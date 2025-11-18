@extends('layouts.app')

@section('title', 'Reporte - Producción Mensual')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-milk-bottle text-info"></i> Reporte: Producción Mensual
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

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-filter me-2"></i>Filtros del Reporte
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('reportes.produccion-mensual') }}" id="filtroForm">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="year" class="form-label">Año</label>
                        <select class="form-select" id="year" name="year">
                            @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == $year ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="month" class="form-label">Mes</label>
                        <select class="form-select" id="month" name="month">
                            @foreach([
                                1 => 'Enero', 2 => 'Febrero', 3 => 'Marzo', 4 => 'Abril',
                                5 => 'Mayo', 6 => 'Junio', 7 => 'Julio', 8 => 'Agosto',
                                9 => 'Septiembre', 10 => 'Octubre', 11 => 'Noviembre', 12 => 'Diciembre'
                            ] as $num => $name)
                            <option value="{{ $num }}" {{ $num == $month ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-search me-2"></i>Generar Reporte
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas -->
    @if(isset($reporte['produccion_total']))
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Producción Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($reporte['produccion_total'], 2) }} L
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
                                Período</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ \Carbon\Carbon::create($year, $month, 1)->translatedFormat('F Y') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
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
                                Total Registros</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($reporte['detalles'] ?? []) }}
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
                                Fincas Activas</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ count($reporte['produccion_por_finca'] ?? []) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tractor fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Producción por Finca -->
    @if(isset($reporte['produccion_por_finca']) && count($reporte['produccion_por_finca']) > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-tractor me-2"></i>Producción por Finca
            </h6>
            <span class="badge bg-info">{{ count($reporte['produccion_por_finca']) }} fincas</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="produccionFincaTable" width="100%" cellspacing="0">
                    <thead class="table-info">
                        <tr>
                            <th>Finca</th>
                            <th>Total Leche (L)</th>
                            <th>Porcentaje</th>
                            <th>Promedio Grasa</th>
                            <th>Total Registros</th>
                            <th>Distribución</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporte['produccion_por_finca'] as $finca)
                        @php
                            $porcentaje = $reporte['produccion_total'] > 0 ? 
                                        ($finca['total_leche'] / $reporte['produccion_total']) * 100 : 0;
                        @endphp
                        <tr>
                            <td><strong>{{ $finca['finca'] }}</strong></td>
                            <td>
                                <span class="badge bg-success fs-6">{{ number_format($finca['total_leche'], 2) }} L</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ number_format($porcentaje, 1) }}%</span>
                            </td>
                            <td>
                                @if($finca['promedio_grasa'])
                                <span class="badge bg-info">{{ number_format($finca['promedio_grasa'], 1) }}%</span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>{{ $finca['total_registros'] }}</td>
                            <td>
                                <div class="progress" style="height: 20px;">
                                    <div class="progress-bar bg-info" role="progressbar" 
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
                </table>
            </div>
        </div>
    </div>
    @endif

    <!-- Detalles de Producción -->
    @if(isset($reporte['detalles']) && count($reporte['detalles']) > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Detalles de Producción
            </h6>
            <span class="badge bg-primary">{{ count($reporte['detalles']) }} registros</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="detallesTable" width="100%" cellspacing="0">
                    <thead class="table-primary">
                        <tr>
                            <th>Fecha</th>
                            <th>Animal</th>
                            <th>Finca</th>
                            <th>Cantidad (L)</th>
                            <th>Grasa</th>
                            <th>Proteína</th>
                            <th>Turno</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reporte['detalles'] as $produccion)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($produccion['fecha'])->format('d/m/Y') }}</td>
                            <td>
                                {{ $produccion['animal']['identificacion'] ?? 'N/A' }}
                                @if($produccion['animal']['nombre'] ?? false)
                                <br><small class="text-muted">{{ $produccion['animal']['nombre'] }}</small>
                                @endif
                            </td>
                            <td>{{ $produccion['animal']['finca']['nombre'] ?? 'N/A' }}</td>
                            <td class="text-success fw-bold">{{ $produccion['cantidad_leche'] }} L</td>
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
                                <span class="badge bg-secondary text-capitalize">{{ $produccion['turno'] }}</span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @elseif(isset($reporte['detalles']) && count($reporte['detalles']) === 0)
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No hay registros de producción</h4>
            <p class="text-muted">No se encontraron registros de producción para el período seleccionado.</p>
            <a href="{{ route('produccion-leche.create') }}" class="btn btn-info">
                <i class="fas fa-plus me-2"></i>Registrar Producción
            </a>
        </div>
    </div>
    @else
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-calendar fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Seleccione un período</h4>
            <p class="text-muted">Use los filtros de arriba para generar el reporte de producción mensual.</p>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover { 
        background-color: rgba(23, 162, 184, 0.1) !important; 
    }
</style>
@endsection