<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function showLogin()
    {
        // Si ya está autenticado, redirigir al dashboard
        if ($this->apiService->isAuthenticated()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $result = $this->apiService->login(
            $request->email, 
            $request->password
        );

        if ($result) {
            return redirect()->route('dashboard')->with('success', 'Bienvenido al sistema');
        }

        return back()->with('error', 'Credenciales incorrectas')->withInput();
    }

    public function logout(Request $request)
    {
        $this->apiService->logout();
        return redirect()->route('login')->with('success', 'Sesión cerrada exitosamente');
    }
}