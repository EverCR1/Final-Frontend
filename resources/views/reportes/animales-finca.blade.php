@extends('layouts.app')

@section('title', 'Reporte - Animales por Finca')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cow text-success"></i> Reporte: Animales por Finca
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

    <!-- Estadísticas -->
    @if(count($animalesPorFinca) > 0)
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Fincas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($animalesPorFinca) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tractor fa-2x text-gray-300"></i>
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
                                Total Animales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ array_sum(array_column($animalesPorFinca, 'total_animales')) }}
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
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Promedio por Finca</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ round(array_sum(array_column($animalesPorFinca, 'total_animales')) / count($animalesPorFinca), 1) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
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
                                Finca con Más Animales</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ max(array_column($animalesPorFinca, 'total_animales')) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-star fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Tabla de Animales por Finca -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-list me-2"></i>Distribución de Animales por Finca
            </h6>
            <span class="badge bg-success">{{ count($animalesPorFinca) }} fincas</span>
        </div>
        <div class="card-body">
            @if(count($animalesPorFinca) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="animalesFincaTable" width="100%" cellspacing="0">
                    <thead class="table-success">
                        <tr>
                            <th>Finca</th>
                            <th>Ubicación</th>
                            <th>Zona</th>
                            <th>Total Animales</th>
                            <th>Porcentaje</th>
                            <th>Distribución</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalAnimales = array_sum(array_column($animalesPorFinca, 'total_animales'));
                        @endphp
                        @foreach($animalesPorFinca as $finca)
                        @php
                            $porcentaje = $totalAnimales > 0 ? ($finca['total_animales'] / $totalAnimales) * 100 : 0;
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $finca['finca'] }}</strong>
                            </td>
                            <td>{{ $finca['ubicacion'] ?? 'N/A' }}</td>
                            <td>
                                @php
                                    $zonaColors = [
                                        'norte' => 'primary',
                                        'sur' => 'info',
                                        'este' => 'warning'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $zonaColors[$finca['zona']] ?? 'secondary' }} text-capitalize">
                                    {{ $finca['zona'] ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-success fs-6">{{ $finca['total_animales'] }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ number_format($porcentaje, 1) }}%</span>
                            </td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-success" role="progressbar" 
                                         style="width: {{ $porcentaje }}%" 
                                         aria-valuenow="{{ $porcentaje }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ number_format($porcentaje, 1) }}%
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                    @if($totalAnimales > 0)
                    <tfoot class="table-dark">
                        <tr>
                            <td colspan="3" class="text-end"><strong>TOTAL GENERAL:</strong></td>
                            <td><strong class="text-white">{{ $totalAnimales }}</strong></td>
                            <td><strong class="text-white">100%</strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-cow fa-4x text-muted mb-3"></i>
                <h4 class="text-muted">No hay datos de fincas disponibles</h4>
                <p class="text-muted">No se encontraron fincas con animales registrados en el sistema.</p>
                <a href="{{ route('fincas.index') }}" class="btn btn-success">
                    <i class="fas fa-tractor me-2"></i>Gestionar Fincas
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Gráfico de Distribución -->
    @if(count($animalesPorFinca) > 0)
    <div class="row">
        <div class="col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-pie me-2"></i>Distribución por Zona
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $zonas = [];
                        foreach ($animalesPorFinca as $finca) {
                            $zona = $finca['zona'] ?? 'sin-zona';
                            if (!isset($zonas[$zona])) {
                                $zonas[$zona] = 0;
                            }
                            $zonas[$zona] += $finca['total_animales'];
                        }
                    @endphp
                    
                    @foreach($zonas as $zona => $total)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-capitalize">{{ $zona === 'sin-zona' ? 'Sin Zona' : $zona }}</span>
                            <span class="fw-bold">{{ $total }} animales</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            @php
                                $zonaPorcentaje = ($total / $totalAnimales) * 100;
                                $zonaColor = [
                                    'norte' => 'bg-primary',
                                    'sur' => 'bg-info',
                                    'este' => 'bg-warning',
                                    'sin-zona' => 'bg-secondary'
                                ][$zona] ?? 'bg-secondary';
                            @endphp
                            <div class="progress-bar {{ $zonaColor }}" role="progressbar" 
                                 style="width: {{ $zonaPorcentaje }}%" 
                                 aria-valuenow="{{ $zonaPorcentaje }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($zonaPorcentaje, 1) }}%
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
                        <i class="fas fa-chart-bar me-2"></i>Top 5 Fincas con Más Animales
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $topFincas = collect($animalesPorFinca)
                            ->sortByDesc('total_animales')
                            ->take(5);
                    @endphp
                    
                    @foreach($topFincas as $finca)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-truncate" title="{{ $finca['finca'] }}">
                                {{ Str::limit($finca['finca'], 25) }}
                            </span>
                            <span class="fw-bold text-success">{{ $finca['total_animales'] }}</span>
                        </div>
                        <div class="progress" style="height: 15px;">
                            @php
                                $fincaPorcentaje = ($finca['total_animales'] / $totalAnimales) * 100;
                            @endphp
                            <div class="progress-bar bg-success" role="progressbar" 
                                 style="width: {{ $fincaPorcentaje }}%" 
                                 aria-valuenow="{{ $fincaPorcentaje }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                            </div>
                        </div>
                    </div>
                    @endforeach
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
    }
    
    .progress {
        background-color: #e9ecef;
        border-radius: 0.375rem;
    }
</style>
@endsection