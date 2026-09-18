<?php
namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->merge(['email' => strtolower((string) $request->input('email'))]);
        $data = $request->validate([
            'name' => 'required|string|max:150', 'email' => 'required|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:30', 'password' => 'required|string|min:8|max:72|confirmed',
        ]);
        $data['email'] = strtolower($data['email']);
        $user = User::create($data)->refresh();
        Auth::guard('web')->login($user);
        $request->session()->regenerate();
        return response()->json(['user' => $user], 201);
    }

    public function login(Request $request)
    {
        $data = $request->validate(['email' => 'required|email', 'password' => 'required|string']);
        $data['email'] = strtolower($data['email']);
        if (!Auth::guard('web')->attempt($data)) {
            throw ValidationException::withMessages(['email' => ['El correo o la contrasena no son correctos.']]);
        }
        $request->session()->regenerate();
        return response()->json(['user' => Auth::guard('web')->user()]);
    }

    public function me(Request $request) { return response()->json(['user' => $request->user()]); }

    public function changePassword(Request $request)
    {
        $data = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|max:72|different:current_password',
            'confirm_password' => 'required|same:new_password',
        ]);
        $user = $request->user();
        if (!Hash::check($data['current_password'], $user->password)) {
            throw ValidationException::withMessages(['current_password' => ['La contrasena actual no es correcta.']]);
        }
        $user->password = $data['new_password'];
        $user->save();
        $user->tokens()->delete();
        if ($request->hasSession()) {
            $request->session()->regenerate();
            $request->session()->put('password_hash_web', $user->password);
        }
        return response()->json(['message' => 'Contrasena actualizada correctamente.']);
    }

    public function logout(Request $request)
    {
        $token = $request->user()?->currentAccessToken();
        Auth::guard('web')->logout();
        if ($request->hasSession()) {
            $request->session()->invalidate(); $request->session()->regenerateToken();
        }
        if ($token instanceof \Laravel\Sanctum\PersonalAccessToken) { $token->delete(); }
        return response()->noContent();
    }
}
