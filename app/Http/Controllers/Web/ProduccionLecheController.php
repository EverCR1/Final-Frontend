<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProduccionLecheController extends Controller
{
    
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('Obteniendo listado de producción leche');

        $response = $this->apiService->get('produccion-leche');
        
        $producciones = [];
        if ($response->successful()) {
            $producciones = $response->json();
        }

        return view('produccion-leche.index', compact('producciones'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Obtener animales para el select (solo hembras en producción)
        $animalesResponse = $this->apiService->get('animals');
        $animales = $animalesResponse->successful() ? $animalesResponse->json() : [];

        // Filtrar solo hembras activas
        $animales = array_filter($animales, function($animal) {
            return ($animal['sexo'] ?? '') === 'hembra' && ($animal['estado'] ?? '') === 'activo';
        });

        return view('produccion-leche.create', compact('animales'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Creando nuevo registro de producción leche', $request->all());

        $validated = $request->validate([
            'animal_id' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'cantidad_leche' => 'required|numeric|min:0',
            'calidad_grasa' => 'nullable|numeric|min:0|max:100',
            'calidad_proteina' => 'nullable|numeric|min:0|max:100',
            'turno' => 'required|in:mañana,tarde,noche',
            'observaciones' => 'nullable|string'
        ]);

        $response = $this->apiService->post('produccion-leche', $validated);

        if ($response->successful()) {
            return redirect()->route('produccion-leche.index')
                           ->with('success', 'Registro de producción creado exitosamente');
        }

        return $this->handleApiError($response, 'crear');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        Log::info("Mostrando producción leche ID: {$id}");

        $response = $this->apiService->get("produccion-leche/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('produccion-leche.index')
                           ->with('error', 'Registro de producción no encontrado');
        }

        $produccion = $response->json();

        return view('produccion-leche.show', compact('produccion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info("Editando producción leche ID: {$id}");

        $response = $this->apiService->get("produccion-leche/{$id}");

        if (!$response->successful()) {
            return redirect()->route('produccion-leche.index')
                           ->with('error', 'Registro de producción no encontrado');
        }

        $produccion = $response->json();
        
        // Obtener animales para el select (solo hembras)
        $animalesResponse = $this->apiService->get('animals');
        $animales = $animalesResponse->successful() ? $animalesResponse->json() : [];
        $animales = array_filter($animales, function($animal) {
            return ($animal['sexo'] ?? '') === 'hembra';
        });

        return view('produccion-leche.edit', compact('produccion', 'animales'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        Log::info("Actualizando producción leche ID: {$id}", $request->all());

        $validated = $request->validate([
            'animal_id' => 'required|integer|min:1',
            'fecha' => 'required|date',
            'cantidad_leche' => 'required|numeric|min:0',
            'calidad_grasa' => 'nullable|numeric|min:0|max:100',
            'calidad_proteina' => 'nullable|numeric|min:0|max:100',
            'turno' => 'required|in:mañana,tarde,noche',
            'observaciones' => 'nullable|string'
        ]);

        $response = $this->apiService->post("produccion-leche/{$id}", array_merge($validated, ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('produccion-leche.index')
                           ->with('success', 'Registro de producción actualizado exitosamente');
        }

        return $this->handleApiError($response, 'actualizar');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Log::info("Eliminando producción leche ID: {$id}");

        $response = $this->apiService->post("produccion-leche/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('produccion-leche.index')
                           ->with('success', 'Registro de producción eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el registro de producción');
    }

    /**
     * Mostrar formulario de reportes
     */
    public function reportes()
    {
        return view('produccion-leche.reportes');
    }

    /**
     * Generar reporte de producción
     */
    public function generarReporte(Request $request)
    {
        Log::info('Generando reporte de producción', $request->all());

        $validated = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio'
        ]);

        $response = $this->apiService->post('produccion-leche/reporte', $validated);

        if ($response->successful()) {
            $data = $response->json();
            return view('produccion-leche.reportes', [
                'producciones' => $data['producciones'] ?? [],
                'estadisticas' => $data['estadisticas'] ?? [],
                'filtros' => $validated
            ]);
        }

        return back()->with('error', 'Error al generar el reporte');
    }

    private function handleApiError($response, $action)
    {
        $errorMessage = "Error al {$action} el registro de producción";
        
        if ($response->status() === 422) {
            $errors = $response->json('errors');
            if ($errors) {
                $errorMessage = 'Errores de validación: ' . implode(', ', array_flatten($errors));
            }
        } elseif ($response->status() === 404) {
            $errorMessage = 'Registro de producción no encontrado';
        } elseif ($response->status() === 500) {
            $errorMessage = 'Error del servidor';
        }

        return back()->with('error', $errorMessage)->withInput();
    }

    public function obtenerDatosGraficaDashboard()
    {
        Log::info('Obteniendo datos de producción para gráfica del dashboard');
        
        // Obtener todas las producciones de los últimos 30 días
        $fechaInicio = now()->subDays(30)->format('Y-m-d');
        $fechaFin = now()->format('Y-m-d');
        
        $response = $this->apiService->get('produccion-leche', [
            'fecha_inicio' => $fechaInicio,
            'fecha_fin' => $fechaFin
        ]);
        
        if ($response->successful()) {
            $producciones = $response->json();
            
            // Procesar los datos para la gráfica
            return $this->procesarDatosParaGrafica($producciones, $fechaInicio, $fechaFin);
        }
        
        // En caso de error, retornar array vacío
        return [
            'fechas' => [],
            'litros' => [],
            'total_periodo' => 0,
            'promedio_diario' => 0
        ];
    }

    /**
     * Procesar los datos de producción para formato de gráfica
     */
    private function procesarDatosParaGrafica($producciones, $fechaInicio, $fechaFin)
    {
        // Agrupar por fecha y sumar la producción
        $produccionPorDia = [];
        
        foreach ($producciones as $produccion) {
            $fecha = date('Y-m-d', strtotime($produccion['fecha']));
            if (!isset($produccionPorDia[$fecha])) {
                $produccionPorDia[$fecha] = 0;
            }
            $produccionPorDia[$fecha] += floatval($produccion['cantidad_leche']);
        }
        
        // Crear array con todos los días del período
        $fechas = [];
        $litros = [];
        $fechaActual = \Carbon\Carbon::parse($fechaInicio);
        $fechaFinal = \Carbon\Carbon::parse($fechaFin);
        
        while ($fechaActual <= $fechaFinal) {
            $fechaStr = $fechaActual->format('Y-m-d');
            $fechaFormateada = $fechaActual->format('d/m');
            
            $fechas[] = $fechaFormateada;
            $litros[] = $produccionPorDia[$fechaStr] ?? 0;
            
            $fechaActual->addDay();
        }
        
        $totalPeriodo = array_sum($litros);
        $diasConProduccion = count(array_filter($litros));
        $promedioDiario = $diasConProduccion > 0 ? $totalPeriodo / $diasConProduccion : 0;
        
        return [
            'fechas' => $fechas,
            'litros' => $litros,
            'total_periodo' => $totalPeriodo,
            'promedio_diario' => round($promedioDiario, 2),
            'dias_con_produccion' => $diasConProduccion,
            'periodo' => [
                'inicio' => $fechaInicio,
                'fin' => $fechaFin
            ]
        ];
    }
}
