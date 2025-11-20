<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;

class ApiService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('api.base_url');
    }

    public function login($email, $password)
    {
        try {
            $response = Http::post("{$this->baseUrl}/api/login", [
                'email' => $email,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                
                if (isset($data['access_token'])) {
                    Session::put('api_token', $data['access_token']);
                    Session::put('user', $data['user']);
                    return [
                        'success' => true,
                        'data' => $data
                    ];
                }
            }

            return [
                'success' => false,
                'status' => $response->status(),
                'error' => 'Credenciales incorrectas'
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => 'Error de conexión'
            ];
        }
    }

    public function get($endpoint)
    {
        return Http::withToken(Session::get('api_token'))
                  ->get("{$this->baseUrl}/api/{$endpoint}");
    }

    public function post($endpoint, $data)
    {
        return Http::withToken(Session::get('api_token'))
                  ->post("{$this->baseUrl}/api/{$endpoint}", $data);
    }

    public function logout()
    {
        Http::withToken(Session::get('api_token'))
            ->post("{$this->baseUrl}/api/logout");
        
        Session::forget(['api_token', 'user']);
    }

    public function isAuthenticated()
    {
        return Session::has('api_token');
    }
}