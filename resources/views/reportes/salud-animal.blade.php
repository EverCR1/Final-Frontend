@extends('layouts.app')

@section('title', 'Reporte - Salud Animal')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-heartbeat text-warning"></i> Reporte: Salud Animal
        </h1>
        <div>
            <a href="{{ route('reportes.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver a Reportes</span>
            </a>
        </div>
    </div>

    <!-- Estadísticas de Salud -->
    @if(isset($reporteSalud['distribucion_estados']))
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Animales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reporteSalud['distribucion_estados']->sum('total') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cow fa-2x text-gray-300"></i>
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
                                Animales Activos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reporteSalud['distribucion_estados']->where('estado', 'activo')->first()->total ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Animales Enfermos</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reporteSalud['distribucion_estados']->where('estado', 'enfermo')->first()->total ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
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
                                Vacunaciones (30 días)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $reporteSalud['total_vacunaciones_30dias'] ?? 0 }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-syringe fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Distribución de Estados -->
    @if(isset($reporteSalud['distribucion_estados']))
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-chart-pie me-2"></i>Distribución por Estado de Salud
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $totalAnimales = $reporteSalud['distribucion_estados']->sum('total');
                    @endphp
                    
                    @foreach($reporteSalud['distribucion_estados'] as $estado)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-capitalize fw-bold">{{ $estado['estado'] }}</span>
                            <span>{{ $estado['total'] }} animales</span>
                        </div>
                        <div class="progress" style="height: 25px;">
                            @php
                                $porcentaje = ($estado['total'] / $totalAnimales) * 100;
                                $estadoColor = [
                                    'activo' => 'bg-success',
                                    'enfermo' => 'bg-warning',
                                    'vendido' => 'bg-secondary',
                                    'muerto' => 'bg-danger'
                                ][$estado['estado']] ?? 'bg-info';
                            @endphp
                            <div class="progress-bar {{ $estadoColor }} progress-bar-striped" role="progressbar" 
                                 style="width: {{ $porcentaje }}%" 
                                 aria-valuenow="{{ $porcentaje }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                <span class="fw-bold">{{ number_format($porcentaje, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-chart-bar me-2"></i>Resumen de Estados
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-success">
                                <tr>
                                    <th>Estado</th>
                                    <th>Cantidad</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reporteSalud['distribucion_estados'] as $estado)
                                @php
                                    $porcentaje = ($estado['total'] / $totalAnimales) * 100;
                                @endphp
                                <tr>
                                    <td class="text-capitalize">{{ $estado['estado'] }}</td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $estado['total'] }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-primary">{{ number_format($porcentaje, 1) }}%</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-dark">
                                <tr>
                                    <td><strong>TOTAL</strong></td>
                                    <td><strong class="text-white">{{ $totalAnimales }}</strong></td>
                                    <td><strong class="text-white">100%</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Vacunaciones Recientes -->
    @if(isset($reporteSalud['vacunaciones_recientes']) && count($reporteSalud['vacunaciones_recientes']) > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-syringe me-2"></i>Vacunaciones Recientes (Últimos 30 días)
            </h6>
            <span class="badge bg-info">{{ count($reporteSalud['vacunaciones_recientes']) }} registros</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="vacunacionesTable" width="100%" cellspacing="0">
                    <thead class="table-info">
                        <tr>
                            <th>Fecha</th>
                            <th>Animal</th>
                            <th>Finca</th>
                            <th>Medicamento</th>
                            <th>Vacuna</th>
                            <th>Veterinario</th>
                            <th>Próxima</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporteSalud['vacunaciones_recientes'] as $vacunacion)
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($vacunacion['fecha_vacunacion'])->format('d/m/Y') }}</strong>
                            </td>
                            <td>
                                {{ $vacunacion['animal']['identificacion'] ?? 'N/A' }}
                                @if($vacunacion['animal']['nombre'] ?? false)
                                <br><small class="text-muted">{{ $vacunacion['animal']['nombre'] }}</small>
                                @endif
                            </td>
                            <td>{{ $vacunacion['animal']['finca']['nombre'] ?? 'N/A' }}</td>
                            <td>{{ $vacunacion['medicamento']['nombre'] ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-primary">{{ $vacunacion['vacuna'] }}</span>
                            </td>
                            <td>{{ $vacunacion['veterinario'] }}</td>
                            <td>
                                @if($vacunacion['fecha_proxima'])
                                    @php
                                        $hoy = now();
                                        $proxima = \Carbon\Carbon::parse($vacunacion['fecha_proxima']);
                                        $diasRestantes = $hoy->diffInDays($proxima, false);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="text-danger" title="Vencida">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $proxima->format('d/m/Y') }}
                                        </span>
                                    @elseif($diasRestantes <= 7)
                                        <span class="text-warning" title="Próxima">
                                            <i class="fas fa-clock"></i> {{ $proxima->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-success">{{ $proxima->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @elseif(isset($reporteSalud['vacunaciones_recientes']) && count($reporteSalud['vacunaciones_recientes']) === 0)
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="text-center py-4">
                <i class="fas fa-syringe fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No hay vacunaciones recientes</h4>
                <p class="text-muted">No se encontraron registros de vacunación en los últimos 30 días.</p>
                <a href="{{ route('vacunaciones.create') }}" class="btn btn-info">
                    <i class="fas fa-plus me-2"></i>Registrar Vacunación
                </a>
            </div>
        </div>
    </div>
    @endif

    <!-- Alertas de Salud -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alertas de Salud
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $animalesEnfermos = $reporteSalud['distribucion_estados']->where('estado', 'enfermo')->first()->total ?? 0;
                        $vacunacionesProximas = collect($reporteSalud['vacunaciones_recientes'] ?? [])
                            ->filter(function($vac) {
                                if (!$vac['fecha_proxima']) return false;
                                $proxima = \Carbon\Carbon::parse($vac['fecha_proxima']);
                                return $proxima->diffInDays(now(), false) <= 7 && $proxima->diffInDays(now(), false) >= 0;
                            })->count();
                    @endphp

                    @if($animalesEnfermos > 0)
                    <div class="alert alert-danger d-flex align-items-center mb-3">
                        <i class="fas fa-heartbeat fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">{{ $animalesEnfermos }} Animal(es) Enfermo(s)</h5>
                            <p class="mb-0">Es necesario revisar el estado de salud de estos animales.</p>
                            <a href="{{ route('animals.index') }}?estado=enfermo" class="btn btn-danger btn-sm mt-2">
                                <i class="fas fa-eye me-2"></i>Ver Animales Enfermos
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($vacunacionesProximas > 0)
                    <div class="alert alert-warning d-flex align-items-center mb-3">
                        <i class="fas fa-clock fa-2x me-3"></i>
                        <div>
                            <h5 class="alert-heading">{{ $vacunacionesProximas }} Vacunación(es) Próxima(s)</h5>
                            <p class="mb-0">Programe las próximas vacunaciones para mantener la salud del ganado.</p>
                            <a href="{{ route('vacunaciones.index') }}" class="btn btn-warning btn-sm mt-2">
                                <i class="fas fa-calendar me-2"></i>Ver Vacunaciones
                            </a>
                        </div>
                    </div>
                    @endif

                    @if($animalesEnfermos == 0 && $vacunacionesProximas == 0)
                    <div class="alert alert-success text-center">
                        <i class="fas fa-check-circle fa-2x me-2"></i>
                        <strong>¡Excelente!</strong> No hay alertas de salud activas en este momento.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover { 
        background-color: rgba(255, 193, 7, 0.1) !important; 
    }
    
    .progress-bar-striped {
        background-image: linear-gradient(45deg, rgba(255,255,255,0.15) 25%, transparent 25%, transparent 50%, rgba(255,255,255,0.15) 50%, rgba(255,255,255,0.15) 75%, transparent 75%, transparent);
        background-size: 1rem 1rem;
    }
</style>
@endsection