@extends('layouts.app')

@section('title', 'Gestión de Medicamentos')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills text-info"></i> Gestión de Medicamentos
        </h1>
        
        <!-- Botón Nuevo Medicamento - SOLO ADMIN y VETERINARIO -->
        @if(in_array(session('user.role'), ['admin', 'veterinario']))
        <a href="{{ route('medicamentos.create') }}" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nuevo Medicamento</span>
        </a>
        @endif
    </div>

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
            <select id="filterTipo" class="form-select">
                <option value="">Todos los tipos</option>
                <option value="vacuna">Vacuna</option>
                <option value="antibiotico">Antibiótico</option>
                <option value="vitaminas">Vitaminas</option>
                <option value="desparasitante">Desparasitante</option>
                <option value="otro">Otro</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterEstado" class="form-select">
                <option value="">Todos los estados</option>
                <option value="agotado">Agotado</option>
                <option value="bajo">Stock Bajo</option>
                <option value="disponible">Disponible</option>
            </select>
        </div>
        <div class="col-md-2">
            <select id="filterVencimiento" class="form-select">
                <option value="">Todo vencimiento</option>
                <option value="vencido">Vencido</option>
                <option value="por_vencer">Por Vencer</option>
                <option value="vigente">Vigente</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="clearFilters" class="btn btn-outline-secondary w-100">
                <i class="fas fa-times"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Medicamentos Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-list me-2"></i>Inventario de Medicamentos
            </h6>
            <span class="badge bg-info" id="medicamentosCount">{{ count($medicamentos) }} medicamentos registrados</span>
        </div>
        <div class="card-body">
            @if(count($medicamentos) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="medicamentosTable" width="100%" cellspacing="0">
                    <thead class="table-info">
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Finca</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                            @if(in_array(session('user.role'), ['admin', 'veterinario']))
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicamentos as $index => $medicamento)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <strong>{{ $medicamento['nombre'] }}</strong>
                                @if($medicamento['descripcion'])
                                <br><small class="text-muted">{{ Str::limit($medicamento['descripcion'], 50) }}</small>
                                @endif
                            </td>
                            <td>
                                @php
                                    $tipoColors = [
                                        'vacuna' => 'success',
                                        'antibiotico' => 'warning',
                                        'vitaminas' => 'primary',
                                        'desparasitante' => 'danger',
                                        'otro' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $tipoColors[$medicamento['tipo']] ?? 'secondary' }} text-capitalize">
                                    <i class="fas fa-syringe me-1"></i>{{ $medicamento['tipo'] }}
                                </span>
                            </td>
                            <td>{{ $medicamento['finca_nombre'] ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2 {{ $medicamento['stock_actual'] <= $medicamento['stock_minimo'] ? 'text-danger fw-bold' : 'text-success' }}">
                                        {{ $medicamento['stock_actual'] }}
                                    </span>
                                    <small class="text-muted">/ min {{ $medicamento['stock_minimo'] }}</small>
                                </div>
                                @if($medicamento['stock_actual'] <= $medicamento['stock_minimo'])
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Stock bajo
                                </small>
                                @endif
                            </td>
                            <td>
                                @if($medicamento['precio_unitario'])
                                Q{{ number_format($medicamento['precio_unitario'], 2) }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($medicamento['fecha_vencimiento'])
                                    @php
                                        $hoy = now();
                                        $vencimiento = \Carbon\Carbon::parse($medicamento['fecha_vencimiento']);
                                        $diasRestantes = $hoy->diffInDays($vencimiento, false);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="text-danger" title="Vencido">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $vencimiento->format('d/m/Y') }}
                                        </span>
                                    @elseif($diasRestantes <= 30)
                                        <span class="text-warning" title="Por vencer">
                                            <i class="fas fa-clock"></i> {{ $vencimiento->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-success">{{ $vencimiento->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                <span class="text-muted">No aplica</span>
                                @endif
                            </td>
                            <td>
                                @if($medicamento['stock_actual'] <= 0)
                                <span class="badge bg-danger">Agotado</span>
                                @elseif($medicamento['stock_actual'] <= $medicamento['stock_minimo'])
                                <span class="badge bg-warning">Stock Bajo</span>
                                @else
                                <span class="badge bg-success">Disponible</span>
                                @endif
                            </td>
                            
                            <!-- Columna de Acciones - SOLO ADMIN y VETERINARIO -->
                            @if(in_array(session('user.role'), ['admin', 'veterinario']))
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('medicamentos.show', $medicamento['id']) }}" 
                                       class="btn btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('medicamentos.edit', $medicamento['id']) }}" 
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <form action="{{ route('medicamentos.destroy', $medicamento['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este medicamento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-pills fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay medicamentos registrados</h4>
                    <p class="text-muted">Comienza agregando el primer medicamento al inventario</p>
                </div>
                <!-- Botón solo para admin y veterinario cuando no hay medicamentos -->
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('medicamentos.create') }}" class="btn btn-info btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primer Medicamento
                </a>
                @else
                <p class="text-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Contacta al administrador o veterinario para gestionar medicamentos
                </p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover { 
        background-color: rgba(23, 162, 184, 0.1) !important; 
        cursor: pointer;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterTipo = document.getElementById('filterTipo');
        const filterEstado = document.getElementById('filterEstado');
        const filterVencimiento = document.getElementById('filterVencimiento');
        const clearFilters = document.getElementById('clearFilters');
        const medicamentosCount = document.getElementById('medicamentosCount');
        const table = document.getElementById('medicamentosTable');
        const rows = table ? table.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];
        
        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const tipoValue = filterTipo.value;
            const estadoValue = filterEstado.value;
            const vencimientoValue = filterVencimiento.value;
            
            let visibleCount = 0;

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                const nombre = cells[1].textContent.toLowerCase();
                const tipo = cells[2].textContent.toLowerCase();
                const estado = cells[7].textContent.toLowerCase();
                const vencimientoCell = cells[6];
                
                // Obtener el estado real del vencimiento basado en clases y contenido
                let estadoVencimiento = 'vigente';
                if (vencimientoCell.innerHTML.includes('fa-exclamation-triangle')) {
                    estadoVencimiento = 'vencido';
                } else if (vencimientoCell.innerHTML.includes('fa-clock')) {
                    estadoVencimiento = 'por_vencer';
                }

                // Buscar en nombre y descripción
                const matchesSearch = !searchText || 
                    nombre.includes(searchText);

                // Filtrar por tipo
                const matchesTipo = !tipoValue || 
                    tipo.includes(tipoValue.toLowerCase());

                // Filtrar por estado
                let matchesEstado = true;
                if (estadoValue === 'agotado') {
                    matchesEstado = estado.includes('agotado');
                } else if (estadoValue === 'bajo') {
                    matchesEstado = estado.includes('bajo');
                } else if (estadoValue === 'disponible') {
                    matchesEstado = estado.includes('disponible');
                }

                // Filtrar por vencimiento - CORREGIDO
                let matchesVencimiento = true;
                if (vencimientoValue === 'vencido') {
                    matchesVencimiento = estadoVencimiento === 'vencido';
                } else if (vencimientoValue === 'por_vencer') {
                    matchesVencimiento = estadoVencimiento === 'por_vencer';
                } else if (vencimientoValue === 'vigente') {
                    matchesVencimiento = estadoVencimiento === 'vigente';
                }

                const isVisible = matchesSearch && matchesTipo && matchesEstado && matchesVencimiento;
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                }
            }

            // Actualizar contador
            medicamentosCount.textContent = visibleCount + ' medicamentos registrados';
            medicamentosCount.className = visibleCount === 0 ? 'badge bg-danger' : 'badge bg-info';
        }

        // Event listeners
        searchInput.addEventListener('keyup', filterTable);
        filterTipo.addEventListener('change', filterTable);
        filterEstado.addEventListener('change', filterTable);
        filterVencimiento.addEventListener('change', filterTable);

        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            filterTipo.value = '';
            filterEstado.value = '';
            filterVencimiento.value = '';
            filterTable();
        });
    });
</script>
@endsection