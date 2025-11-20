<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FincaController extends Controller
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
        Log::info('Obteniendo listado de fincas');
        $response = $this->apiService->get('fincas');
        
        $fincas = [];
        if ($response->successful()) {
            $fincas = $response->json();
        }

        return view('fincas.index', compact('fincas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('fincas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Creando nueva finca', $request->all());

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'responsable' => 'required|string|max:255',
            'zona' => 'required|in:norte,sur,este,oeste',
            'ip_subred' => 'nullable|string|max:18',
        ]);

        $response = $this->apiService->post('fincas', $validated);

        if ($response->successful()) {
            return redirect()->route('fincas.index')
                           ->with('success', 'Finca creada exitosamente');
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
        Log::info("Mostrando finca ID: {$id}");
        $response = $this->apiService->get("fincas/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('fincas.index')
                           ->with('error', 'Finca no encontrada');
        }

        $finca = $response->json();
        
        // Obtener animales de esta finca
        $animalesResponse = $this->apiService->get("animals/por-finca/{$id}");
        $animales = $animalesResponse->successful() ? $animalesResponse->json() : [];

        return view('fincas.show', compact('finca', 'animales'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info("Editando finca ID: {$id}");
        $response = $this->apiService->get("fincas/{$id}");

        if (!$response->successful()) {
            return redirect()->route('fincas.index')
                           ->with('error', 'Finca no encontrada');
        }

        $finca = $response->json();
        return view('fincas.edit', compact('finca'));
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
         Log::info("Actualizando finca ID: {$id}", $request->all());

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'telefono' => 'nullable|string|max:20',
            'responsable' => 'required|string|max:255',
            'zona' => 'required|in:norte,sur,este,oeste',
            'ip_subred' => 'nullable|string|max:18',
        ]);

        $response = $this->apiService->post("fincas/{$id}", array_merge($validated, ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('fincas.index')
                           ->with('success', 'Finca actualizada exitosamente');
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
        Log::info("Eliminando finca ID: {$id}");
        $response = $this->apiService->post("fincas/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('fincas.index')
                           ->with('success', 'Finca eliminada exitosamente');
        }

        return back()->with('error', 'Error al eliminar la finca');
    }

    private function handleApiError($response, $action)
    {
        $errorMessage = "Error al {$action} la finca";
        
        if ($response->status() === 422) {
            $errors = $response->json('errors');
            if ($errors) {
                $errorMessage = 'Errores de validación: ' . implode(', ', array_flatten($errors));
            }
        } elseif ($response->status() === 404) {
            $errorMessage = 'Finca no encontrada';
        } elseif ($response->status() === 500) {
            $errorMessage = 'Error del servidor';
        }

        return back()->with('error', $errorMessage)->withInput();
    }
}
