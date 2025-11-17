<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\ApiService;
use Illuminate\Http\Request;

class AnimalController extends Controller
{
    
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
         $response = $this->apiService->get('animals');
        
        $animals = [];
        if ($response->successful()) {
            $animals = $response->json();
        }

        return view('animals.index', compact('animals'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         // Obtener fincas para el select
        $fincasResponse = $this->apiService->get('fincas');
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('animals.create', compact('fincas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $request->validate([
           'finca_id' => 'required|numeric|min:1',
            'identificacion' => 'required|string|max:255',
            'nombre' => 'nullable|string',
            'especie' => 'required|in:bovino,porcino,caprino,ovina',
            'raza' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:macho,hembra',
            'peso_inicial' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
        ]);

        $response = $this->apiService->post('animals', $request->all());

        if ($response->successful()) {
            return redirect()->route('animals.index')
                           ->with('success', 'Animal creado exitosamente');
        }

        return back()->with('error', 'Error al crear el animal')->withInput();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $response = $this->apiService->get("animals/{$id}");
        
        if (!$response->successful()) {
            return redirect()->route('animals.index')
                           ->with('error', 'Animal no encontrado');
        }

        $animal = $response->json();
        return view('animals.show', compact('animal'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
          $animalResponse = $this->apiService->get("animals/{$id}");
        $fincasResponse = $this->apiService->get('fincas');

        if (!$animalResponse->successful()) {
            return redirect()->route('animals.index')
                           ->with('error', 'Animal no encontrado');
        }

        $animal = $animalResponse->json();
        $fincas = $fincasResponse->successful() ? $fincasResponse->json() : [];

        return view('animals.edit', compact('animal', 'fincas'));
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
        $request->validate([
           'finca_id' => 'required|numeric|min:1',
            'identificacion' => 'required|string|max:255',
            'nombre' => 'nullable|string',
            'especie' => 'required|in:bovino,porcino,caprino,ovina',
            'raza' => 'required|string',
            'fecha_nacimiento' => 'required|date',
            'sexo' => 'required|in:macho,hembra',
            'estado' => 'required|in:activo,vendido,muerto,enfermo',
            'peso_inicial' => 'nullable|numeric',
            'observaciones' => 'nullable|string',
        ]);

        $response = $this->apiService->post("animals/{$id}", array_merge($request->all(), ['_method' => 'PUT']));

        if ($response->successful()) {
            return redirect()->route('animals.index')
                           ->with('success', 'Animal actualizado exitosamente');
        }

        return back()->with('error', 'Error al actualizar el animal')->withInput();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         $response = $this->apiService->post("animals/{$id}", ['_method' => 'DELETE']);

        if ($response->successful()) {
            return redirect()->route('animals.index')
                           ->with('success', 'Animal eliminado exitosamente');
        }

        return back()->with('error', 'Error al eliminar el animal');
    }
}
