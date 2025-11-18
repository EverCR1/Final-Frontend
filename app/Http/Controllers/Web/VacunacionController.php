<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class VacunacionController extends Controller
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
        Log::info('Obteniendo listado de vacunaciones');
        
        // Verificar permisos - Solo admin y veterinario
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('dashboard')
                           ->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $response = $this->apiService->get('vacunaciones');
        
        $vacunaciones = [];
        if ($response->successful()) {
            $vacunaciones = $response->json();
            
        }

        return view('vacunaciones.index', compact('vacunaciones'));
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
            return redirect()->route('vacunaciones.index')
                        ->with('error', 'No tienes permisos para crear vacunaciones');
        }

        // Obtener animales, medicamentos y veterinarios
        $animalesResponse = $this->apiService->get('animals');
        $medicamentosResponse = $this->apiService->get('medicamentos');
        $veterinariosResponse = $this->apiService->get('veterinarios'); // Nuevo endpoint
        
        $animales = $animalesResponse->successful() ? $animalesResponse->json() : [];
        $medicamentos = $medicamentosResponse->successful() ? $medicamentosResponse->json() : [];
        $veterinarios = $veterinariosResponse->successful() ? $veterinariosResponse->json() : [];

        return view('vacunaciones.create', compact('animales', 'medicamentos', 'veterinarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Creando nueva vacunación', $request->all());

        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('vacunaciones.index')
                           ->with('error', 'No tienes permisos para crear vacunaciones');
        }

        $validated = $request->validate([
            'animal_id' => 'required|integer|min:1',
            'medicamento_id' => 'required|integer|min:1',
            'fecha_vacunacion' => 'required|date',
            'vacuna' => 'required|string|max:255',
            'lote' => 'required|string|max:255',
            'fecha_proxima' => 'nullable|date|after:fecha_vacunacion',
            'observaciones' => 'nullable|string',
            'veterinario' => 'required|string|max:255',
            'dosis' => 'required|numeric|min:0.01'
        ]);

        $response = $this->apiService->post('vacunaciones', $validated);

        if ($response->successful()) {
            return redirect()->route('vacunaciones.index')
                           ->with('success', 'Vacunación registrada exitosamente');
        }

        // Manejar error de stock insuficiente
        if ($response->status() === 400) {
            $errorData = $response->json();
            if (isset($errorData['error']) && str_contains($errorData['error'], 'Stock insuficiente')) {
                return back()->with('error', 'Stock insuficiente del medicamento seleccionado')->withInput();
            }
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
        Log::info("Mostrando vacunación ID: {$id}");
        
        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('dashboard')
                           ->with('error', 'No tienes permisos para acceder a esta sección');
        }

        $response = $this->apiService->get("vacunaciones/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('vacunaciones.index')
                           ->with('error', 'Vacunación no encontrada');
        }

        $vacunacion = $response->json();
        // La API ya incluye: animal, animal.finca, medicamento

        return view('vacunaciones.show', compact('vacunacion'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info("Editando vacunación ID: {$id}");

        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('vacunaciones.index')
                        ->with('error', 'No tienes permisos para editar vacunaciones');
        }

        $response = $this->apiService->get("vacunaciones/{$id}");

        if (!$response->successful()) {
            return redirect()->route('vacunaciones.index')
                        ->with('error', 'Vacunación no encontrada');
        }

        $vacunacion = $response->json();
        
        // Obtener animales, medicamentos y veterinarios
        $animalesResponse = $this->apiService->get('animals');
        $medicamentosResponse = $this->apiService->get('medicamentos');
        $veterinariosResponse = $this->apiService->get('veterinarios'); 
        
        $animales = $animalesResponse->successful() ? $animalesResponse->json() : [];
        $medicamentos = $medicamentosResponse->successful() ? $medicamentosResponse->json() : [];
        $veterinarios = $veterinariosResponse->successful() ? $veterinariosResponse->json() : [];

        return view('vacunaciones.edit', compact('vacunacion', 'animales', 'medicamentos', 'veterinarios'));
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
        Log::info("Actualizando vacunación ID: {$id}", $request->all());

        // Verificar permisos
        if (!in_array(session('user.role'), ['admin', 'veterinario'])) {
            return redirect()->route('vacunaciones.index')
                           ->with('error', 'No tienes permisos para editar vacunaciones');
        }

        $validated = $request->validate([
            'animal_id' => 'required|integer|min:1',
            'medicamento_id' => 'required|integer|min:1',
            'fecha_vacunacion' => 'required|date',
            'vacuna' => 'required|string|max:255',
            'lote' => 'required|string|max:255',
            'fecha_proxima' => 'nullable|date|after:fecha_vacunacion',
            'observaciones' => 'nullable|string',
            'veterinario' => 'required|string|max:255',
            'dosis' => 'required|numeric|min:0.01'
        ]);

        $response = $this->apiService->post("vacunaciones/{$id}", array_merge($validated, ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('vacunaciones.index')
                           ->with('success', 'Vacunación actualizada exitosamente');
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
        Log::info("Eliminando vacunación ID: {$id}");

        // Verificar permisos - Solo admin
        if (session('user.role') !== 'admin') {
            return redirect()->route('vacunaciones.index')
                           ->with('error', 'Solo el administrador puede eliminar vacunaciones');
        }

        $response = $this->apiService->post("vacunaciones/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('vacunaciones.index')
                           ->with('success', 'Vacunación eliminada exitosamente');
        }

        return back()->with('error', 'Error al eliminar la vacunación');
    }

    private function handleApiError($response, $action)
    {
        $errorMessage = "Error al {$action} la vacunación";
        
        if ($response->status() === 422) {
            $errors = $response->json('errors');
            if ($errors) {
                $errorMessage = 'Errores de validación: ' . implode(', ', array_flatten($errors));
            }
        } elseif ($response->status() === 404) {
            $errorMessage = 'Vacunación no encontrada';
        } elseif ($response->status() === 500) {
            $errorMessage = 'Error del servidor';
        }

        return back()->with('error', $errorMessage)->withInput();
    }
}
