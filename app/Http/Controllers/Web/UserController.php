<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    
    protected $apiService;

    public function __construct(ApiService $apiService)
    {
        $this->apiService = $apiService;
        // Aplicar middleware de rol admin a todos los métodos
        $this->middleware('role:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        Log::info('Obteniendo listado de usuarios');
        $response = $this->apiService->get('users');
        
        $users = [];
        if ($response->successful()) {
            $users = $response->json();
        }

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = [
            'admin' => 'Administrador',
            'veterinario' => 'Veterinario',
            'productor' => 'Productor'
        ];

        return view('users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        Log::info('Creando nuevo usuario', $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,veterinario,productor',
        ]);

        $response = $this->apiService->post('users', $validated);

        if ($response->successful()) {
            return redirect()->route('users.index')
                           ->with('success', 'Usuario creado exitosamente');
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
        Log::info("Mostrando usuario ID: {$id}");
        $response = $this->apiService->get("users/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('users.index')
                           ->with('error', 'Usuario no encontrado');
        }

        $user = $response->json();
        
        // Obtener estadísticas adicionales si es necesario
        $estadisticasResponse = $this->apiService->get('users/estadisticas');
        $estadisticas = $estadisticasResponse->successful() ? $estadisticasResponse->json() : [];

        return view('users.show', compact('user', 'estadisticas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        Log::info("Editando usuario ID: {$id}");
        $response = $this->apiService->get("users/{$id}");

        if (!$response->successful()) {
            return redirect()->route('users.index')
                           ->with('error', 'Usuario no encontrado');
        }

        $user = $response->json();
        $roles = [
            'admin' => 'Administrador',
            'veterinario' => 'Veterinario',
            'productor' => 'Productor'
        ];

        return view('users.edit', compact('user', 'roles'));
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
        Log::info("Actualizando usuario ID: {$id}", $request->all());

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'role' => 'required|in:admin,veterinario,productor',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Si no se proporciona contraseña, eliminarla del array
        if (empty($validated['password'])) {
            unset($validated['password']);
            unset($validated['password_confirmation']);
        }

        $response = $this->apiService->post("users/{$id}", array_merge($validated, ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('users.index')
                           ->with('success', 'Usuario actualizado exitosamente');
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
        Log::info("Eliminando usuario ID: {$id}");
        $response = $this->apiService->post("users/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('users.index')
                           ->with('success', 'Usuario eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el usuario');
    }

    public function estadisticas()
    {
        Log::info('Obteniendo estadísticas de usuarios');
        $response = $this->apiService->get('users/estadisticas');
        
        $estadisticas = [];
        if ($response->successful()) {
            $estadisticas = $response->json();
        }

        return view('users.estadisticas', compact('estadisticas'));
    }

    /**
     * Display list of veterinarians.
     *
     * @return \Illuminate\Http\Response
     */
    public function veterinarios()
    {
        Log::info('Obteniendo listado de veterinarios');
        $response = $this->apiService->get('veterinarios');
        
        $veterinarios = [];
        if ($response->successful()) {
            $veterinarios = $response->json();
        }

        return view('users.veterinarios', compact('veterinarios'));
    }

    /**
     * Display list of producers.
     *
     * @return \Illuminate\Http\Response
     */
    public function productores()
    {
        Log::info('Obteniendo listado de productores');
        $response = $this->apiService->get('users/productores');
        
        $productores = [];
        if ($response->successful()) {
            $productores = $response->json();
        }

        return view('users.productores', compact('productores'));
    }

    private function handleApiError($response, $action)
    {
        $errorMessage = "Error al {$action} el usuario";
        
        if ($response->status() === 422) {
            $errors = $response->json('errors');
            if ($errors) {
                $errorMessage = 'Errores de validación: ' . implode(', ', array_flatten($errors));
            }
        } elseif ($response->status() === 404) {
            $errorMessage = 'Usuario no encontrado';
        } elseif ($response->status() === 500) {
            $errorMessage = 'Error del servidor';
        } elseif ($response->status() === 409) {
            $errorMessage = 'El email ya está registrado';
        }

        return back()->with('error', $errorMessage)->withInput();
    }
}
