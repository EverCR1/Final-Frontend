@extends('layouts.app')

@section('title', 'Módulo de Alimentación')

@section('content')
<!-- PRIMERA FILA: Estadísticas Principales del Módulo -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-alimentos text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Alimentos
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ count($data['alimentos'] ?? []) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-apple-alt fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('alimentacion.alimentos.index') }}" class="text-white-50 small">
                    Gestionar <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-dietas text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Dietas Activas
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ collect($data['dietas'] ?? [])->where('activa', true)->count() }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-utensils fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('alimentacion.dietas.index') }}" class="text-white-50 small">
                    Ver dietas <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-registros text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Registros Hoy
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{-- CORRECCIÓN: Debug y comparación flexible --}}
                            @php
                                $hoy = now()->format('Y-m-d');
                                $registrosHoy = collect($data['registros'] ?? [])
                                    ->filter(function($registro) use ($hoy) {
                                        // Intentar diferentes formatos de fecha
                                        $fechaRegistro = $registro['fecha'];
                                        
                                        // Si es un string, convertirlo
                                        if (is_string($fechaRegistro)) {
                                            $fechaFormateada = \Carbon\Carbon::parse($fechaRegistro)->format('Y-m-d');
                                            return $fechaFormateada === $hoy;
                                        }
                                        
                                        return false;
                                    })
                                    ->count();
                            @endphp
                            {{ $registrosHoy }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
                <a href="{{ route('alimentacion.registros.index') }}" class="text-white-50 small">
                    Ver registros <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-alertas text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Stock Bajo
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ count($data['stockBajo'] ?? []) }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-transparent border-0 pt-0">
            </div>
        </div>
    </div>
</div>

<!-- SEGUNDA FILA: Alertas y Acciones Rápidas -->
<div class="row">
    <!-- Alertas de Alimentación -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i> Alertas de Alimentación
                </h6>
                <span class="badge bg-danger">{{ count($data['stockBajo'] ?? []) }}</span>
            </div>
            <div class="card-body">
                @if(count($data['stockBajo'] ?? []) > 0)
                    @foreach($data['stockBajo'] as $alimento)
                    <div class="alert alert-warning d-flex align-items-center justify-content-between mb-3">
                        <div class="flex-grow-1">
                            <i class="fas fa-apple-alt me-2"></i>
                            <strong>{{ $alimento['nombre'] }}</strong> - 
                            Stock: {{ $alimento['stock_actual'] }} {{ $alimento['unidad_medida'] }} 
                            (Mínimo: {{ $alimento['stock_minimo'] }})
                            @if($alimento['finca'])
                                <br><small class="text-muted">Finca: {{ $alimento['finca']['nombre'] }}</small>
                            @endif
                        </div>
                        <a href="{{ route('alimentacion.alimentos.edit', $alimento['id']) }}" class="btn btn-warning btn-sm ms-3">
                            <i class="fas fa-edit me-1"></i>Reponer
                        </a>
                    </div>
                    @endforeach
                @else
                <div class="alert alert-success text-center py-4">
                    <i class="fas fa-check-circle fa-2x mb-3"></i>
                    <h5 class="alert-heading">¡Excelente!</h5>
                    <p class="mb-0">No hay alimentos con stock bajo.</p>
                    <small class="text-muted">Todos los inventarios están en niveles adecuados.</small>
                </div>
                @endif

                <!-- Alertas de dietas inactivas -->
                @php
                    $dietasInactivas = collect($data['dietas'] ?? [])->where('activa', false)->count();
                @endphp
                @if($dietasInactivas > 0)
                <div class="alert alert-info d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-utensils me-2"></i>
                        <strong>{{ $dietasInactivas }} dietas</strong> están inactivas
                    </div>
                    <a href="{{ route('alimentacion.dietas.index') }}?activa=false" class="btn btn-info btn-sm">
                        <i class="fas fa-eye me-1"></i>Revisar
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Acciones Rápidas del Módulo -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-bolt me-2"></i> Acciones Rápidas
                </h6>
            </div>
            <div class="card-body">
                <a href="{{ route('alimentacion.registros.create') }}" class="btn btn-success btn-block mb-2 w-100 text-start">
                    <i class="fas fa-plus-circle me-2"></i>Registrar Alimentación
                </a>
                <a href="{{ route('alimentacion.alimentos.create') }}" class="btn btn-info btn-block mb-2 w-100 text-start">
                    <i class="fas fa-apple-alt me-2"></i>Agregar Alimento
                </a>
                <a href="{{ route('alimentacion.dietas.create') }}" class="btn btn-warning btn-block mb-2 w-100 text-start">
                    <i class="fas fa-utensils me-2"></i>Crear Dieta
                </a>
                <a href="{{ route('alimentacion.reportes') }}" class="btn btn-secondary btn-block mb-2 w-100 text-start">
                    <i class="fas fa-chart-bar me-2"></i>Reportes de Alimentación
                </a>
            </div>
        </div>
    </div>
