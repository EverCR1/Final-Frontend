@extends('layouts.app')

@section('title', 'Reportes de Producción de Leche')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar text-info"></i> Reportes de Producción de Leche
        </h1>
        <a href="{{ route('produccion-leche.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver al Listado</span>
        </a>
    </div>

    <!-- Formulario de Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-filter me-2"></i>Filtros del Reporte
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('produccion-leche.generar-reporte') }}" id="reporteForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha Inicio <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="fecha_inicio" name="fecha_inicio" 
                               value="{{ $filtros['fecha_inicio'] ?? old('fecha_inicio', date('Y-m-01')) }}" 
                               required>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha Fin <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="fecha_fin" name="fecha_fin" 
                               value="{{ $filtros['fecha_fin'] ?? old('fecha_fin', date('Y-m-d')) }}" 
                               required>
                    </div>

                    <div class="col-md-2 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-info w-100">
                            <i class="fas fa-search me-2"></i>Generar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Estadísticas del Reporte -->
    @if(isset($estadisticas))
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Leche Producida</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($estadisticas['total_leche'] ?? 0, 2) }} L
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
                                {{ number_format($estadisticas['promedio_grasa'] ?? 0, 2) }}%
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
                                Total Registros</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Período</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                {{ \Carbon\Carbon::parse($filtros['fecha_inicio'])->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($filtros['fecha_fin'])->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Resultados del Reporte -->
    @if(isset($producciones) && count($producciones) > 0)
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-list me-2"></i>Resultados del Reporte
            </h6>
            <span class="badge bg-info">{{ count($producciones) }} registros</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="reporteTable" width="100%" cellspacing="0">
                    <thead class="table-info">
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
                        @foreach($producciones as $produccion)
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
    @elseif(isset($producciones) && count($producciones) === 0)
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-search fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No hay registros en el período seleccionado</h4>
            <p class="text-muted">Intente con otras fechas</p>
        </div>
    </div>
    @else
    <div class="card shadow mb-4">
        <div class="card-body text-center py-5">
            <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Seleccione un período para generar el reporte</h4>
            <p class="text-muted">Use los filtros de arriba para ver las estadísticas de producción</p>
        </div>
    </div>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fechaInicio = document.getElementById('fecha_inicio');
        const fechaFin = document.getElementById('fecha_fin');

        // Validar que fecha fin sea mayor o igual a fecha inicio
        function validarFechas() {
            if (fechaInicio.value && fechaFin.value) {
                const inicio = new Date(fechaInicio.value);
                const fin = new Date(fechaFin.value);
                
                if (fin < inicio) {
                    fechaFin.classList.add('is-invalid');
                    return false;
                } else {
                    fechaFin.classList.remove('is-invalid');
                    return true;
                }
            }
            return true;
        }

        fechaInicio.addEventListener('change', validarFechas);
        fechaFin.addEventListener('change', validarFechas);

        // Validación del formulario
        document.getElementById('reporteForm').addEventListener('submit', function(e) {
            if (!validarFechas()) {
                e.preventDefault();
                alert('La fecha fin debe ser mayor o igual a la fecha inicio.');
            }
        });
    });
</script>

<style>
    .is-invalid {
        border-color: #dc3545 !important;
    }
</style>
@endsection