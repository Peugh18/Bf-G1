<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Throwable;

class HealthCheckController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $dbStatus = $this->checkDatabase();
        $storageStatus = $this->checkStorage();
        $missingEnvironment = $this->missingEnvironmentVariables();

        $isDatabaseHealthy = $dbStatus === 'connected';
        $status = $isDatabaseHealthy && $storageStatus === 'writable' && empty($missingEnvironment)
            ? 'ok'
            : 'degraded';

        return response()->json([
            'status' => $status,
            'timestamp' => now()->toISOString(),
            'db_status' => $dbStatus,
            'storage_status' => $storageStatus,
            'environment' => app()->environment(),
            'env_status' => empty($missingEnvironment) ? 'configured' : 'missing_required_variables',
            'missing_env_vars' => $missingEnvironment,
        ], $isDatabaseHealthy ? 200 : 503);
    }

    private function checkDatabase(): string
    {
        try {
            DB::connection()->select('select 1');

            return 'connected';
        } catch (Throwable $exception) {
            report($exception);

            return 'unavailable';
        }
    }

    private function checkStorage(): string
    {
        $directory = function_exists('storage_path')
            ? storage_path('framework/cache')
            : sys_get_temp_dir();

        if (! is_dir($directory) && ! @mkdir($directory, 0775, true) && ! is_dir($directory)) {
            return 'unavailable';
        }

        $file = $directory . DIRECTORY_SEPARATOR . 'healthcheck_' . uniqid('', true) . '.tmp';

        try {
            if (@file_put_contents($file, 'ok') === false) {
                return 'unwritable';
            }

            return @file_get_contents($file) === 'ok' ? 'writable' : 'unreadable';
        } finally {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }

    /**
     * APP_KEY can be absent in early local scaffolds, but database connection data is critical here.
     */
    private function missingEnvironmentVariables(): array
    {
        $requiredVariables = [
            'APP_ENV',
            'DB_CONNECTION',
            'DB_HOST',
            'DB_PORT',
            'DB_DATABASE',
            'DB_USERNAME',
        ];

        return array_values(array_filter($requiredVariables, static function (string $variable): bool {
            $value = env($variable);

            return $value === null || $value === '';
        }));
    }
}