</div>

<!-- TERCERA FILA: Últimos Registros y Alimentos Populares -->
<div class="row">
    <!-- Últimos Registros de Alimentación -->
    <div class="col-lg-8 mb-4">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-history me-2"></i> Últimos Registros de Alimentación
                </h6>
            </div>
            <div class="card-body">
                @if(count($data['registros'] ?? []) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Dieta</th>
                                    <th>Cantidad</th>
                                    <th>Responsable</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($data['registros'], 0, 5) as $registro)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}</td>
                                    <td>
                                        @if($registro['dieta'])
                                            {{ $registro['dieta']['nombre'] }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $registro['cantidad_total'] }} kg</td>
                                    <td>{{ $registro['responsable'] }}</td>
                                    <td>
                                        <a href="{{ route('alimentacion.registros.show', $registro['id']) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-center mt-3">
                        <a href="{{ route('alimentacion.registros.index') }}" class="btn btn-outline-success btn-sm">
                            Ver todos los registros <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-clipboard-list fa-3x mb-3"></i>
                    <h5>No hay registros de alimentación</h5>
                    <p class="mb-3">Aún no se han registrado alimentaciones en el sistema.</p>
                    <a href="{{ route('alimentacion.registros.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Registrar Primera Alimentación
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Alimentos con Stock Saludable -->
    <div class="col-lg-4 mb-4">
        <div class="card shadow">
            <div class="card-header bg-info text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-apple-alt me-2"></i> Alimentos con Buen Stock
                </h6>
            </div>
            <div class="card-body">
                @php
                    $alimentosBuenStock = collect($data['alimentos'] ?? [])
                        ->where('stock_actual', '>', 0)
                        ->sortByDesc('stock_actual')
                        ->take(5);
                @endphp
                
                @if($alimentosBuenStock->count() > 0)
                    @foreach($alimentosBuenStock as $alimento)
                    <div class="d-flex justify-content-between align-items-center mb-3 p-2 border rounded">
                        <div>
                            <strong class="d-block">{{ $alimento['nombre'] }}</strong>
                            <small class="text-muted">
                                Stock: {{ $alimento['stock_actual'] }} {{ $alimento['unidad_medida'] }}
                            </small>
                        </div>
                        <span class="badge bg-success">
                            {{ number_format(($alimento['stock_actual'] / max($alimento['stock_minimo'], 1)) * 100, 0) }}%
                        </span>
                    </div>
                    @endforeach
                @else
                <div class="text-center py-3 text-muted">
                    <i class="fas fa-apple-alt fa-2x mb-2"></i>
                    <p class="mb-0">No hay alimentos registrados</p>
                </div>
                @endif
                
                <div class="text-center mt-3">
                    <a href="{{ route('alimentacion.alimentos.index') }}" class="btn btn-outline-info btn-sm">
                        Ver inventario completo <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CUARTA FILA: Resumen del Módulo -->
<div class="row">
    <div class="col-12">
        <div class="card shadow">
            <div class="card-header bg-secondary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle me-2"></i> Resumen del Módulo de Alimentación
                </h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-apple-alt me-2 text-success"></i>Total Alimentos:</strong><br>
                        <span class="h5 text-success">{{ count($data['alimentos'] ?? []) }}</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-utensils me-2 text-primary"></i>Dietas Activas:</strong><br>
                        <span class="h5 text-primary">{{ collect($data['dietas'] ?? [])->where('activa', true)->count() }}</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-clipboard-list me-2 text-warning"></i>Registros Totales:</strong><br>
                        <span class="h5 text-warning">{{ count($data['registros'] ?? []) }}</span>
                    </div>
                    <div class="col-md-3 mb-3">
                        <strong><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Alertas Activas:</strong><br>
                        <span class="h5 text-danger">{{ count($data['stockBajo'] ?? []) }}</span>
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
    
    .bg-alimentos {
        background: linear-gradient(45deg, #ff9a9e, #fad0c4);
    }
    
    .bg-dietas {
        background: linear-gradient(45deg, #a1c4fd, #c2e9fb);
    }
    
    .bg-registros {
        background: linear-gradient(45deg, #ffecd2, #fcb69f);
    }
    
    .bg-alertas {
        background: linear-gradient(45deg, #ff9a9e, #fecfef);
    }
    
    .btn-block {
        padding: 12px 15px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }
    
    .btn-block:hover {
        transform: translateX(5px);
    }
    
    .table-sm th, .table-sm td {
        padding: 0.75rem 0.5rem;
    }
</style>
@endsection