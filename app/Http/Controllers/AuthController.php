<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;
use App\Traits\Jwt;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;
use App\Notifications\ActivationNotification;
use App\Notifications\ResetPasswordNotification;

class AuthController extends Controller
{
    use HttpResponses, Jwt;


    /**
     * Handle user login.
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();
        $token = $this->jwtToken($credentials);
        if (!Auth::user()) {
            return $this->error(null, 'Authentication failed', 401);
        }
        return $this->success([
            'token' => $token,
            'user' => Auth::user(),
        ], 'Login successful');
    }

    /**
     * Handle user registration.
     */
    public function register(StoreUserRequest $request)
    {
        try {
            $token = Str::random(60);
            // Vérification et attribution du rôle 'tourist'
            $roleId = static::getRoleId('admin');
            $user = User::create([
                'name' => $request->validated('name'),
                'username' => Str::lower(Str::slug($request->validated('name'))) . '-' . Str::lower(Str::random(4)),
                'email' => $request->validated('email'),
                'role_id' => $roleId,
                'activation_token' => $token,
                'password' => Hash::make($request->validated('password')),
            ]);

            if ($user) {
                // Envoyer un e-mail d'activation
                $user->notify(new ActivationNotification($token));
            }
            return $this->success([
                'user' => $user,
                'token' => $this->jwtToken(['email' => $user->email, 'password' => $request->password]),
            ], 'User registered successfully', 201);
        } catch (Exception $e) {
            return $this->error(null, 'User registration failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Activate user account.
     */
    public function activateAccount(Request $request)
    {
        $request->validate(['token' => 'required']);
        $user = User::where('activation_token', $request->token)->first();
        if (!$user) {
            return $this->error(null, 'Invalid token', 400);
        }
        try {
            $user->update(['activated' => true, 'activation_token' => null, 'email_verified_at' => now()]);
            return $this->success(
                null,
                'Account activated successfully'
            );
        } catch (Exception $e) {
            return $this->error(null, 'Account activation failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Handle user logout.
     */
    public function logout()
    {
        try {
            if ($this->invalidateToken()) {
                return $this->success(['token' => $this->getToken(), 'user' => Auth::user()], 'Successfully logged out');
            }
        } catch (Exception $e) {
            return $this->error(null, 'Logout failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Send a password reset link via email.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);
        try {
            $token = Str::random(60);
            PasswordResetToken::updateOrCreate(
                ['email' => $request->email],
                ['token' => $token, 'created_at' => now()]
            );
            $user = User::where('email', $request->email)->firstOrFail();
            $user->notify(new ResetPasswordNotification($token));
            return $this->success(null, 'Password reset email sent.');
        } catch (Exception $e) {
            return $this->error(null, 'Failed to send reset email: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Reset user password.
     */
    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
        if ($validator->fails()) {
            return $this->error($validator->errors(), 'Invalid input', 400);
        }
        $reset = PasswordResetToken::where('email', $request->email)
            ->where('token', $request->token)
            ->first();
        if (!$reset || Carbon::parse($reset->created_at)->addMinutes(60)->isPast()) {
            return $this->error(null, 'Invalid or expired token', 400);
        }
        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $user->update(['password' => Hash::make($request->password)]);
            $reset->delete();
            return $this->success(null, 'Password successfully reset.');
        } catch (Exception $e) {
            return $this->error(null, 'Password reset failed: ' . $e->getMessage(), 500);
        }
    }

    /**
     * Get the ID of a user role.
     */
    public static function getRoleId($role)
    {
        // Assurez-vous que le rôle existe avant d'essayer de récupérer son ID
        $role = Role::where('name', $role)->first();
        return $role ? $role->id : null;
    }

}
