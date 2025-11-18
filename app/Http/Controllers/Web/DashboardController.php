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

        // Obtener datos de producción para la gráfica
        $produccionController = new \App\Http\Controllers\Web\ProduccionLecheController($this->apiService);
        $datosGrafica = $produccionController->obtenerDatosGraficaDashboard();
        
        // Incluir los datos en la respuesta
        $data['estadisticas_generales']['grafica_produccion'] = $datosGrafica;

        return view('dashboard', compact('data'));
    }
}