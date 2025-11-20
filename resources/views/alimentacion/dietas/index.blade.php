@extends('layouts.app')

@section('title', 'Gestión de Dietas')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-utensils text-success"></i> Gestión de Dietas
        </h1>
        
        <!-- Botón Nueva Dieta - Solo Admin y Veterinario -->
        @if(in_array(session('user.role'), ['admin', 'veterinario']))
        <a href="{{ route('alimentacion.dietas.create') }}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nueva Dieta</span>
        </a>
        @endif
    </div>

    <!-- Información de permisos para Productor -->
    @if(session('user.role') === 'productor')
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Modo Consulta:</strong> Como productor, tienes acceso de solo lectura a la información de dietas.
    </div>
    @endif

    <!-- Búsqueda y Filtros -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, descripción...">
            </div>
        </div>
        <div class="col-md-2">
            <select id="filterTipoAnimal" class="form-select">
                <option value="">Todos los tipos</option>
                <option value="bovino">Bovino</option>
                <option value="porcino">Porcino</option>
                <option value="caprino">Caprino</option>
                <option value="ovina">Ovina</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterCategoria" class="form-select">
                <option value="">Todas las categorías</option>
                <option value="ternero">Ternero</option>
                <option value="desarrollo">Desarrollo</option>
                <option value="adulto">Adulto</option>
                <option value="lactancia">Lactancia</option>
                <option value="gestacion">Gestación</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterEstado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="activa">Activas</option>
                <option value="inactiva">Inactivas</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="clearFilters" class="btn btn-outline-secondary w-100">
                <i class="fas fa-times"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Dietas Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-list me-2"></i>Listado de Dietas
            </h6>
            <span class="badge bg-success" id="dietasCount">{{ count($dietas) }} dietas</span>
        </div>
        <div class="card-body">
            @if(count($dietas) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="dietasTable" width="100%" cellspacing="0">
                    <thead class="table-success">
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Tipo Animal</th>
                            <th>Categoría</th>
                            <th>Alimentos</th>
                            <th>Costo Estimado</th>
                            <th>Estado</th>
                            <th>Finca</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dietas as $index => $dieta)
                        @php
                            // Iconos y colores para tipos de animal
                            $tiposAnimal = [
                                'bovino' => ['fa-cow', 'primary', 'Bovino'],
                                'porcino' => ['fa-piggy', 'pink', 'Porcino'],
                                'caprino' => ['fa-goat', 'warning', 'Caprino'],
                                'ovina' => ['fa-sheep', 'light', 'Ovina']
                            ];
                            list($animalIcono, $animalColor, $animalTexto) = $tiposAnimal[$dieta['tipo_animal']] ?? ['fa-paw', 'secondary', $dieta['tipo_animal']];

                            // Colores para categorías
                            $categoriaColors = [
                                'ternero' => 'info',
                                'desarrollo' => 'warning',
                                'adulto' => 'success',
                                'lactancia' => 'primary',
                                'gestacion' => 'danger'
                            ];
                        @endphp
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-utensils text-success me-2"></i>
                                    <div>
                                        <strong>{{ $dieta['nombre'] }}</strong>
                                        @if($dieta['descripcion'])
                                        <br><small class="text-muted">{{ Str::limit($dieta['descripcion'], 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $animalColor }} text-capitalize">
                                    <i class="fas {{ $animalIcono }} me-1"></i>{{ $animalTexto }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $categoriaColors[$dieta['categoria']] ?? 'secondary' }} text-capitalize">
                                    {{ $dieta['categoria'] }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-apple-alt me-1"></i>
                                    {{ count($dieta['alimentos'] ?? []) }} alimentos
                                </small>
                                @if(isset($dieta['alimentos']) && count($dieta['alimentos']) > 0)
                                <div class="mt-1">
                                    @foreach(array_slice($dieta['alimentos'], 0, 2) as $alimento)
                                    <span class="badge bg-light text-dark small me-1">
                                        {{ $alimento['nombre'] }}
                                    </span>
                                    @endforeach
                                    @if(count($dieta['alimentos']) > 2)
                                    <span class="badge bg-secondary small">+{{ count($dieta['alimentos']) - 2 }}</span>
                                    @endif
                                </div>
                                @endif
                            </td>
                            <td>
                                @if($dieta['costo_estimado_kg'])
                                <span class="text-success fw-bold">
                                    Q{{ number_format($dieta['costo_estimado_kg'], 2) }}/kg
                                </span>
                                @else
                                <span class="text-muted">No calculado</span>
                                @endif
                            </td>
                            <td>
                                @if($dieta['activa'])
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Activa
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times-circle me-1"></i>Inactiva
                                </span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-tractor me-1"></i>
                                    {{ $dieta['finca']['nombre'] ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Ver - Todos los roles -->
                                    <a href="{{ route('alimentacion.dietas.show', $dieta['id']) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Editar - Solo Admin y Veterinario -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <a href="{{ route('alimentacion.dietas.edit', $dieta['id']) }}" 
                                       class="btn btn-warning btn-sm"
                                       title="Editar"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    
                                    <!-- Eliminar - Solo Admin y Veterinario -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <form action="{{ route('alimentacion.dietas.destroy', $dieta['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar la dieta {{ $dieta['nombre'] }}?')">
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
                    <i class="fas fa-utensils fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay dietas registradas</h4>
                    <p class="text-muted">Comienza creando la primera dieta para el ganado</p>
                </div>
                <!-- Botón solo para Admin y Veterinario -->
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('alimentacion.dietas.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>Crear Primera Dieta
                </a>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Contacta al administrador para crear dietas en el sistema.
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    @if(count($dietas) > 0)
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Dietas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($dietas) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-utensils fa-2x text-gray-300"></i>
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
                                Dietas Activas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($dietas)->where('activa', true)->count() }}
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Para Bovinos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($dietas)->where('tipo_animal', 'bovino')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cow fa-2x text-gray-300"></i>
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
                                En Lactancia
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($dietas)->where('categoria', 'lactancia')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wine-bottle fa-2x text-gray-300"></i>
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
    .table-hover tbody tr:hover { 
        background-color: rgba(40, 167, 69, 0.1) !important; 
        cursor: pointer;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .bg-pink { 
        background-color: #e83e8c !important; 
        color: white !important; 
    }
    .border-left-pink { 
        border-left: 0.25rem solid #e83e8c !important; 
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterTipoAnimal = document.getElementById('filterTipoAnimal');
        const filterCategoria = document.getElementById('filterCategoria');
        const filterEstado = document.getElementById('filterEstado');
        const clearFilters = document.getElementById('clearFilters');
        const dietasCount = document.getElementById('dietasCount');
        const table = document.getElementById('dietasTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        // Elementos para estadísticas
        const totalCount = document.querySelector('.card-border-left-success .h5');
        const activasCount = document.querySelector('.card-border-left-primary .h5');
        const bovinosCount = document.querySelector('.card-border-left-info .h5');
        const lactanciaCount = document.querySelector('.card-border-left-warning .h5');

        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const tipoAnimalValue = filterTipoAnimal.value;
            const categoriaValue = filterCategoria.value;
            const estadoValue = filterEstado.value;
            
            let visibleCount = 0;
            let activas = 0, bovinos = 0, lactancia = 0;

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                const nombre = cells[1].textContent.toLowerCase();
                const tipoAnimal = cells[2].textContent.toLowerCase();
                const categoria = cells[3].textContent.toLowerCase();
                const estado = cells[6].querySelector('.badge').textContent.toLowerCase().trim();

                const matchesSearch = !searchText || 
                    nombre.includes(searchText);

                const matchesTipoAnimal = !tipoAnimalValue || tipoAnimal.includes(tipoAnimalValue);
                const matchesCategoria = !categoriaValue || categoria.includes(categoriaValue);
                
                let matchesEstado = true;
                if (estadoValue === 'activa') {
                    matchesEstado = estado.includes('activa');
                } else if (estadoValue === 'inactiva') {
                    matchesEstado = estado.includes('inactiva');
                }

                const isVisible = matchesSearch && matchesTipoAnimal && matchesCategoria && matchesEstado;
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                    // Contar para estadísticas
                    if (estado.includes('activa')) activas++;
                    if (tipoAnimal.includes('bovino')) bovinos++;
                    if (categoria.includes('lactancia')) lactancia++;
                }
            }

            // Actualizar contador principal
            const total = rows.length;
            if (visibleCount === total) {
                dietasCount.textContent = total + ' dietas';
                dietasCount.className = 'badge bg-success';
            } else {
                dietasCount.textContent = visibleCount + ' de ' + total + ' dietas';
                dietasCount.className = 'badge bg-info';
            }

            // Actualizar estadísticas
            if (totalCount) totalCount.textContent = visibleCount;
            if (activasCount) activasCount.textContent = activas;
            if (bovinosCount) bovinosCount.textContent = bovinos;
            if (lactanciaCount) lactanciaCount.textContent = lactancia;
        }

        // Event listeners
        searchInput.addEventListener('keyup', filterTable);
        filterTipoAnimal.addEventListener('change', filterTable);
        filterCategoria.addEventListener('change', filterTable);
        filterEstado.addEventListener('change', filterTable);

        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            filterTipoAnimal.value = '';
            filterCategoria.value = '';
            filterEstado.value = '';
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