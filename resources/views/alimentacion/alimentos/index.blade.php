@extends('layouts.app')

@section('title', 'Gestión de Alimentos')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-apple-alt text-warning"></i> Gestión de Alimentos
        </h1>
        
        <!-- Botón Nuevo Alimento - Solo Admin y Veterinario -->
        @if(in_array(session('user.role'), ['admin', 'veterinario']))
        <a href="{{ route('alimentacion.alimentos.create') }}" class="btn btn-warning btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nuevo Alimento</span>
        </a>
        @endif
    </div>

    <!-- Información de permisos para Productor -->
    @if(session('user.role') === 'productor')
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Modo Consulta:</strong> Como productor, tienes acceso de solo lectura a la información de alimentos.
    </div>
    @endif

    <!-- Búsqueda y Filtros -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, tipo, proveedor...">
            </div>
        </div>
        <div class="col-md-2">
            <select id="filterFinca" class="form-select">
                <option value="">Todas las fincas</option>
                @foreach($fincas ?? [] as $finca)
                <option value="{{ $finca['nombre'] }}">{{ $finca['nombre'] }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterTipo" class="form-select">
                <option value="">Todos los tipos</option>
                <option value="concentrado">Concentrado</option>
                <option value="forraje">Forraje</option>
                <option value="suplemento">Suplemento</option>
                <option value="mineral">Mineral</option>
                <option value="otro">Otro</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterStock" class="form-select">
                <option value="">Todo el stock</option>
                <option value="bajo">Stock Bajo</option>
                <option value="normal">Stock Normal</option>
                <option value="optimo">Stock Óptimo</option>
            </select>
        </div>

        <div class="col-md-2">
            <button id="clearFilters" class="btn btn-outline-secondary w-100">
                <i class="fas fa-times"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Alimentos Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-list me-2"></i>Inventario de Alimentos
            </h6>
            <span class="badge bg-warning" id="alimentosCount">{{ count($alimentos) }} alimentos</span>
        </div>
        <div class="card-body">
            @if(count($alimentos) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="alimentosTable" width="100%" cellspacing="0">
                    <thead class="table-warning">
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Stock</th>
                            <th>Unidad</th>
                            <th>Precio Unitario</th>
                            <th>Finca</th>
                            <th>Estado Stock</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($alimentos as $index => $alimento)
                        @php
                            // Calcular porcentaje de stock
                            $porcentajeStock = $alimento['stock_minimo'] > 0 ? 
                                ($alimento['stock_actual'] / $alimento['stock_minimo']) * 100 : 0;
                            
                            // Determinar clase del badge según el stock
                            if ($alimento['stock_actual'] <= $alimento['stock_minimo']) {
                                $stockBadgeClass = 'bg-danger';
                                $stockIcon = 'fa-exclamation-triangle';
                                $stockText = 'Bajo';
                            } elseif ($porcentajeStock <= 150) {
                                $stockBadgeClass = 'bg-warning text-dark';
                                $stockIcon = 'fa-info-circle';
                                $stockText = 'Normal';
                            } else {
                                $stockBadgeClass = 'bg-success';
                                $stockIcon = 'fa-check-circle';
                                $stockText = 'Óptimo';
                            }

                            // Iconos y colores para tipos
                            $tipos = [
                                'concentrado' => ['fa-weight', 'primary', 'Concentrado'],
                                'forraje' => ['fa-leaf', 'success', 'Forraje'],
                                'suplemento' => ['fa-pills', 'info', 'Suplemento'],
                                'mineral' => ['fa-gem', 'secondary', 'Mineral'],
                                'otro' => ['fa-box', 'dark', 'Otro']
                            ];
                            list($tipoIcono, $tipoColor, $tipoTexto) = $tipos[$alimento['tipo']] ?? ['fa-box', 'secondary', $alimento['tipo']];
                        @endphp
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-apple-alt text-warning me-2"></i>
                                    <div>
                                        <strong>{{ $alimento['nombre'] }}</strong>
                                        @if($alimento['descripcion'])
                                        <br><small class="text-muted">{{ Str::limit($alimento['descripcion'], 50) }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $tipoColor }} text-capitalize">
                                    <i class="fas {{ $tipoIcono }} me-1"></i>{{ $tipoTexto }}
                                </span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar {{ $stockBadgeClass }}" 
                                         role="progressbar" 
                                         style="width: {{ min($porcentajeStock, 100) }}%"
                                         aria-valuenow="{{ $porcentajeStock }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $alimento['stock_actual'] }}
                                    </div>
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Mín: {{ $alimento['stock_minimo'] }}
                                </small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">
                                    {{ $alimento['unidad_medida'] }}
                                </span>
                            </td>
                            <td>
                                @if($alimento['precio_unitario'])
                                <span class="text-success fw-bold">
                                    Q{{ number_format($alimento['precio_unitario'], 2) }}
                                </span>
                                @else
                                <span class="text-muted">No definido</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-tractor me-1"></i>
                                    {{ $alimento['finca']['nombre'] ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <span class="badge {{ $stockBadgeClass }}">
                                    <i class="fas {{ $stockIcon }} me-1"></i>{{ $stockText }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Ver - Todos los roles -->
                                    <a href="{{ route('alimentacion.alimentos.show', $alimento['id']) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Editar - Solo Admin y Veterinario -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <a href="{{ route('alimentacion.alimentos.edit', $alimento['id']) }}" 
                                       class="btn btn-warning btn-sm"
                                       title="Editar"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    
                                    <!-- Eliminar - Solo Admin y Veterinario -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <form action="{{ route('alimentacion.alimentos.destroy', $alimento['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar el alimento {{ $alimento['nombre'] }}?')">
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
                    <i class="fas fa-apple-alt fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay alimentos registrados</h4>
                    <p class="text-muted">Comienza agregando el primer alimento al inventario</p>
                </div>
                <!-- Botón solo para Admin y Veterinario -->
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('alimentacion.alimentos.create') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primer Alimento
                </a>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Contacta al administrador para registrar alimentos en el sistema.
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    @if(count($alimentos) > 0)
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Alimentos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($alimentos) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-apple-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Stock Bajo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                            {{ collect($alimentos)->filter(function($alimento) {
                                return $alimento['stock_actual'] <= $alimento['stock_minimo'];
                            })->count() }}
                        </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Stock Óptimo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($alimentos)->filter(function($alimento) {
                                    return $alimento['stock_actual'] > $alimento['stock_minimo'] * 1.5;
                                })->count() }}
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
                                Valor Inventario
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Q{{ number_format(collect($alimentos)->sum(function($alimento) {
                                    return ($alimento['precio_unitario'] ?? 0) * $alimento['stock_actual'];
                                }), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
        background-color: rgba(255, 193, 7, 0.1) !important; 
        cursor: pointer;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 0.375rem;
    }
    .progress-bar {
        border-radius: 0.375rem;
        font-size: 0.75rem;
        font-weight: bold;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterTipo = document.getElementById('filterTipo');
        const filterStock = document.getElementById('filterStock');
        const filterFinca = document.getElementById('filterFinca');
        const clearFilters = document.getElementById('clearFilters');
        const alimentosCount = document.getElementById('alimentosCount');
        const table = document.getElementById('alimentosTable');
        const rows = table ? table.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];
        
        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const tipoValue = filterTipo.value;
            const stockValue = filterStock.value;
            const fincaValue = filterFinca.value;
            
            let visibleCount = 0;

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                const nombre = cells[1].textContent.toLowerCase();
                const tipo = cells[2].textContent.toLowerCase();
                const finca = cells[6].textContent.toLowerCase();
                const estadoStock = cells[7].querySelector('.badge').textContent.toLowerCase().trim();

                // Buscar en nombre
                const matchesSearch = !searchText || 
                    nombre.includes(searchText);

                // Filtrar por tipo
                const matchesTipo = !tipoValue || 
                    tipo.includes(tipoValue);

                // Filtrar por finca
                const matchesFinca = !fincaValue || 
                    finca.includes(fincaValue.toLowerCase());

                // Filtrar por estado de stock
                let matchesStock = true;
                if (stockValue === 'bajo') {
                    matchesStock = estadoStock.includes('bajo');
                } else if (stockValue === 'normal') {
                    matchesStock = estadoStock.includes('normal');
                } else if (stockValue === 'optimo') {
                    matchesStock = estadoStock.includes('óptimo');
                }

                const isVisible = matchesSearch && matchesTipo && matchesStock && matchesFinca;
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                }
            }

            // Actualizar contador
            alimentosCount.textContent = visibleCount + ' alimentos';
            alimentosCount.className = visibleCount === 0 ? 'badge bg-danger' : 'badge bg-warning';
        }

        // Event listeners
        searchInput.addEventListener('keyup', filterTable);
        filterTipo.addEventListener('change', filterTable);
        filterStock.addEventListener('change', filterTable);
        filterFinca.addEventListener('change', filterTable);

        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            filterTipo.value = '';
            filterStock.value = '';
            filterFinca.value = '';
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