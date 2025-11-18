<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReporteController extends Controller
{
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    /**
     * Verificar permisos de administrador
     */
    private function checkAdminPermission()
    {
        if (session('user.role') !== 'admin') {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }
    }

    /**
     * Dashboard principal
     */
    public function dashboard()
    {
        $this->checkAdminPermission();
        Log::info('Cargando dashboard de reportes');

        $response = $this->apiService->get('reportes/dashboard');
        
        $estadisticas = [];
        if ($response->successful()) {
            $data = $response->json();
            $estadisticas = $data['estadisticas_generales'] ?? [];
        }

        return view('reportes.dashboard', compact('estadisticas'));
    }

    /**
     * Reporte de animales por finca
     */
    public function animalesPorFinca()
    {
        $this->checkAdminPermission();
        Log::info('Generando reporte de animales por finca');

        $response = $this->apiService->get('reportes/animales-por-finca');
        
        $animalesPorFinca = [];
        if ($response->successful()) {
            $animalesPorFinca = $response->json();
        }

        return view('reportes.animales-finca', compact('animalesPorFinca'));
    }

    /**
     * Reporte de producción mensual
     */
    public function produccionMensual(Request $request)
    {
        $this->checkAdminPermission();
        Log::info('Generando reporte de producción mensual');

        $year = $request->get('year', date('Y'));
        $month = $request->get('month', date('m'));

        $response = $this->apiService->get("reportes/produccion-mensual/{$year}/{$month}");
        
        $reporte = [];
        if ($response->successful()) {
            $reporte = $response->json();
        }

        return view('reportes.produccion-mensual', compact('reporte', 'year', 'month'));
    }

    /**
     * Reporte de salud animal
     */
    public function reporteSalud()
    {
        $this->checkAdminPermission();
        Log::info('Generando reporte de salud animal');

        $response = $this->apiService->get('reportes/reporte-salud');
        
        $reporteSalud = [];
        if ($response->successful()) {
            $reporteSalud = $response->json();
            
            // Convertir arrays a Collections
            if (isset($reporteSalud['distribucion_estados'])) {
                $reporteSalud['distribucion_estados'] = collect($reporteSalud['distribucion_estados']);
            }
            
            if (isset($reporteSalud['vacunaciones_recientes'])) {
                $reporteSalud['vacunaciones_recientes'] = collect($reporteSalud['vacunaciones_recientes']);
            }
        }

        return view('reportes.salud-animal', compact('reporteSalud'));
    }

    /**
     * Página principal de reportes
     */
    public function index()
    {
        $this->checkAdminPermission();
        return view('reportes.index');
    }
}