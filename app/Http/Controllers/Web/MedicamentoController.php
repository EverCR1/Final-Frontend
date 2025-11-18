<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MedicamentoController extends Controller
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
        Log::info('Obteniendo listado de medicamentos');
    
    // Verificar permisos - Solo admin y veterinario
    if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
        return redirect()->route('dashboard')
                       ->with('error', 'No tienes permisos para acceder a esta sección');
    }

    $response = $this->apiService->get('medicamentos');
    
    $medicamentos = [];
    if ($response->successful()) {
        $medicamentos = $response->json();
        
        // Obtener información de fincas para mostrar nombres
        $fincasResponse = $this->apiService->get('fincas');
        if ($fincasResponse->successful()) {
            $fincas = $fincasResponse->json();
            $fincasMap = collect($fincas)->keyBy('id');
            
            // Agregar nombre de finca a cada medicamento
            foreach ($medicamentos as &$medicamento) {
                $medicamento['finca_nombre'] = $fincasMap[$medicamento['finca_id']]['nombre'] ?? 'N/A';
            }
        }
    }

    return view('medicamentos.index', compact('medicamentos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('medicamentos.index')
                           ->with('error', 'No tienes permisos para crear medicamentos');
        }

        // Obtener fincas para el select
        $fincasResponse = $this->apiService->get('fincas');
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('medicamentos.create', compact('fincas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Creando nuevo medicamento', $request->all());

        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('medicamentos.index')
                           ->with('error', 'No tienes permisos para crear medicamentos');
        }

        $validated = $request->validate([
            'finca_id' => 'required|integer|min:1',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:vacuna,antibiotico,vitaminas,desparasitante,otro',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'nullable|numeric|min:0',
            'fecha_vencimiento' => 'nullable|date|after:today',
            'proveedor' => 'nullable|string|max:255'
        ]);

        $response = $this->apiService->post('medicamentos', $validated);

        if ($response->successful()) {
            return redirect()->route('medicamentos.index')
                           ->with('success', 'Medicamento creado exitosamente');
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
        Log::info("Mostrando medicamento ID: {$id}");
        
        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('dashboard')
                           ->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $response = $this->apiService->get("medicamentos/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('medicamentos.index')
                           ->with('error', 'Medicamento no encontrado');
        }

        $medicamento = $response->json();
        
        // Obtener historial de vacunaciones con este medicamento
        $vacunacionesResponse = $this->apiService->get("vacunaciones/por-medicamento/{$id}");
        $vacunaciones = $vacunacionesResponse->successful() ? $vacunacionesResponse->json() : [];

        return view('medicamentos.show', compact('medicamento', 'vacunaciones'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info("Editando medicamento ID: {$id}");

        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('medicamentos.index')
                           ->with('error', 'No tienes permisos para editar medicamentos');
        }

        $response = $this->apiService->get("medicamentos/{$id}");

        if (!$response->successful()) {
            return redirect()->route('medicamentos.index')
                           ->with('error', 'Medicamento no encontrado');
        }

        $medicamento = $response->json();
        
        // Obtener fincas para el select
        $fincasResponse = $this->apiService->get('fincas');
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('medicamentos.edit', compact('medicamento', 'fincas'));
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
        Log::info("Actualizando medicamento ID: {$id}", $request->all());

        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('medicamentos.index')
                           ->with('error', 'No tienes permisos para editar medicamentos');
        }

        $validated = $request->validate([
            'finca_id' => 'required|integer|min:1',
            'nombre' => 'required|string|max:255',
            'tipo' => 'required|in:vacuna,antibiotico,vitaminas,desparasitante,otro',
            'descripcion' => 'nullable|string',
            'stock_actual' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
            'precio_unitario' => 'nullable|numeric|min:0',
            'fecha_vencimiento' => 'nullable|date|after:today',
            'proveedor' => 'nullable|string|max:255'
        ]);

        $response = $this->apiService->post("medicamentos/{$id}", array_merge($validated, ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('medicamentos.index')
                           ->with('success', 'Medicamento actualizado exitosamente');
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
        Log::info("Eliminando medicamento ID: {$id}");

        // Verificar permisos - Solo admin
        if (session('user.role') !== 'admin') {
            return redirect()->route('medicamentos.index')
                           ->with('error', 'Solo el administrador puede eliminar medicamentos');
        }

        $response = $this->apiService->post("medicamentos/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('medicamentos.index')
                           ->with('success', 'Medicamento eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el medicamento');
    }

    private function handleApiError($response, $action)
    {
        $errorMessage = "Error al {$action} el medicamento";
        
        if ($response->status() === 422) {
            $errors = $response->json('errors');
            if ($errors) {
                $errorMessage = 'Errores de validación: ' . implode(', ', array_flatten($errors));
            }
        } elseif ($response->status() === 404) {
            $errorMessage = 'Medicamento no encontrado';
        } elseif ($response->status() === 500) {
            $errorMessage = 'Error del servidor';
        }

        return back()->with('error', $errorMessage)->withInput();
    }
}
