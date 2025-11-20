@extends('layouts.app')

@section('title', 'Producción de Leche')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-milk-bottle text-warning"></i> Producción de Leche
        </h1>
        <div>
            <a href="{{ route('produccion-leche.reportes') }}" class="btn btn-info btn-icon-split me-2">
                <span class="icon text-white-50">
                    <i class="fas fa-chart-bar"></i>
                </span>
                <span class="text">Reportes</span>
            </a>
            <a href="{{ route('produccion-leche.create') }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-plus"></i>
                </span>
                <span class="text">Nuevo Registro</span>
            </a>
        </div>
    </div>


    <!-- Estadísticas Rápidas -->
    @if(count($producciones) > 0)
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Leche (L)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(collect($producciones)->sum('cantidad_leche'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-milk-bottle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Promedio Grasa</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(collect($producciones)->avg('calidad_grasa') ?? 0, 1) }}%
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Registros</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($producciones) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Animales Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count(array_unique(collect($producciones)->pluck('animal_id')->toArray())) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cow fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Búsqueda y Filtros -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por animal, finca...">
            </div>
        </div>
        <div class="col-md-2">
            <select id="filterTurno" class="form-select">
                <option value="">Todos los turnos</option>
                <option value="mañana">Mañana</option>
                <option value="tarde">Tarde</option>
                <option value="noche">Noche</option>
            </select>
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
            <input type="date" id="filterFecha" class="form-control" placeholder="Filtrar por fecha">
        </div>
        <div class="col-md-2">
            <button id="clearFilters" class="btn btn-outline-secondary w-100">
                <i class="fas fa-times"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Producciones Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-list me-2"></i>Registros de Producción
            </h6>
            <span class="badge bg-warning" id="produccionesCount">{{ count($producciones) }} registros</span>
        </div>
        <div class="card-body">
            @if(count($producciones) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="produccionesTable" width="100%" cellspacing="0">
                    <thead class="table-warning">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Animal</th>
                            <th>Finca</th>
                            <th>Cantidad (L)</th>
                            <th>Grasa</th>
                            <th>Proteína</th>
                            <th>Turno</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($producciones as $index => $produccion)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($produccion['fecha'])->format('d/m/Y') }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $produccion['animal']['identificacion'] ?? 'N/A' }}</strong>
                                    @if($produccion['animal']['nombre'] ?? false)
                                    <br><small class="text-muted">{{ $produccion['animal']['nombre'] }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>{{ $produccion['animal']['finca']['nombre'] ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-success">{{ $produccion['cantidad_leche'] }} L</span>
                            </td>
                            <td>
                                @if($produccion['calidad_grasa'])
                                <span class="badge bg-info">{{ $produccion['calidad_grasa'] }}%</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($produccion['calidad_proteina'])
                                <span class="badge bg-primary">{{ $produccion['calidad_proteina'] }}%</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $turnoColors = [
                                        'mañana' => 'success',
                                        'tarde' => 'warning',
                                        'noche' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $turnoColors[$produccion['turno']] ?? 'secondary' }} text-capitalize">
                                    {{ $produccion['turno'] }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('produccion-leche.show', $produccion['id']) }}" 
                                       class="btn btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('produccion-leche.edit', $produccion['id']) }}" 
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('produccion-leche.destroy', $produccion['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este registro de producción?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                    <i class="fas fa-milk-bottle fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay registros de producción</h4>
                    <p class="text-muted">Comienza registrando la primera producción de leche</p>
                </div>
                <a href="{{ route('produccion-leche.create') }}" class="btn btn-warning btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primera Producción
                </a>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover { 
        background-color: rgba(255, 193, 7, 0.1) !important; 
        cursor: pointer;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const filterTurno = document.getElementById('filterTurno');
        const filterFinca = document.getElementById('filterFinca');
        const filterFecha = document.getElementById('filterFecha');
        const clearFilters = document.getElementById('clearFilters');
        const produccionesCount = document.getElementById('produccionesCount');
        const table = document.getElementById('produccionesTable');
        const rows = table ? table.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];
        
        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const turnoValue = filterTurno.value;
            const fincaValue = filterFinca.value;
            const fechaValue = filterFecha.value;
            
            let visibleCount = 0;
            let totalLeche = 0, totalRegistros = 0;

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                const animal = cells[2].textContent.toLowerCase();
                const finca = cells[3].textContent.toLowerCase();
                const fecha = cells[1].textContent.toLowerCase();
                const turno = cells[7].textContent.toLowerCase();
                const cantidadLeche = parseFloat(cells[4].querySelector('.badge').textContent);

                // Buscar en animal y finca
                const matchesSearch = !searchText || 
                    animal.includes(searchText) ||
                    finca.includes(searchText);

                // Filtrar por turno
                const matchesTurno = !turnoValue || 
                    turno.includes(turnoValue.toLowerCase());

                // Filtrar por finca
                const matchesFinca = !fincaValue || 
                    finca.includes(fincaValue.toLowerCase());

                // Filtrar por fecha
                let matchesFecha = true;
                if (fechaValue) {
                    const fechaCell = cells[1].querySelector('strong').textContent;
                    const fechaFormateada = convertirFecha(fechaCell);
                    matchesFecha = fechaFormateada === fechaValue;
                }

                const isVisible = matchesSearch && matchesTurno && matchesFinca && matchesFecha;
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                    totalLeche += cantidadLeche;
                    totalRegistros++;
                }
            }

            // Actualizar contador
            produccionesCount.textContent = visibleCount + ' registros';
            produccionesCount.className = visibleCount === 0 ? 'badge bg-danger' : 'badge bg-warning';

            // Actualizar estadísticas si existen
            updateStatistics(totalLeche, totalRegistros, visibleCount);
        }

        function convertirFecha(fechaTexto) {
            // Convertir de "dd/mm/yyyy" a "yyyy-mm-dd"
            const partes = fechaTexto.split('/');
            if (partes.length === 3) {
                return `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
            }
            return fechaTexto;
        }

        function updateStatistics(totalLeche, totalRegistros, visibleCount) {
            // Actualizar tarjetas de estadísticas
            const totalLecheCard = document.querySelector('.card-border-left-primary .h5');
            const registrosCard = document.querySelector('.card-border-left-info .h5');
            const animalesCard = document.querySelector('.card-border-left-warning .h5');

            if (totalLecheCard) {
                totalLecheCard.textContent = totalLeche.toFixed(2);
            }
            if (registrosCard) {
                registrosCard.textContent = visibleCount;
            }
            // Nota: El conteo de animales activos no se puede actualizar fácilmente sin recargar
        }

        // Event listeners
        searchInput.addEventListener('keyup', filterTable);
        filterTurno.addEventListener('change', filterTable);
        filterFinca.addEventListener('change', filterTable);
        filterFecha.addEventListener('change', filterTable);

        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            filterTurno.value = '';
            filterFinca.value = '';
            filterFecha.value = '';
            filterTable();
        });
    });
</script>
@endsection