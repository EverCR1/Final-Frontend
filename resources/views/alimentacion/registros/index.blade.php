@extends('layouts.app')

@section('title', 'Registros de Alimentación')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-clipboard-list text-primary"></i> Registros de Alimentación
        </h1>
        
        <!-- Botón Nuevo Registro - Solo Admin y Veterinario -->
        @if(in_array(session('user.role'), ['admin', 'veterinario']))
        <a href="{{ route('alimentacion.registros.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nuevo Registro</span>
        </a>
        @endif
    </div>

    <!-- Información de permisos para Productor -->
    @if(session('user.role') === 'productor')
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Modo Consulta:</strong> Como productor, tienes acceso de solo lectura a los registros de alimentación.
    </div>
    @endif

    <!-- Búsqueda y Filtros -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="input-group">
                <span class="input-group-text">
                    
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Dieta, animal, responsable...">
                <button id="searchButton" class="btn btn-primary" type="button">
                    <i class="fas fa-search"></i>
                </button>
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
            <select id="filterTurno" class="form-select">
                <option value="">Todos los turnos</option>
                <option value="mañana">Mañana</option>
                <option value="tarde">Tarde</option>
                <option value="noche">Noche</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" id="filterFecha" class="form-control" placeholder="Fecha específica">
        </div>
        <div class="col-md-3">
            <div class="btn-group w-100" role="group">
                <button id="filterHoy" class="btn btn-outline-info btn-sm">Hoy</button>
                <button id="filterSemana" class="btn btn-outline-info btn-sm">Semana</button>
                <button id="clearFilters" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-times"></i> Limpiar
                </button>
            </div>
        </div>
    </div>

    <!-- Registros Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Historial de Alimentación
            </h6>
            <span class="badge bg-primary" id="registrosCount">{{ count($registros) }} registros</span>
        </div>
        <div class="card-body">
            @if(count($registros) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="registrosTable" width="100%" cellspacing="0">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Dieta</th>
                            <th>Animal</th>
                            <th>Cantidad</th>
                            <th>Costo</th>
                            <th>Turno</th>
                            <th>Responsable</th>
                            <th>Finca</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($registros as $index => $registro)
                        @php
                            // Colores para turnos
                            $turnoColors = [
                                'mañana' => 'success',
                                'tarde' => 'warning',
                                'noche' => 'dark'
                            ];
                            
                            $turnoIcons = [
                                'mañana' => 'fa-sun',
                                'tarde' => 'fa-cloud-sun',
                                'noche' => 'fa-moon'
                            ];
                        @endphp
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</strong>
                                <br>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($registro['created_at'])->format('H:i') }}
                                </small>
                            </td>
                            <td>
                                @if($registro['dieta'])
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-utensils text-success me-2"></i>
                                    <div>
                                        <strong>{{ $registro['dieta']['nombre'] }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $registro['dieta']['tipo_animal'] }} - {{ $registro['dieta']['categoria'] }}
                                        </small>
                                    </div>
                                </div>
                                @else
                                <span class="text-muted">Dieta no disponible</span>
                                @endif
                            </td>
                            <td>
                                @if($registro['animal'])
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-horse text-info me-2"></i>
                                    <div>
                                        <strong>{{ $registro['animal']['nombre'] ?: 'Sin nombre' }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            {{ $registro['animal']['identificacion'] }}
                                        </small>
                                    </div>
                                </div>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-users me-1"></i>Alimentación Grupal
                                </span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold text-primary">
                                    {{ $registro['cantidad_total'] }} kg
                                </span>
                            </td>
                            <td>
                                @if($registro['costo_total'])
                                <span class="text-success fw-bold">
                                    Q{{ number_format($registro['costo_total'], 2) }}
                                </span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $turnoColors[$registro['turno']] ?? 'secondary' }} text-capitalize">
                                    <i class="fas {{ $turnoIcons[$registro['turno']] ?? 'fa-clock' }} me-1"></i>
                                    {{ $registro['turno'] }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $registro['responsable'] }}
                                </small>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-tractor me-1"></i>
                                    {{ $registro['finca']['nombre'] ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <!-- Ver - Todos los roles -->
                                    <a href="{{ route('alimentacion.registros.show', $registro['id']) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    
                                    <!-- Editar - Solo Admin y Veterinario -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <a href="{{ route('alimentacion.registros.edit', $registro['id']) }}" 
                                       class="btn btn-warning btn-sm"
                                       title="Editar"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @endif
                                    
                                    <!-- Eliminar - Solo Admin y Veterinario -->
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <form action="{{ route('alimentacion.registros.destroy', $registro['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este registro de alimentación?')">
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
                    <i class="fas fa-clipboard-list fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay registros de alimentación</h4>
                    <p class="text-muted">Comienza registrando la primera alimentación del ganado</p>
                </div>
                <!-- Botón solo para Admin y Veterinario -->
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('alimentacion.registros.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primera Alimentación
                </a>
                @else
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Contacta al administrador para registrar alimentaciones en el sistema.
                </div>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    @if(count($registros) > 0)
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Registros
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($registros) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                Registros Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($registros)->where('fecha', \Carbon\Carbon::today()->toDateString())->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
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
                                Costo Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Q{{ number_format(collect($registros)->sum('costo_total'), 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Cantidad Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(collect($registros)->sum('cantidad_total'), 1) }} kg
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Distribución por Turno -->
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Distribución por Turno
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $turnosCount = [
                            'mañana' => collect($registros)->where('turno', 'mañana')->count(),
                            'tarde' => collect($registros)->where('turno', 'tarde')->count(),
                            'noche' => collect($registros)->where('turno', 'noche')->count()
                        ];
                        $totalTurnos = array_sum($turnosCount);
                    @endphp
                    @foreach($turnosCount as $turno => $count)
                    @if($count > 0)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="d-flex align-items-center">
                            <span class="badge bg-{{ $turnoColors[$turno] }} me-2 text-capitalize">
                                {{ $turno }}
                            </span>
                            <span>{{ $count }} registros</span>
                        </div>
                        <span class="text-muted">{{ number_format(($count / $totalTurnos) * 100, 1) }}%</span>
                    </div>
                    <div class="progress mb-3" style="height: 8px;">
                        <div class="progress-bar bg-{{ $turnoColors[$turno] }}" 
                             style="width: {{ ($count / $totalTurnos) * 100 }}%">
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-line me-2"></i>Resumen de Hoy
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $registrosHoy = collect($registros)->where('fecha', \Carbon\Carbon::today()->toDateString());
                        $cantidadHoy = $registrosHoy->sum('cantidad_total');
                        $costoHoy = $registrosHoy->sum('costo_total');
                        $registrosHoyCount = $registrosHoy->count();
                    @endphp
                    <div class="text-center">
                        <h3 class="text-success">{{ $registrosHoyCount }}</h3>
                        <p class="text-muted">Registros hoy</p>
                    </div>
                    <div class="row text-center">
                        <div class="col-6">
                            <strong class="text-primary">{{ number_format($cantidadHoy, 1) }} kg</strong>
                            <br>
                            <small class="text-muted">Cantidad</small>
                        </div>
                        <div class="col-6">
                            <strong class="text-success">Q{{ number_format($costoHoy, 2) }}</strong>
                            <br>
                            <small class="text-muted">Costo</small>
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
        background-color: rgba(13, 110, 253, 0.1) !important; 
        cursor: pointer;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    .progress {
        background-color: #e9ecef;
    }
</style>
@endsection

@section('scripts')
@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('JavaScript cargado - Iniciando filtros...');
        
        // Obtener elementos con verificación de null
        const getElement = (id) => {
            const element = document.getElementById(id);
            if (!element) {
                console.error(`❌ No se encontró el elemento: ${id}`);
            }
            return element;
        };

        const searchInput = getElement('searchInput');
        const searchButton = getElement('searchButton');
        const filterFinca = getElement('filterFinca');
        const filterTurno = getElement('filterTurno');
        const filterFecha = getElement('filterFecha');
        const filterHoy = getElement('filterHoy');
        const filterSemana = getElement('filterSemana');
        const clearFilters = getElement('clearFilters');
        const registrosCount = getElement('registrosCount');
        const table = getElement('registrosTable');
        
        // Verificar que todos los elementos esenciales existen
        if (!table) {
            console.error('ERROR: No se encontró la tabla');
            return;
        }
        
        const tbody = table.getElementsByTagName('tbody')[0];
        if (!tbody) {
            console.error('ERROR: No se encontró tbody');
            return;
        }
        
        const rows = tbody.getElementsByTagName('tr');
        console.log('✅ Filas encontradas en la tabla:', rows.length);

        function filterTable() {
            console.log('🔍 Ejecutando filtro...');
            
            // Usar valores por defecto si los elementos no existen
            const searchText = searchInput ? searchInput.value.toLowerCase().trim() : '';
            const fincaValue = filterFinca ? filterFinca.value : '';
            const turnoValue = filterTurno ? filterTurno.value : '';
            const fechaValue = filterFecha ? filterFecha.value : '';
            
            console.log('Parámetros:', { searchText, fincaValue, turnoValue, fechaValue });
            
            let visibleCount = 0;

            for (let i = 0; i < rows.length; i++) {
                const row = rows[i];
                const cells = row.getElementsByTagName('td');
                
                if (cells.length < 9) {
                    console.warn('Fila con menos de 9 celdas:', cells.length);
                    continue;
                }
                
                try {
                    // Obtener texto de las celdas importantes
                    const fechaText = cells[1].textContent || '';
                    const dietaText = (cells[2].textContent || '').toLowerCase();
                    const animalText = (cells[3].textContent || '').toLowerCase();
                    const responsableText = (cells[7].textContent || '').toLowerCase();
                    const fincaText = (cells[8].textContent || '').toLowerCase();
                    const turnoText = (cells[6].textContent || '').toLowerCase();

                    // Convertir fecha a formato YYYY-MM-DD para comparación
                    let fechaFormateada = '';
                    try {
                        const fechaParts = fechaText.split('/');
                        if (fechaParts.length === 3) {
                            fechaFormateada = fechaParts[2] + '-' + fechaParts[1] + '-' + fechaParts[0];
                        }
                    } catch (e) {
                        console.error('Error formateando fecha:', e);
                    }

                    // Búsqueda en múltiples campos
                    const matchesSearch = !searchText || 
                        dietaText.includes(searchText) ||
                        animalText.includes(searchText) ||
                        responsableText.includes(searchText) ||
                        fincaText.includes(searchText) ||
                        turnoText.includes(searchText);

                    const matchesFinca = !fincaValue || fincaText.includes(fincaValue.toLowerCase());
                    const matchesTurno = !turnoValue || turnoText.includes(turnoValue);
                    const matchesFecha = !fechaValue || fechaFormateada === fechaValue;

                    const isVisible = matchesSearch && matchesFinca && matchesTurno && matchesFecha;
                    row.style.display = isVisible ? '' : 'none';
                    
                    if (isVisible) {
                        visibleCount++;
                    }
                } catch (error) {
                    console.error('Error procesando fila:', error);
                    row.style.display = ''; // Mostrar fila por defecto si hay error
                }
            }

            console.log('✅ Filas visibles después del filtro:', visibleCount);

            // Actualizar contador principal
            if (registrosCount) {
                const total = rows.length;
                if (visibleCount === total) {
                    registrosCount.textContent = total + ' registros';
                    registrosCount.className = 'badge bg-primary';
                } else if (visibleCount === 0) {
                    registrosCount.textContent = '0 registros encontrados';
                    registrosCount.className = 'badge bg-danger';
                } else {
                    registrosCount.textContent = visibleCount + ' de ' + total + ' registros';
                    registrosCount.className = 'badge bg-info';
                }
            }
        }

        // Agregar event listeners solo si los elementos existen
        if (searchButton) {
            searchButton.addEventListener('click', filterTable);
            console.log('✅ Botón de búsqueda configurado');
        }

        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    filterTable();
                }
            });
            console.log('✅ Búsqueda con Enter configurada');
        }

        if (filterFinca) {
            filterFinca.addEventListener('change', filterTable);
            console.log('✅ Filtro de finca configurado');
        }

        if (filterTurno) {
            filterTurno.addEventListener('change', filterTable);
            console.log('✅ Filtro de turno configurado');
        }

        if (filterFecha) {
            filterFecha.addEventListener('change', filterTable);
            console.log('✅ Filtro de fecha configurado');
        }

        if (filterHoy) {
            filterHoy.addEventListener('click', function() {
                if (filterFecha) {
                    const today = new Date().toISOString().split('T')[0];
                    filterFecha.value = today;
                    filterTable();
                }
            });
            console.log('✅ Filtro de hoy configurado');
        }

        if (filterSemana) {
            filterSemana.addEventListener('click', function() {
                // Por ahora solo ejecuta el filtro actual
                filterTable();
            });
            console.log('✅ Filtro de semana configurado');
        }

        if (clearFilters) {
            clearFilters.addEventListener('click', function() {
                if (searchInput) searchInput.value = '';
                if (filterFinca) filterFinca.value = '';
                if (filterTurno) filterTurno.value = '';
                if (filterFecha) filterFecha.value = '';
                filterTable();
            });
            console.log('✅ Botón limpiar configurado');
        }

        // Establecer fecha máxima como hoy
        if (filterFecha) {
            const today = new Date().toISOString().split('T')[0];
            filterFecha.max = today;
        }

        // Ejecutar filtro inicial para asegurar que todo funciona
        filterTable();
        console.log('✅ Filtro inicial ejecutado');
    });
</script>
@endsection