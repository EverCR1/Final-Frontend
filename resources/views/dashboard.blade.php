@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- PRIMERA FILA: Estadísticas Principales -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-animales text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Animales
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $data['estadisticas_generales']['total_animales'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-horse fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('animals.index') }}" class="text-white-50 small">
                    Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-fincas text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Fincas
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $data['estadisticas_generales']['total_fincas'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tractor fa-2x"></i>
                    </div>
                </div>
            </div>
            @if(session('user.role') === 'admin')
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('fincas.index') }}" class="text-white-50 small">
                    Gestionar <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-leche text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Producción Semanal (L)
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ number_format($data['estadisticas_generales']['produccion_semanal_leche'] ?? 0, 1) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wine-bottle fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('produccion-leche.index') }}" class="text-white-50 small">
                    Ver producción <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-salud text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Animales Enfermos
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $data['estadisticas_generales']['animales_enfermos'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-first-aid fa-2x"></i>
                    </div>
                </div>
            </div>
            @if(in_array(session('user.role'), ['admin', 'veterinario']))
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('animals.index') }}?estado=enfermo" class="text-white-50 small">
                    Atender <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- SEGUNDA FILA: Alertas y Acciones Rápidas -->
<div class="row">
    <!-- Alertas -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i> Alertas del Sistema
                </h6>
                <span class="badge bg-danger">{{ $totalAlertas = ($data['estadisticas_generales']['medicamentos_stock_bajo'] ?? 0) + ($data['estadisticas_generales']['animales_enfermos'] ?? 0) + ($data['estadisticas_generales']['vacunaciones_proximas'] ?? 0) }}</span>
            </div>
            <div class="card-body">
                @if(($data['estadisticas_generales']['medicamentos_stock_bajo'] ?? 0) > 0)
                <div class="alert alert-warning d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-pills me-2"></i>
                        <strong>{{ $data['estadisticas_generales']['medicamentos_stock_bajo'] }} medicamentos</strong> con stock bajo
                    </div>
                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                    <a href="{{ route('medicamentos.index') }}?stock=bajo" class="btn btn-warning btn-sm">
                        <i class="fas fa-box me-1"></i>Revisar Stock
                    </a>
                    @endif
                </div>
                @endif

                @if(($data['estadisticas_generales']['animales_enfermos'] ?? 0) > 0)
                <div class="alert alert-danger d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-heartbeat me-2"></i>
                        <strong>{{ $data['estadisticas_generales']['animales_enfermos'] }} animales</strong> requieren atención médica
                    </div>
                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                    <a href="{{ route('animals.index') }}?estado=enfermo" class="btn btn-danger btn-sm">
                        <i class="fas fa-stethoscope me-1"></i>Atender
                    </a>
                    @endif
                </div>
                @endif

                @if(($data['estadisticas_generales']['vacunaciones_proximas'] ?? 0) > 0)
                <div class="alert alert-info d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-syringe me-2"></i>
                        <strong>{{ $data['estadisticas_generales']['vacunaciones_proximas'] }} vacunaciones</strong> programadas para esta semana
                    </div>
                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                    <a href="{{ route('vacunaciones.index') }}" class="btn btn-info btn-sm">
                        <i class="fas fa-calendar me-1"></i>Ver Calendario
                    </a>
                    @endif
                </div>
                @endif

                @if(($data['estadisticas_generales']['medicamentos_vencidos'] ?? 0) > 0)
                <div class="alert alert-dark d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-clock me-2"></i>
                        <strong>{{ $data['estadisticas_generales']['medicamentos_vencidos'] }} medicamentos</strong> próximos a vencer
                    </div>
                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                    <a href="{{ route('medicamentos.index') }}?vencimiento=proximo" class="btn btn-dark btn-sm">
                        <i class="fas fa-exclamation-triangle me-1"></i>Revisar
                    </a>
                    @endif
                </div>
                @endif

                @if($totalAlertas == 0)
                <div class="alert alert-success text-center py-4">
                    <i class="fas fa-check-circle fa-2x mb-3"></i>
                    <h5 class="alert-heading">¡Excelente!</h5>
                    <p class="mb-0">No hay alertas críticas en este momento.</p>
                    <small class="text-muted">El sistema está funcionando correctamente.</small>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-bolt me-2"></i> Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('animals.create') }}" class="btn btn-success btn-block mb-2 w-100 text-start">
                    <i class="fas fa-plus-circle me-2"></i>Registrar Animal
                </a>
                <a href="{{ route('vacunaciones.create') }}" class="btn btn-info btn-block mb-2 w-100 text-start">
                    <i class="fas fa-syringe me-2"></i>Registrar Vacunación
                </a>
                <a href="{{ route('medicamentos.create') }}" class="btn btn-warning btn-block mb-2 w-100 text-start">
                    <i class="fas fa-pills me-2"></i>Agregar Medicamento
                </a>
                @endif
                
                <a href="{{ route('produccion-leche.create') }}" class="btn btn-primary btn-block mb-2 w-100 text-start">
                    <i class="fas fa-wine-bottle me-2"></i>Registrar Producción
                </a>
                
                @if(session('user.role') === 'admin')
                <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-block mb-2 w-100 text-start">
                    <i class="fas fa-chart-bar me-2"></i>Ver Reportes
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- TERCERA FILA: Gráfica de Producción -->
<div class="row">
    <div class="col-12 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-chart-line me-2"></i> Producción de Leche - Últimos 30 Días
                </h6>
            </div>
            <div class="card-body">
                <div class="chart-container" style="position: relative; height: 300px;">
                    <canvas id="produccionLecheChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CUARTA FILA: Información del Sistema -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle me-2"></i> Información del Sistema
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-user me-2 text-primary"></i>Usuario:</strong><br>
                        <span class="text-muted">{{ session('user.name') }}</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-user-tag me-2 text-warning"></i>Rol:</strong><br>
                        <span class="badge bg-{{ session('user.role') === 'admin' ? 'success' : (session('user.role') === 'veterinario' ? 'info' : 'secondary') }}">
                            {{ session('user.role') }}
                        </span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-envelope me-2 text-success"></i>Email:</strong><br>
                        <span class="text-muted">{{ session('user.email') }}</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-clock me-2 text-danger"></i>Último acceso:</strong><br>
                        <span class="text-muted">{{ date('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .stat-card {
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .stat-card .card-footer {
        border-top: 1px solid rgba(255,255,255,0.2);
    }
    
    .bg-pink {
        background: linear-gradient(45deg, #e83e8c, #ff6b9c);
    }
    
    .bg-animales {
        background: linear-gradient(45deg, #667eea, #764ba2);
    }
    
    .bg-fincas {
        background: linear-gradient(45deg, #f093fb, #f5576c);
    }
    
    .bg-leche {
        background: linear-gradient(45deg, #4facfe, #00f2fe);
    }
    
    .bg-salud {
        background: linear-gradient(45deg, #43e97b, #38f9d7);
    }
    
    .btn-block {
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-block:hover {
        transform: translateX(5px);
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Acceder a los datos desde la estructura correcta
        const datosProduccion = {
            fechas: {!! json_encode($data['estadisticas_generales']['grafica_produccion']['fechas'] ?? []) !!},
            litros: {!! json_encode($data['estadisticas_generales']['grafica_produccion']['litros'] ?? []) !!}
        };

        // Solo crear la gráfica si hay datos
        if (datosProduccion.fechas.length > 0 && datosProduccion.litros.length > 0) {
            const ctx = document.getElementById('produccionLecheChart').getContext('2d');
            const produccionChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: datosProduccion.fechas,
                    datasets: [{
                        label: 'Producción Diaria (Litros)',
                        data: datosProduccion.litros,
                        borderColor: '#28a745',
                        backgroundColor: 'rgba(40, 167, 69, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#28a745',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: true,
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(context) {
                                    return `Producción: ${context.parsed.y} litros`;
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Litros',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            },
                            ticks: {
                                callback: function(value) {
                                    return value + ' L';
                                }
                            }
                        },
                        x: {
                            title: {
                                display: true,
                                text: 'Fecha',
                                font: {
                                    weight: 'bold'
                                }
                            },
                            grid: {
                                color: 'rgba(0,0,0,0.1)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'nearest'
                    },
                    animation: {
                        duration: 1000,
                        easing: 'easeInOutQuart'
                    }
                }
            });

            // Agregar estadísticas debajo de la gráfica si están disponibles
            const totalPeriodo = {{ $data['estadisticas_generales']['grafica_produccion']['total_periodo'] ?? 0 }};
            const promedioDiario = {{ $data['estadisticas_generales']['grafica_produccion']['promedio_diario'] ?? 0 }};
            
            if (totalPeriodo > 0) {
                const statsHtml = `
                    <div class="row mt-3 text-center">
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-chart-bar me-1"></i>
                                <strong>Total período:</strong> ${totalPeriodo.toLocaleString()} litros
                            </small>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">
                                <i class="fas fa-calculator me-1"></i>
                                <strong>Promedio diario:</strong> ${promedioDiario.toLocaleString()} litros
                            </small>
                        </div>
                    </div>
                `;
                document.querySelector('#produccionLecheChart').closest('.card-body').insertAdjacentHTML('beforeend', statsHtml);
            }

        } else {
            // Mostrar mensaje si no hay datos
            document.querySelector('#produccionLecheChart').closest('.card-body').innerHTML = 
                '<div class="text-center py-5 text-muted">' +
                '<i class="fas fa-chart-line fa-3x mb-3"></i>' +
                '<h5>No hay datos de producción</h5>' +
                '<p class="mb-0">No se encontraron registros de producción para los últimos 30 días.</p>' +
                '<a href="{{ route("produccion-leche.create") }}" class="btn btn-primary mt-3">' +
                '<i class="fas fa-plus me-2"></i>Registrar Primera Producción' +
                '</a>' +
                '</div>';
        }

        // Debug: mostrar datos en consola
        console.log('Datos de producción para gráfica:', datosProduccion);
    });
</script>
@endsection