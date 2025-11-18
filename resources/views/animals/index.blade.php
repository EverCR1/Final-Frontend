@extends('layouts.app')

@section('title', 'Gestión de Animales')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-horse text-success"></i> Gestión de Animales
        </h1>
        
        <!-- Botón Nuevo Animal - Solo Admin y Veterinario -->
        @if(in_array(session('user.role'), ['admin', 'veterinario']))
        <a href="{{ route('animals.create') }}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nuevo Animal</span>
        </a>
        @endif
    </div>


    <!-- Información de permisos para Productor -->
    @if(session('user.role') === 'productor')
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Modo Consulta:</strong> Como productor, tienes acceso de solo lectura a la información de animales.
    </div>
    @endif

    <!-- Búsqueda y Filtros -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por identificación, nombre, finca...">
            </div>
        </div>
        <div class="col-md-2">
            <select id="filterEstado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="activo">Activo</option>
                <option value="enfermo">Enfermo</option>
                <option value="vendido">Vendido</option>
                <option value="muerto">Muerto</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterEspecie" class="form-select">
                <option value="">Todas las especies</option>
                <option value="bovino">Bovino</option>
                <option value="porcino">Porcino</option>
                <option value="caprino">Caprino</option>
                <option value="ovina">Ovina</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterSexo" class="form-select">
                <option value="">Todos</option>
                <option value="macho">Machos</option>
                <option value="hembra">Hembras</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="clearFilters" class="btn btn-outline-secondary w-100">
                <i class="fas fa-times"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Animals Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-list me-2"></i>Listado de Animales
            </h6>
            <span class="badge bg-success" id="animalesCount">{{ count($animals) }} animales</span>
        </div>
        <div class="card-body">
            @if(count($animals) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="animalsTable" width="100%" cellspacing="0">
                    <thead class="table-success">
                        <tr>
                            <th>No.</th>
                            <th>Identificación</th>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Raza</th>
                            <th>Sexo</th>
                            <th>Estado</th>
                            <th>Finca</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($animals as $index => $animal)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $animal['identificacion'] }}</span>
                            </td>
                            <td>
                                @if($animal['nombre'])
                                <i class="fas fa-tag me-1 text-muted"></i>{{ $animal['nombre'] }}
                                @else
                                <span class="text-muted">Sin nombre</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $especies = [
                                        'bovino' => ['fa-cow', 'primary', 'Bovino'],
                                        'porcino' => ['fa-piggy', 'pink', 'Porcino'],
                                        'caprino' => ['fa-goat', 'warning', 'Caprino'],
                                        'ovina' => ['fa-sheep', 'light', 'Ovina']
                                    ];
                                    list($icono, $color, $texto) = $especies[$animal['especie']] ?? ['fa-paw', 'secondary', $animal['especie']];
                                @endphp
                                <span class="badge bg-{{ $color }} text-dark text-capitalize">
                                    <i class="fas {{ $icono }} me-1"></i>{{ $texto }}
                                </span>
                            </td>
                            <td>{{ $animal['raza'] }}</td>
                            <td>
                                @if($animal['sexo'] == 'macho')
                                <span class="badge bg-primary">
                                    <i class="fas fa-mars me-1"></i>Macho
                                </span>
                                @else
                                <span class="badge bg-pink text-white">
                                    <i class="fas fa-venus me-1"></i>Hembra
                                </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'activo' => 'bg-success',
                                        'enfermo' => 'bg-warning text-dark',
                                        'vendido' => 'bg-secondary',
                                        'muerto' => 'bg-danger'
                                    ][$animal['estado']] ?? 'bg-secondary';
                                    
                                    $icon = [
                                        'activo' => 'fa-check-circle',
                                        'enfermo' => 'fa-first-aid',
                                        'vendido' => 'fa-dollar-sign',
                                        'muerto' => 'fa-skull'
                                    ][$animal['estado']] ?? 'fa-question';
                                @endphp
                                <span class="badge {{ $badgeClass }} text-capitalize">
                                    <i class="fas {{ $icon }} me-1"></i>{{ $animal['estado'] }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-tractor me-1"></i>
                                    {{ $animal['finca']['nombre'] ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Ver - Todos los roles -->
                                    <a href="{{ route('animals.show', $animal['id']) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Editar - Solo Admin y Veterinario -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <a href="{{ route('animals.edit', $animal['id']) }}" 
                                       class="btn btn-warning btn-sm"
                                       title="Editar"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    
                                    <!-- Eliminar - Solo Admin -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <form action="{{ route('animals.destroy', $animal['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar el animal {{ $animal['identificacion'] }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                title="Eliminar"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-cow fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay animales registrados</h4>
                    <p class="text-muted">Comienza agregando el primer animal al sistema</p>
                </div>
                <!-- Botón solo para Admin y Veterinario -->
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('animals.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primer Animal
                </a>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Contacta al administrador para registrar animales en el sistema.
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    @if(count($animals) > 0)
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Animales Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('estado', 'activo')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Animales Enfermos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('estado', 'enfermo')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-first-aid fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Machos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('sexo', 'macho')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mars fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-pink shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-pink text-uppercase mb-1">
                                Hembras
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('sexo', 'hembra')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-venus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .bg-pink { 
        background-color: #e83e8c !important; 
        color: white !important; 
    }
    .border-left-pink { 
        border-left: 0.25rem solid #e83e8c !important; 
    }
    .text-pink {
        color: #e83e8c !important;
    }
    .table-hover tbody tr:hover { 
        background-color: rgba(40, 167, 69, 0.1) !important; 
        cursor: pointer;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterEstado = document.getElementById('filterEstado');
        const filterEspecie = document.getElementById('filterEspecie');
        const filterSexo = document.getElementById('filterSexo');
        const clearFilters = document.getElementById('clearFilters');
        const animalesCount = document.getElementById('animalesCount');
        const table = document.getElementById('animalsTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        // Elementos para estadísticas
        const activosCount = document.querySelector('.card-border-left-success .h5');
        const enfermosCount = document.querySelector('.card-border-left-warning .h5');
        const machosCount = document.querySelector('.card-border-left-primary .h5');
        const hembrasCount = document.querySelector('.card-border-left-pink .h5');

        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const estadoValue = filterEstado.value;
            const especieValue = filterEspecie.value;
            const sexoValue = filterSexo.value;
            
            let visibleCount = 0;
            let activos = 0, enfermos = 0, machos = 0, hembras = 0;

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                const identificacion = cells[1].textContent.toLowerCase();
                const nombre = cells[2].textContent.toLowerCase();
                const especie = cells[3].textContent.toLowerCase();
                const finca = cells[7].textContent.toLowerCase();
                const estado = cells[6].querySelector('.badge').textContent.toLowerCase().trim();
                const sexo = cells[5].querySelector('.badge').textContent.toLowerCase().trim();

                const matchesSearch = !searchText || 
                    identificacion.includes(searchText) || 
                    nombre.includes(searchText) ||
                    especie.includes(searchText) ||
                    finca.includes(searchText);

                const matchesEstado = !estadoValue || estado === estadoValue;
                const matchesEspecie = !especieValue || especie.includes(especieValue);
                const matchesSexo = !sexoValue || sexo === sexoValue;

                const isVisible = matchesSearch && matchesEstado && matchesEspecie && matchesSexo;
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                    // Contar para estadísticas
                    if (estado === 'activo') activos++;
                    if (estado === 'enfermo') enfermos++;
                    if (sexo === 'macho') machos++;
                    if (sexo === 'hembra') hembras++;
                }
            }

            // Actualizar contador principal
            const total = rows.length;
            if (visibleCount === total) {
                animalesCount.textContent = total + ' animales';
                animalesCount.className = 'badge bg-success';
            } else {
                animalesCount.textContent = visibleCount + ' de ' + total + ' animales';
                animalesCount.className = 'badge bg-info';
            }

            // Actualizar estadísticas
            if (activosCount) activosCount.textContent = activos;
            if (enfermosCount) enfermosCount.textContent = enfermos;
            if (machosCount) machosCount.textContent = machos;
            if (hembrasCount) hembrasCount.textContent = hembras;
        }

        // Event listeners
        searchInput.addEventListener('keyup', filterTable);
        filterEstado.addEventListener('change', filterTable);
        filterEspecie.addEventListener('change', filterTable);
        filterSexo.addEventListener('change', filterTable);

        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            filterEstado.value = '';
            filterEspecie.value = '';
            filterSexo.value = '';
            filterTable();
        });

        // Inicializar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection