<?php

namespace App\Traits;


// use Illuminate\Container\Attributes\Auth; for debugging
// use Illuminate\Support\Facades\Auth; for debugging
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Traits\HttpResponses;

trait Jwt
{
    use HttpResponses;

    /**
     * Attempt to authenticate user and generate JWT token
     *
     * @param array $credentials User credentials (typically email and password)
     * @return mixed Returns token string on success, error response on failure
     */
    public function jwtToken($credentials)
    {
        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                // dd('Échec de l\'authentification'); // Debug
                return $this->error(null, 'Invalid credentials', 401);
            }
        } catch (JWTException $e) {
            return $this->error(null, 'Could not create token error: ' . $e, 500);
        }
        // dd('Token généré :', $token); // Vérifier si le token est bien créé
        return $token;
    }

    /**
     * Invalidate JWT token
     *
     * @return mixed Returns true on success, error response on failure
     */
    public function invalidateToken()
    {
        try {
            JWTAuth::invalidate(JWTAuth::getToken());
        } catch (JWTException $e) {
            return $this->error(null, 'Could not invalidate token', 500);
        }
        return true;
    }


    /**
     * Get JWT token
     *
     * @return mixed Returns token string on success, error response on failure
     */
    public function getToken()
    {
        try {
            $token = JWTAuth::getToken()->get();
            // dd($token); // Debug
            return $token;
        } catch (JWTException $e) {
            return $this->error(null, 'Could not get token', 500);
        }
    }

    /**
     * Refresh JWT token
     *
     * @return mixed Returns token string on success, error response on failure
     */
    public function refreshToken()
    {
        try {
            $token = JWTAuth::refresh()->get();
            // dd($token); // Debug
            return $token;
        } catch (JWTException $e) {
            return $this->error(null, 'Could not refresh token', 500);
        }
    }
}
