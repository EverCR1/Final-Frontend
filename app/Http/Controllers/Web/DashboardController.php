<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        if (!$this->apiService->isAuthenticated()) {
            return redirect()->route('login');
        }

        // Obtener datos del dashboard desde API
        $response = $this->apiService->get('reportes/dashboard');
        
        $data = [];
        if ($response->successful()) {
            $data = $response->json();
        }

        return view('dashboard', compact('data'));
    }
}