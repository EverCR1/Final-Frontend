@extends('layouts.app')

@section('title', 'Reportes de Alimentación')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar text-info"></i> Reportes de Alimentación
        </h1>
        <a href="{{ route('alimentacion.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver al Módulo</span>
        </a>
    </div>

    <!-- Filtros de Reportes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-filter me-2"></i>Filtros del Reporte
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('alimentacion.generar-reporte') }}" id="reporteForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="fecha_inicio" name="fecha_inicio" 
                               value="{{ old('fecha_inicio', date('Y-m-01')) }}" 
                               max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="fecha_fin" name="fecha_fin" 
                               value="{{ old('fecha_fin', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}" required>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="tipo_reporte" class="form-label">Tipo de Reporte</label>
                        <select class="form-select" id="tipo_reporte" name="tipo_reporte">
                            <option value="general">Reporte General</option>
                            <option value="costos">Análisis de Costos</option>
                            <option value="dietas">Uso de Dietas</option>
                            <option value="turnos">Distribución por Turnos</option>
                            <option value="fincas">Por Fincas</option>
                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="finca_id" class="form-label">Finca (Opcional)</label>
                        <select class="form-select" id="finca_id" name="finca_id">
                            <option value="">Todas las Fincas</option>
                            @foreach($fincas ?? [] as $finca)
                            <option value="{{ $finca['id'] }}">{{ $finca['nombre'] }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Rangos Predefinidos</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-info btn-sm" id="btnHoy">Hoy</button>
                            <button type="button" class="btn btn-outline-info btn-sm" id="btnSemana">Esta Semana</button>
                            <button type="button" class="btn btn-outline-info btn-sm" id="btnMes">Este Mes</button>
                            <button type="button" class="btn btn-outline-info btn-sm" id="btnTrimestre">Este Trimestre</button>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3 d-flex align-items-end">
                        <div class="btn-group w-100" role="group">
                            <button type="submit" class="btn btn-info">
                                <i class="fas fa-chart-line me-2"></i>Generar Reporte
                            </button>
                            <button type="button" class="btn btn-outline-secondary" id="limpiarFiltros">
                                <i class="fas fa-times me-2"></i>Limpiar
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Registros
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalRegistros">
                                {{ $estadisticas['total_registros'] ?? 0 }}
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Cantidad Total (kg)
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="cantidadTotal">
                                {{ number_format($estadisticas['cantidad_total'] ?? 0, 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-weight fa-2x text-gray-300"></i>
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
                                Costo Total
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="costoTotal">
                                Q{{ number_format($estadisticas['costo_total'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Costo Promedio/kg
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="costoPromedio">
                                Q{{ number_format($estadisticas['costo_promedio_kg'] ?? 0, 2) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calculator fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Tabla de Registros Recientes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-table me-2"></i>Registros Recientes
            </h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="tablaRegistros">
                    <thead class="table-info">
                        <tr>
                            <th>Fecha</th>
                            <th>Dieta</th>
                            <th>Animal</th>
                            <th>Cantidad (kg)</th>
                            <th>Costo (Q)</th>
                            <th>Turno</th>
                            <th>Responsable</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($registros_recientes) && count($registros_recientes) > 0)
                            @foreach($registros_recientes as $registro)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</td>
                                <td>{{ $registro['dieta']['nombre'] ?? 'N/A' }}</td>
                                <td>
                                    @if($registro['animal'])
                                        {{ $registro['animal']['nombre'] ?: $registro['animal']['identificacion'] }}
                                    @else
                                        <span class="badge bg-secondary">Grupal</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($registro['cantidad_total'], 2) }}</td>
                                <td class="text-end">Q{{ number_format($registro['costo_total'] ?? 0, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $registro['turno'] == 'mañana' ? 'success' : ($registro['turno'] == 'tarde' ? 'warning' : 'dark') }} text-capitalize">
                                        {{ $registro['turno'] }}
                                    </span>
                                </td>
                                <td>{{ $registro['responsable'] }}</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center py-4 text-muted">
                                    <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                                    <p>No hay registros recientes para mostrar</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Resumen por Fincas -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-tractor me-2"></i>Resumen por Fincas
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                @if(isset($resumen_fincas) && count($resumen_fincas) > 0)
                    @foreach($resumen_fincas as $finca)
                    <div class="col-md-4 mb-3">
                        <div class="card border-warning">
                            <div class="card-header bg-warning text-dark py-2">
                                <h6 class="m-0 font-weight-bold">
                                    <i class="fas fa-warehouse me-2"></i>{{ $finca['nombre'] }}
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Registros:</span>
                                    <strong>{{ $finca['total_registros'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Cantidad:</span>
                                    <strong>{{ number_format($finca['cantidad_total'], 1) }} kg</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Costo:</span>
                                    <strong class="text-success">Q{{ number_format($finca['costo_total'], 2) }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="col-12 text-center py-4 text-muted">
                        <i class="fas fa-tractor fa-2x mb-3"></i>
                        <p>No hay datos de fincas para mostrar</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');
        const btnHoy = document.getElementById('btnHoy');
        const btnSemana = document.getElementById('btnSemana');
        const btnMes = document.getElementById('btnMes');
        const btnTrimestre = document.getElementById('btnTrimestre');
        const limpiarFiltros = document.getElementById('limpiarFiltros');

        // Establecer fechas máximas
        const today = new Date().toISOString().split('T')[0];
        fechaInicio.max = today;
        fechaFin.max = today;

        // Filtro: Hoy
        btnHoy.addEventListener('click', function() {
            fechaInicio.value = today;
            fechaFin.value = today;
        });

        // Filtro: Esta semana
        btnSemana.addEventListener('click', function() {
            const now = new Date();
            const firstDay = new Date(now.setDate(now.getDate() - now.getDay()));
            const lastDay = new Date(now.setDate(now.getDate() - now.getDay() + 6));
            
            fechaInicio.value = firstDay.toISOString().split('T')[0];
            fechaFin.value = lastDay.toISOString().split('T')[0];
        });

        // Filtro: Este mes
        btnMes.addEventListener('click', function() {
            const now = new Date();
            const firstDay = new Date(now.getFullYear(), now.getMonth(), 1);
            const lastDay = new Date(now.getFullYear(), now.getMonth() + 1, 0);
            
            fechaInicio.value = firstDay.toISOString().split('T')[0];
            fechaFin.value = lastDay.toISOString().split('T')[0];
        });

        // Filtro: Este trimestre
        btnTrimestre.addEventListener('click', function() {
            const now = new Date();
            const currentMonth = now.getMonth();
            const quarterStartMonth = Math.floor(currentMonth / 3) * 3;
            const quarterEndMonth = quarterStartMonth + 2;
            
            const firstDay = new Date(now.getFullYear(), quarterStartMonth, 1);
            const lastDay = new Date(now.getFullYear(), quarterEndMonth + 1, 0);
            
            fechaInicio.value = firstDay.toISOString().split('T')[0];
            fechaFin.value = lastDay.toISOString().split('T')[0];
        });

        // Limpiar filtros
        limpiarFiltros.addEventListener('click', function() {
            fechaInicio.value = '';
            fechaFin.value = '';
            document.getElementById('tipo_reporte').value = 'general';
            document.getElementById('finca_id').value = '';
        });

        // Validación de fechas
        document.getElementById('reporteForm').addEventListener('submit', function(e) {
            if (fechaInicio.value && fechaFin.value) {
                const inicio = new Date(fechaInicio.value);
                const fin = new Date(fechaFin.value);
                
                if (inicio > fin) {
                    e.preventDefault();
                    alert('La fecha de inicio no puede ser mayor que la fecha de fin.');
                    return;
                }
            }
        });

        // Inicializar gráficas si hay datos
        @if(isset($grafica_turnos) && array_sum($grafica_turnos) > 0)
            inicializarGraficaTurnos();
        @endif

        @if(isset($grafica_dietas) && count($grafica_dietas) > 0)
            inicializarGraficaDietas();
        @endif

        function inicializarGraficaTurnos() {
            const ctx = document.getElementById('graficaTurnos');
            if (!ctx) return;
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Mañana', 'Tarde', 'Noche'],
                    datasets: [{
                        data: [
                            {{ $grafica_turnos['mañana'] ?? 0 }},
                            {{ $grafica_turnos['tarde'] ?? 0 }},
                            {{ $grafica_turnos['noche'] ?? 0 }}
                        ],
                        backgroundColor: [
                            '#28a745',
                            '#ffc107',
                            '#343a40'
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        }
                    }
                }
            });
        }

        function inicializarGraficaDietas() {
        const ctx = document.getElementById('graficaDietas');
        if (!ctx) return;
        
        const dietasData = {!! json_encode($grafica_dietas) !!};
        const labels = Object.keys(dietasData);
        const data = Object.values(dietasData);
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Registros',
                    data: data,
                    backgroundColor: '#20c997',
                    borderColor: '#198754',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Cantidad de Registros'
                        }
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'Dietas'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
        }
    });
</script>

<style>
    .card {
        border-radius: 10px;
    }
    
    .card-header {
        border-radius: 10px 10px 0 0 !important;
    }
    
    .btn-group .btn {
        border-radius: 5px;
    }
    
    .table th {
        border-top: none;
        font-weight: 600;
    }
    
    #graficaTurnos, #graficaDietas {
        position: relative;
    }
</style>
@endsection