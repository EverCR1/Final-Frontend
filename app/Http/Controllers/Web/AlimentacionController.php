<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AlimentacionController extends Controller
{
    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    // ==================== DASHBOARD PRINCIPAL ====================
    public function index()
    {
        // Obtener datos para el dashboard
        $alimentosResponse = $this->apiService->get('alimentos');
        $dietasResponse = $this->apiService->get('dietas');
        $registrosResponse = $this->apiService->get('registros-alimentacion');
        $stockBajoResponse = $this->apiService->get('alimentos/stock/bajo');

        $data = [
            'alimentos' => $alimentosResponse->successful() ? $alimentosResponse->json() : [],
            'dietas' => $dietasResponse->successful() ? $dietasResponse->json() : [],
            'registros' => $registrosResponse->successful() ? $registrosResponse->json() : [],
            'stockBajo' => $stockBajoResponse->successful() ? $stockBajoResponse->json() : [],
        ];

        return view('alimentacion.index', compact('data'));
    }

    // ==================== ALIMENTOS ====================

    public function alimentosIndex()
    {
        $response = $this->apiService->get('alimentos');
        $alimentos = $response->successful() ? $response->json() : [];

        // Obtener fincas para el filtro
        $fincasResponse = $this->apiService->get('fincas');
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('alimentacion.alimentos.index', compact('alimentos', 'fincas'));
    }

    public function alimentosCreate()
    {
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('alimentacion.alimentos.index')
                        ->with('error', 'No tienes permisos para crear alimentos');
        }

        $fincasResponse = $this->apiService->get('fincas');
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('alimentacion.alimentos.create', compact('fincas'));
    }

    public function alimentosStore(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|numeric|min:1',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:concentrado,forraje,suplemento,mineral,otro',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'nullable|numeric|min:0',
            'fecha_vencimiento' => 'nullable|date',
            'proveedor' => 'nullable|string|max:255',
        ]);

        $response = $this->apiService->post('alimentos', $request->all());

        if ($response->successful()) {
            return redirect()->route('alimentacion.alimentos.index')
                        ->with('success', 'Alimento creado exitosamente');
        }

        return back()->with('error', 'Error al crear el alimento')->withInput();
    }

    public function alimentosShow($id)
    {
        $response = $this->apiService->get("alimentos/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('alimentacion.alimentos.index')
                        ->with('error', 'Alimento no encontrado');
        }

        $alimento = $response->json();
        return view('alimentacion.alimentos.show', compact('alimento'));
    }

    public function alimentosEdit($id)
    {
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('alimentacion.alimentos.index')
                        ->with('error', 'No tienes permisos para editar alimentos');
        }

        $alimentoResponse = $this->apiService->get("alimentos/{$id}");
        $fincasResponse = $this->apiService->get('fincas');

        if (!$alimentoResponse->successful()) {
            return redirect()->route('alimentacion.alimentos.index')
                        ->with('error', 'Alimento no encontrado');
        }

        $alimento = $alimentoResponse->json();
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('alimentacion.alimentos.edit', compact('alimento', 'fincas'));
    }

    public function alimentosUpdate(Request $request, $id)
    {
        $request->validate([
            'finca_id' => 'required|numeric|min:1',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:concentrado,forraje,suplemento,mineral,otro',
            'descripcion' => 'nullable|string',
            'unidad_medida' => 'required|string|max:50',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'nullable|numeric|min:0',
            'fecha_vencimiento' => 'nullable|date',
            'proveedor' => 'nullable|string|max:255',
        ]);

        $response = $this->apiService->post("alimentos/{$id}", array_merge($request->all(), ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('alimentacion.alimentos.index')
                        ->with('success', 'Alimento actualizado exitosamente');
        }

        return back()->with('error', 'Error al actualizar el alimento')->withInput();
    }

    public function alimentosDestroy($id)
    {
        $response = $this->apiService->post("alimentos/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('alimentacion.alimentos.index')
                        ->with('success', 'Alimento eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el alimento');
    }

    // ==================== DIETAS ====================

    public function dietasIndex()
    {
        $response = $this->apiService->get('dietas');
        $dietas = $response->successful() ? $response->json() : [];

        return view('alimentacion.dietas.index', compact('dietas'));
    }

    public function dietasCreate()
    {
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('alimentacion.dietas.index')
                        ->with('error', 'No tienes permisos para crear dietas');
        }

        $fincasResponse = $this->apiService->get('fincas');
        $alimentosResponse = $this->apiService->get('alimentos');

        $data = [
            'fincas' => $fincasResponse->successful() ? $fincasResponse->json() : [],
            'alimentos' => $alimentosResponse->successful() ? $alimentosResponse->json() : [],
        ];

        return view('alimentacion.dietas.create', compact('data'));
    }

    public function dietasStore(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|numeric|min:1',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_animal' => 'required|in:bovino,porcino,caprino,ovina',
            'categoria' => 'required|in:ternero,desarrollo,adulto,lactancia,gestacion',
            'activa' => 'boolean',
            'alimentos' => 'required|array|min:1',
            'alimentos.*.alimento_id' => 'required|numeric|min:1',
            'alimentos.*.cantidad' => 'required|numeric|min:0',
            'alimentos.*.frecuencia' => 'required|string|max:100',
        ]);

        $response = $this->apiService->post('dietas', $request->all());

        if ($response->successful()) {
            return redirect()->route('alimentacion.dietas.index')
                        ->with('success', 'Dieta creada exitosamente');
        }

        return back()->with('error', 'Error al crear la dieta')->withInput();
    }

    public function dietasShow($id)
    {
        $response = $this->apiService->get("dietas/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('alimentacion.dietas.index')
                        ->with('error', 'Dieta no encontrada');
        }

        $dieta = $response->json();
        return view('alimentacion.dietas.show', compact('dieta'));
    }

    public function dietasEdit($id)
    {
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('alimentacion.dietas.index')
                        ->with('error', 'No tienes permisos para editar dietas');
        }

        $dietaResponse = $this->apiService->get("dietas/{$id}");
        $fincasResponse = $this->apiService->get('fincas');
        $alimentosResponse = $this->apiService->get('alimentos');

        if (!$dietaResponse->successful()) {
            return redirect()->route('alimentacion.dietas.index')
                        ->with('error', 'Dieta no encontrada');
        }

        $dieta = $dietaResponse->json();
        $data = [
            'fincas' => $fincasResponse->successful() ? $fincasResponse->json() : [],
            'alimentos' => $alimentosResponse->successful() ? $alimentosResponse->json() : [],
        ];

        return view('alimentacion.dietas.edit', compact('dieta', 'data'));
    }

    public function dietasUpdate(Request $request, $id)
    {
        $request->validate([
            'finca_id' => 'required|numeric|min:1',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tipo_animal' => 'required|in:bovino,porcino,caprino,ovina',
            'categoria' => 'required|in:ternero,desarrollo,adulto,lactancia,gestacion',
            'activa' => 'boolean',
            'alimentos' => 'sometimes|array|min:1',
            'alimentos.*.alimento_id' => 'required|numeric|min:1',
            'alimentos.*.cantidad' => 'required|numeric|min:0',
            'alimentos.*.frecuencia' => 'required|string|max:100',
        ]);

        $response = $this->apiService->post("dietas/{$id}", array_merge($request->all(), ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('alimentacion.dietas.index')
                        ->with('success', 'Dieta actualizada exitosamente');
        }

        return back()->with('error', 'Error al actualizar la dieta')->withInput();
    }

    public function dietasDestroy($id)
    {
        $response = $this->apiService->post("dietas/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('alimentacion.dietas.index')
                        ->with('success', 'Dieta eliminada exitosamente');
        }

        return back()->with('error', 'Error al eliminar la dieta');
    }

    // ==================== REGISTROS DE ALIMENTACIÓN ====================

    public function registrosIndex()
    {
        $response = $this->apiService->get('registros-alimentacion');
        $registros = $response->successful() ? $response->json() : [];

        // Obtener fincas para el filtro
        $fincasResponse = $this->apiService->get('fincas');
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('alimentacion.registros.index', compact('registros', 'fincas'));
    }

    public function registrosCreate()
    {
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('alimentacion.registros.index')
                        ->with('error', 'No tienes permisos para crear registros');
        }

        $fincasResponse = $this->apiService->get('fincas');
        $animalesResponse = $this->apiService->get('animals');
        $dietasResponse = $this->apiService->get('dietas');

        $data = [
            'fincas' => $fincasResponse->successful() ? $fincasResponse->json() : [],
            'animales' => $animalesResponse->successful() ? $animalesResponse->json() : [],
            'dietas' => $dietasResponse->successful() ? $dietasResponse->json() : [],
        ];

        return view('alimentacion.registros.create', compact('data'));
    }

    public function registrosStore(Request $request)
    {
        $request->validate([
            'finca_id' => 'required|numeric|min:1',
            'animal_id' => 'nullable|numeric|min:1',
            'dieta_id' => 'required|numeric|min:1',
            'fecha' => 'required|date',
            'cantidad_total' => 'required|numeric|min:0',
            'turno' => 'required|in:mañana,tarde,noche',
            'observaciones' => 'nullable|string',
            'responsable' => 'required|string|max:255',
        ]);

        $response = $this->apiService->post('registros-alimentacion', $request->all());

        if ($response->successful()) {
            return redirect()->route('alimentacion.registros.index')
                        ->with('success', 'Registro de alimentación creado exitosamente');
        }

        return back()->with('error', 'Error al crear el registro de alimentación')->withInput();
    }

    public function registrosShow($id)
    {
        $response = $this->apiService->get("registros-alimentacion/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('alimentacion.registros.index')
                        ->with('error', 'Registro de alimentación no encontrado');
        }

        $registro = $response->json();
        return view('alimentacion.registros.show', compact('registro'));
    }

    public function registrosEdit($id)
    {
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('alimentacion.registros.index')
                        ->with('error', 'No tienes permisos para editar registros');
        }

        $registroResponse = $this->apiService->get("registros-alimentacion/{$id}");
        $fincasResponse = $this->apiService->get('fincas');
        $animalesResponse = $this->apiService->get('animals');
        $dietasResponse = $this->apiService->get('dietas');

        if (!$registroResponse->successful()) {
            return redirect()->route('alimentacion.registros.index')
                        ->with('error', 'Registro de alimentación no encontrado');
        }

        $registro = $registroResponse->json();
        $data = [
            'fincas' => $fincasResponse->successful() ? $fincasResponse->json() : [],
            'animales' => $animalesResponse->successful() ? $animalesResponse->json() : [],
            'dietas' => $dietasResponse->successful() ? $dietasResponse->json() : [],
        ];

        return view('alimentacion.registros.edit', compact('registro', 'data'));
    }

    public function registrosUpdate(Request $request, $id)
    {
        $request->validate([
            'finca_id' => 'required|numeric|min:1',
            'animal_id' => 'nullable|numeric|min:1',
            'dieta_id' => 'required|numeric|min:1',
            'fecha' => 'required|date',
            'cantidad_total' => 'required|numeric|min:0',
            'turno' => 'required|in:mañana,tarde,noche',
            'observaciones' => 'nullable|string',
            'responsable' => 'required|string|max:255',
        ]);

        $response = $this->apiService->post("registros-alimentacion/{$id}", array_merge($request->all(), ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('alimentacion.registros.index')
                        ->with('success', 'Registro de alimentación actualizado exitosamente');
        }

        return back()->with('error', 'Error al actualizar el registro de alimentación')->withInput();
    }

    public function registrosDestroy($id)
    {
        $response = $this->apiService->post("registros-alimentacion/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('alimentacion.registros.index')
                        ->with('success', 'Registro de alimentación eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el registro de alimentación');
    }

    // ==================== REPORTES Y ESTADÍSTICAS ====================
    public function reportes()
    {
        try {
            // Obtener datos para los filtros
            $fincasResponse = $this->apiService->get('fincas');
            $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

            // Obtener todos los registros para estadísticas
            $registrosResponse = $this->apiService->get('registros-alimentacion');
            $todosRegistros = $registrosResponse->successful() ? $registrosResponse->json() : [];
            
            // Registros recientes (últimos 10)
            $registrosRecientes = array_slice($todosRegistros, 0, 10);

            // Calcular estadísticas básicas con todos los registros
            $estadisticas = [
                'total_registros' => count($todosRegistros),
                'cantidad_total' => collect($todosRegistros)->sum('cantidad_total') ?? 0,
                'costo_total' => collect($todosRegistros)->sum('costo_total') ?? 0,
                'costo_promedio_kg' => count($todosRegistros) > 0 ? 
                    (collect($todosRegistros)->sum('costo_total') ?? 0) / max(collect($todosRegistros)->sum('cantidad_total'), 1) : 0
            ];

            // Datos para gráfica de turnos
            $grafica_turnos = [
                'mañana' => collect($todosRegistros)->where('turno', 'mañana')->count(),
                'tarde' => collect($todosRegistros)->where('turno', 'tarde')->count(),
                'noche' => collect($todosRegistros)->where('turno', 'noche')->count()
            ];

            // Datos para gráfica de dietas (top 5)
            $grafica_dietas = [];
            foreach ($todosRegistros as $registro) {
                if (isset($registro['dieta']['nombre'])) {
                    $dietaNombre = $registro['dieta']['nombre'];
                    $grafica_dietas[$dietaNombre] = ($grafica_dietas[$dietaNombre] ?? 0) + 1;
                }
            }
            
            // Ordenar y tomar solo las top 5 dietas
            arsort($grafica_dietas);
            $grafica_dietas = array_slice($grafica_dietas, 0, 5, true);

            // Resumen por fincas
            $resumen_fincas = [];
            foreach ($todosRegistros as $registro) {
                if (isset($registro['finca']['nombre'])) {
                    $fincaNombre = $registro['finca']['nombre'];
                    if (!isset($resumen_fincas[$fincaNombre])) {
                        $resumen_fincas[$fincaNombre] = [
                            'nombre' => $fincaNombre,
                            'total_registros' => 0,
                            'cantidad_total' => 0,
                            'costo_total' => 0
                        ];
                    }
                    $resumen_fincas[$fincaNombre]['total_registros']++;
                    $resumen_fincas[$fincaNombre]['cantidad_total'] += $registro['cantidad_total'] ?? 0;
                    $resumen_fincas[$fincaNombre]['costo_total'] += $registro['costo_total'] ?? 0;
                }
            }
            $resumen_fincas = array_values($resumen_fincas);

            return view('alimentacion.reportes', compact(
                'fincas',
                'registrosRecientes',
                'estadisticas',
                'grafica_turnos',
                'grafica_dietas',
                'resumen_fincas'
            ));

        } catch (\Exception $e) {
            // En caso de error, retornar valores por defecto
            return view('alimentacion.reportes', [
                'fincas' => [],
                'registrosRecientes' => [],
                'estadisticas' => [
                    'total_registros' => 0,
                    'cantidad_total' => 0,
                    'costo_total' => 0,
                    'costo_promedio_kg' => 0
                ],
                'grafica_turnos' => [
                    'mañana' => 0,
                    'tarde' => 0,
                    'noche' => 0
                ],
                'grafica_dietas' => [],
                'resumen_fincas' => []
            ]);
        }
    }

    public function generarReporte(Request $request)
    {
        try {
            // Validar los datos del request
            $request->validate([
                'fecha_inicio' => 'required|date',
                'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
                'tipo_reporte' => 'required|in:general,costos,dietas,turnos,fincas',
                'finca_id' => 'nullable|numeric|min:1'
            ]);

            // Obtener parámetros del request
            $fechaInicio = $request->fecha_inicio;
            $fechaFin = $request->fecha_fin;
            $tipoReporte = $request->tipo_reporte;
            $fincaId = $request->finca_id;

            // Obtener todos los registros
            $registrosResponse = $this->apiService->get('registros-alimentacion');
            $todosRegistros = $registrosResponse->successful() ? $registrosResponse->json() : [];

            // Filtrar registros por fecha
            $registrosFiltrados = collect($todosRegistros)->filter(function($registro) use ($fechaInicio, $fechaFin) {
                $fechaRegistro = $registro['fecha'];
                return $fechaRegistro >= $fechaInicio && $fechaRegistro <= $fechaFin;
            });

            // Filtrar por finca si se especificó
            if ($fincaId) {
                $registrosFiltrados = $registrosFiltrados->filter(function($registro) use ($fincaId) {
                    return $registro['finca_id'] == $fincaId;
                });
            }

            $registrosFiltrados = $registrosFiltrados->values();

            // Generar reporte según el tipo
            $reporteData = $this->generarDatosReporte($tipoReporte, $registrosFiltrados, $fechaInicio, $fechaFin);

            // Obtener fincas para los filtros
            $fincasResponse = $this->apiService->get('fincas');
            $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

            // Retornar a la vista con los datos del reporte
            return view('alimentacion.reportes', array_merge($reporteData, [
                'fincas' => $fincas,
                'filtros' => [
                    'fecha_inicio' => $fechaInicio,
                    'fecha_fin' => $fechaFin,
                    'tipo_reporte' => $tipoReporte,
                    'finca_id' => $fincaId
                ]
            ]));

        } catch (\Exception $e) {
            return redirect()->route('alimentacion.reportes')
                ->with('error', 'Error al generar el reporte: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Generar datos específicos para cada tipo de reporte
     */
    private function generarDatosReporte($tipoReporte, $registros, $fechaInicio, $fechaFin)
    {
        $estadisticas = [
            'total_registros' => count($registros),
            'cantidad_total' => collect($registros)->sum('cantidad_total') ?? 0,
            'costo_total' => collect($registros)->sum('costo_total') ?? 0,
            'costo_promedio_kg' => count($registros) > 0 ? 
                (collect($registros)->sum('costo_total') ?? 0) / max(collect($registros)->sum('cantidad_total'), 1) : 0
        ];

        // Datos comunes para todos los reportes
        $datosComunes = [
            'registros_recientes' => $registros->take(10)->values(),
            'estadisticas' => $estadisticas,
            'grafica_turnos' => [
                'mañana' => $registros->where('turno', 'mañana')->count(),
                'tarde' => $registros->where('turno', 'tarde')->count(),
                'noche' => $registros->where('turno', 'noche')->count()
            ],
        ];

        // Datos específicos por tipo de reporte
        switch ($tipoReporte) {
            case 'costos':
                return array_merge($datosComunes, $this->generarReporteCostos($registros, $fechaInicio, $fechaFin));
            
            case 'dietas':
                return array_merge($datosComunes, $this->generarReporteDietas($registros, $fechaInicio, $fechaFin));
            
            case 'turnos':
                return array_merge($datosComunes, $this->generarReporteTurnos($registros, $fechaInicio, $fechaFin));
            
            case 'fincas':
                return array_merge($datosComunes, $this->generarReporteFincas($registros, $fechaInicio, $fechaFin));
            
            case 'general':
            default:
                return array_merge($datosComunes, $this->generarReporteGeneral($registros, $fechaInicio, $fechaFin));
        }
    }

    /**
     * Reporte General
     */
    private function generarReporteGeneral($registros, $fechaInicio, $fechaFin)
    {
        // Top 5 dietas más usadas
        $grafica_dietas = [];
        foreach ($registros as $registro) {
            if (isset($registro['dieta']['nombre'])) {
                $dietaNombre = $registro['dieta']['nombre'];
                $grafica_dietas[$dietaNombre] = ($grafica_dietas[$dietaNombre] ?? 0) + 1;
            }
        }
        arsort($grafica_dietas);
        $grafica_dietas = array_slice($grafica_dietas, 0, 5, true);

        // Resumen por fincas
        $resumen_fincas = [];
        foreach ($registros as $registro) {
            if (isset($registro['finca']['nombre'])) {
                $fincaNombre = $registro['finca']['nombre'];
                if (!isset($resumen_fincas[$fincaNombre])) {
                    $resumen_fincas[$fincaNombre] = [
                        'nombre' => $fincaNombre,
                        'total_registros' => 0,
                        'cantidad_total' => 0,
                        'costo_total' => 0
                    ];
                }
                $resumen_fincas[$fincaNombre]['total_registros']++;
                $resumen_fincas[$fincaNombre]['cantidad_total'] += $registro['cantidad_total'] ?? 0;
                $resumen_fincas[$fincaNombre]['costo_total'] += $registro['costo_total'] ?? 0;
            }
        }
        $resumen_fincas = array_values($resumen_fincas);

        return [
            'grafica_dietas' => $grafica_dietas,
            'resumen_fincas' => $resumen_fincas,
            'titulo_reporte' => 'Reporte General de Alimentación',
            'descripcion_reporte' => "Período: {$fechaInicio} al {$fechaFin}"
        ];
    }

    /**
     * Reporte de Costos
     */
    private function generarReporteCostos($registros, $fechaInicio, $fechaFin)
    {
        // Costo por día
        $costoPorDia = [];
        foreach ($registros as $registro) {
            $fecha = $registro['fecha'];
            $costoPorDia[$fecha] = ($costoPorDia[$fecha] ?? 0) + ($registro['costo_total'] ?? 0);
        }
        ksort($costoPorDia);

        // Costo por dieta
        $costoPorDieta = [];
        foreach ($registros as $registro) {
            if (isset($registro['dieta']['nombre'])) {
                $dietaNombre = $registro['dieta']['nombre'];
                $costoPorDieta[$dietaNombre] = ($costoPorDieta[$dietaNombre] ?? 0) + ($registro['costo_total'] ?? 0);
            }
        }
        arsort($costoPorDieta);

        return [
            'grafica_dietas' => $costoPorDieta,
            'costo_por_dia' => $costoPorDia,
            'resumen_fincas' => [],
            'titulo_reporte' => 'Análisis de Costos de Alimentación',
            'descripcion_reporte' => "Período: {$fechaInicio} al {$fechaFin} - Total: Q" . number_format(collect($registros)->sum('costo_total'), 2)
        ];
    }

    /**
     * Reporte de Dietas
     */
    private function generarReporteDietas($registros, $fechaInicio, $fechaFin)
    {
        // Uso de dietas
        $usoDietas = [];
        foreach ($registros as $registro) {
            if (isset($registro['dieta']['nombre'])) {
                $dietaNombre = $registro['dieta']['nombre'];
                if (!isset($usoDietas[$dietaNombre])) {
                    $usoDietas[$dietaNombre] = [
                        'nombre' => $dietaNombre,
                        'total_registros' => 0,
                        'cantidad_total' => 0,
                        'costo_total' => 0,
                        'tipo_animal' => $registro['dieta']['tipo_animal'] ?? 'N/A',
                        'categoria' => $registro['dieta']['categoria'] ?? 'N/A'
                    ];
                }
                $usoDietas[$dietaNombre]['total_registros']++;
                $usoDietas[$dietaNombre]['cantidad_total'] += $registro['cantidad_total'] ?? 0;
                $usoDietas[$dietaNombre]['costo_total'] += $registro['costo_total'] ?? 0;
            }
        }

        // Ordenar por cantidad de registros
        usort($usoDietas, function($a, $b) {
            return $b['total_registros'] - $a['total_registros'];
        });

        // Preparar datos para gráfica
        $grafica_dietas = [];
        foreach ($usoDietas as $dieta) {
            $grafica_dietas[$dieta['nombre']] = $dieta['total_registros'];
        }
        $grafica_dietas = array_slice($grafica_dietas, 0, 5, true);

        return [
            'grafica_dietas' => $grafica_dietas,
            'uso_dietas' => $usoDietas,
            'resumen_fincas' => [],
            'titulo_reporte' => 'Análisis de Uso de Dietas',
            'descripcion_reporte' => "Período: {$fechaInicio} al {$fechaFin} - Total dietas utilizadas: " . count($usoDietas)
        ];
    }

    /**
     * Reporte de Turnos
     */
    private function generarReporteTurnos($registros, $fechaInicio, $fechaFin)
    {
        // Detalle por turno
        $detalleTurnos = [
            'mañana' => ['registros' => 0, 'cantidad' => 0, 'costo' => 0],
            'tarde' => ['registros' => 0, 'cantidad' => 0, 'costo' => 0],
            'noche' => ['registros' => 0, 'cantidad' => 0, 'costo' => 0]
        ];

        foreach ($registros as $registro) {
            $turno = $registro['turno'];
            if (isset($detalleTurnos[$turno])) {
                $detalleTurnos[$turno]['registros']++;
                $detalleTurnos[$turno]['cantidad'] += $registro['cantidad_total'] ?? 0;
                $detalleTurnos[$turno]['costo'] += $registro['costo_total'] ?? 0;
            }
        }

        return [
            'grafica_dietas' => [],
            'detalle_turnos' => $detalleTurnos,
            'resumen_fincas' => [],
            'titulo_reporte' => 'Distribución por Turnos de Alimentación',
            'descripcion_reporte' => "Período: {$fechaInicio} al {$fechaFin}"
        ];
    }

    /**
     * Reporte por Fincas
     */
    private function generarReporteFincas($registros, $fechaInicio, $fechaFin)
    {
        // Resumen detallado por fincas
        $resumen_fincas = [];
        foreach ($registros as $registro) {
            if (isset($registro['finca']['nombre'])) {
                $fincaNombre = $registro['finca']['nombre'];
                if (!isset($resumen_fincas[$fincaNombre])) {
                    $resumen_fincas[$fincaNombre] = [
                        'nombre' => $fincaNombre,
                        'total_registros' => 0,
                        'cantidad_total' => 0,
                        'costo_total' => 0,
                        'costo_promedio_kg' => 0
                    ];
                }
                $resumen_fincas[$fincaNombre]['total_registros']++;
                $resumen_fincas[$fincaNombre]['cantidad_total'] += $registro['cantidad_total'] ?? 0;
                $resumen_fincas[$fincaNombre]['costo_total'] += $registro['costo_total'] ?? 0;
            }
        }

        // Calcular costo promedio por kg para cada finca
        foreach ($resumen_fincas as &$finca) {
            $finca['costo_promedio_kg'] = $finca['cantidad_total'] > 0 ? 
                $finca['costo_total'] / $finca['cantidad_total'] : 0;
        }

        $resumen_fincas = array_values($resumen_fincas);

        // Preparar datos para gráfica (top 5 fincas por costo)
        $grafica_dietas = [];
        foreach ($resumen_fincas as $finca) {
            $grafica_dietas[$finca['nombre']] = $finca['costo_total'];
        }
        arsort($grafica_dietas);
        $grafica_dietas = array_slice($grafica_dietas, 0, 5, true);

        return [
            'grafica_dietas' => $grafica_dietas,
            'resumen_fincas' => $resumen_fincas,
            'titulo_reporte' => 'Reporte de Alimentación por Fincas',
            'descripcion_reporte' => "Período: {$fechaInicio} al {$fechaFin} - Total fincas: " . count($resumen_fincas)
        ];
    }

    public function alertasStockBajo()
    {
        $response = $this->apiService->get('alimentos/stock/bajo');
        $alimentosStockBajo = $response->successful() ? $response->json() : [];

        return view('alimentacion.alertas', compact('alimentosStockBajo'));
    }
}