<?php
namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ProfileAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_read_profile_or_export(): void
    {
        $this->getJson('/api/v1/auth/me')->assertUnauthorized();
        $this->getJson('/api/v1/customers/export/pdf')->assertUnauthorized();
    }

    public function test_registration_and_session_profile(): void
    {
        $this->withHeader('Origin', 'http://127.0.0.1:5174')->postJson('/api/v1/auth/register', [
            'name' => 'Usuario prueba', 'email' => 'USER@example.test', 'password' => 'Testing123!', 'password_confirmation' => 'Testing123!', 'role' => 'Administrador',
        ])->assertCreated()->assertJsonPath('user.email', 'user@example.test')->assertJsonMissingPath('user.password');
        $this->getJson('/api/v1/auth/me')->assertOk()->assertJsonPath('user.role', 'Vendedor');
        $this->assertTrue(Hash::check('Testing123!', User::first()->password));
        $this->postJson('/api/v1/auth/logout')->assertNoContent();
    }

    public function test_wrong_password_and_change_validation(): void
    {
        $user = User::create(['name' => 'Prueba', 'email' => 'login@example.test', 'password' => 'Testing123!']);
        $this->withHeader('Origin', 'http://127.0.0.1:5174')->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'wrong'])->assertUnprocessable();
        $this->postJson('/api/v1/auth/login', ['email' => $user->email, 'password' => 'Testing123!'])->assertOk();
        $this->postJson('/api/v1/auth/change-password', ['current_password' => 'wrong', 'new_password' => 'Updated456!', 'confirm_password' => 'Updated456!'])->assertUnprocessable()->assertJsonValidationErrors('current_password');
        $this->postJson('/api/v1/auth/change-password', ['current_password' => 'Testing123!', 'new_password' => 'Updated456!', 'confirm_password' => 'different'])->assertUnprocessable()->assertJsonValidationErrors('confirm_password');
        $this->postJson('/api/v1/auth/change-password', ['current_password' => 'Testing123!', 'new_password' => 'Updated456!', 'confirm_password' => 'Updated456!'])->assertOk();
        $this->assertTrue(Hash::check('Updated456!', $user->fresh()->password));
        $this->getJson('/api/v1/auth/me')->assertOk();
    }

    public function test_reports_download_real_records(): void
    {
        $user = User::create(['name' => 'Prueba', 'email' => 'reports@example.test', 'password' => 'Testing123!']);
        $this->actingAs($user);
        DB::table('customers')->insert(['name' => 'Cliente real', 'document_number' => '00123456']);
        DB::table('products')->insert(['name' => 'Extintor', 'sku' => 'BF-1', 'price' => 150, 'stock' => 3]);
        $this->getJson('/api/v1/customers/export/pdf?q=Cliente')->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $this->get('/api/v1/catalog/export/excel')->assertOk()->assertHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->getJson('/api/v1/customers/export/pdf?q[]=invalid')->assertUnprocessable();
    }

    public function test_preferences_are_validated_and_isolated_per_user(): void
    {
        $user = User::create(['name' => 'Prueba', 'email' => 'reports@example.test', 'password' => 'Testing123!']);
        $this->actingAs($user);
        $this->putJson('/api/v1/auth/preferences', ['theme' => 'invalid', 'density' => 'compact'])->assertUnprocessable();
        $this->putJson('/api/v1/auth/preferences', ['theme' => 'dark', 'density' => 'compact'])->assertOk();
        $this->getJson('/api/v1/auth/preferences')->assertJsonPath('preferences.theme', 'dark');
        $this->assertSame('compact', $user->fresh()->preferences['density']);
        $other = User::create(['name' => 'Otro', 'email' => 'other@example.test', 'password' => 'Testing123!']);
        $this->actingAs($other)->getJson('/api/v1/auth/preferences')->assertJsonPath('preferences.theme', 'system');
    }
}
