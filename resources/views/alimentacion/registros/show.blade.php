@extends('layouts.app')

@section('title', 'Detalle del Registro de Alimentación')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-primary"></i> Detalle del Registro de Alimentación
        </h1>
        <div>
            @if(in_array(session('user.role'), ['admin', 'veterinario']))
            <a href="{{ route('alimentacion.registros.edit', $registro['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            @endif
            <a href="{{ route('alimentacion.registros.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información del Registro -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong> 
                                <span class="text-primary fw-bold">
                                    {{ \Carbon\Carbon::parse($registro['fecha'])->format('d/m/Y') }}
                                </span>
                            </p>
                            <p><strong>Turno:</strong> 
                                @php
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
                                <span class="badge bg-{{ $turnoColors[$registro['turno']] ?? 'secondary' }} text-capitalize">
                                    <i class="fas {{ $turnoIcons[$registro['turno']] ?? 'fa-clock' }} me-1"></i>
                                    {{ $registro['turno'] }}
                                </span>
                            </p>
                            <p><strong>Finca:</strong> {{ $registro['finca']['nombre'] ?? 'N/A' }}</p>
                            <p><strong>Responsable:</strong> 
                                <span class="fw-bold text-info">
                                    <i class="fas fa-user me-1"></i>
                                    {{ $registro['responsable'] }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Tipo de Alimentación:</strong> 
                                @if($registro['animal_id'])
                                <span class="badge bg-info">
                                    <i class="fas fa-horse me-1"></i>Individual
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-users me-1"></i>Grupal
                                </span>
                                @endif
                            </p>
                            <p><strong>Cantidad Aplicada:</strong> 
                                <span class="fw-bold text-success">
                                    {{ $registro['cantidad_total'] }} kg
                                </span>
                            </p>
                            <p><strong>Costo Total:</strong> 
                                @if($registro['costo_total'])
                                <span class="fw-bold text-success">
                                    Q{{ number_format($registro['costo_total'], 2) }}
                                </span>
                                @else
                                <span class="text-muted">No calculado</span>
                                @endif
                            </p>
                            <p><strong>Costo por kg:</strong> 
                                @if($registro['costo_total'] && $registro['cantidad_total'])
                                <span class="text-muted">
                                    Q{{ number_format($registro['costo_total'] / $registro['cantidad_total'], 2) }}/kg
                                </span>
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Información del Animal -->
                    @if($registro['animal'])
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-horse me-2"></i>Información del Animal
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <strong>Identificación:</strong>
                                            <br>
                                            <span class="badge bg-primary">{{ $registro['animal']['identificacion'] }}</span>
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Nombre:</strong>
                                            <br>
                                            {{ $registro['animal']['nombre'] ?: 'Sin nombre' }}
                                        </div>
                                        <div class="col-md-4">
                                            <strong>Especie:</strong>
                                            <br>
                                            <span class="text-capitalize">{{ $registro['animal']['especie'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Información de la Dieta -->
                    @if($registro['dieta'])
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-utensils me-2"></i>Información de la Dieta
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <strong>Nombre:</strong>
                                            <br>
                                            {{ $registro['dieta']['nombre'] }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Tipo Animal:</strong>
                                            <br>
                                            <span class="badge bg-primary text-capitalize">
                                                {{ $registro['dieta']['tipo_animal'] }}
                                            </span>
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Categoría:</strong>
                                            <br>
                                            <span class="badge bg-info text-capitalize">
                                                {{ $registro['dieta']['categoria'] }}
                                            </span>
                                        </div>
                                    </div>
                                    @if($registro['dieta']['descripcion'])
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <strong>Descripción:</strong>
                                            <br>
                                            <span class="text-muted">{{ $registro['dieta']['descripcion'] }}</span>
                                        </div>
                                    </div>
                                    @endif
                                    @if($registro['dieta']['costo_estimado_kg'])
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <strong>Costo Estimado:</strong>
                                            <br>
                                            <span class="text-success fw-bold">
                                                Q{{ number_format($registro['dieta']['costo_estimado_kg'], 2) }}/kg
                                            </span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Observaciones -->
                    @if($registro['observaciones'])
                    <div class="row mt-4">
                        <div class="col-12">
                            <p><strong>Observaciones:</strong></p>
                            <div class="border rounded p-3 bg-light">
                                {{ $registro['observaciones'] }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Composición de la Dieta -->
            @if($registro['dieta'] && isset($registro['dieta']['alimentos']) && count($registro['dieta']['alimentos']) > 0)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-apple-alt me-2"></i>Composición de la Dieta Aplicada
                        <span class="badge bg-warning ms-2">{{ count($registro['dieta']['alimentos']) }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-warning">
                                <tr>
                                    <th>Alimento</th>
                                    <th>Tipo</th>
                                    <th>Cantidad en Dieta</th>
                                    <th>Frecuencia</th>
                                    <th>Cantidad Aplicada</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($registro['dieta']['alimentos'] as $alimento)
                                @php
                                    $cantidadDieta = $alimento['pivot']['cantidad'] ?? $alimento['cantidad'] ?? 0;
                                    $cantidadAplicada = $cantidadDieta * $registro['cantidad_total'];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-apple-alt text-warning me-2"></i>
                                            <div>
                                                <strong>{{ $alimento['nombre'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $alimento['unidad_medida'] }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary text-capitalize">
                                            {{ $alimento['tipo'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">
                                            {{ $cantidadDieta }} {{ $alimento['unidad_medida'] }}
                                        </strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $alimento['pivot']['frecuencia'] ?? $alimento['frecuencia'] ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        <strong class="text-success">
                                            {{ number_format($cantidadAplicada, 2) }} {{ $alimento['unidad_medida'] }}
                                        </strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Estadísticas del Registro -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">{{ $registro['cantidad_total'] }}</h2>
                        <p class="text-muted">Kilogramos Aplicados</p>
                    </div>
                    
                    <!-- Eficiencia de Costo -->
                    <hr>
                    <h6 class="text-muted mb-3">Análisis de Costo:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Costo Total:</span>
                        <span class="badge bg-success">Q{{ number_format($registro['costo_total'] ?? 0, 2) }}</span>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Costo por kg:</span>
                        <span class="badge bg-info">
                            Q{{ number_format(($registro['costo_total'] ?? 0) / max($registro['cantidad_total'], 1), 2) }}
                        </span>
                    </div>

                    <!-- Información de Uso -->
                    <hr>
                    <h6 class="text-muted mb-3">Información de Uso:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tipo:</span>
                        @if($registro['animal_id'])
                        <span class="badge bg-info">Individual</span>
                        @else
                        <span class="badge bg-secondary">Grupal</span>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Alimentos en Dieta:</span>
                        <span class="badge bg-warning text-dark">
                            {{ count($registro['dieta']['alimentos'] ?? []) }}
                        </span>
                    </div>

                    <!-- Estado de la Dieta -->
                    <hr>
                    <h6 class="text-muted mb-3">Estado de la Dieta:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Dieta Activa:</span>
                        @if($registro['dieta']['activa'] ?? false)
                        <span class="badge bg-success">Sí</span>
                        @else
                        <span class="badge bg-danger">No</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas -->
            @if(in_array(session('user.role'), ['admin', 'veterinario']))
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('alimentacion.registros.edit', $registro['id']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Registro
                        </a>
                        <a href="{{ route('alimentacion.registros.create') }}?dieta_id={{ $registro['dieta_id'] }}&animal_id={{ $registro['animal_id'] }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-copy me-2"></i>Repetir Registro
                        </a>
                        @if(session('user.role') === 'admin')
                        <form action="{{ route('alimentacion.registros.destroy', $registro['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar este registro de alimentación?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Registro
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Información Adicional -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-history me-2"></i>Información Adicional
                    </h6>
                </div>
                <div class="card-body">
                    <p class="mb-2">
                        <strong>Registro Creado:</strong> 
                        <br>
                        {{ \Carbon\Carbon::parse($registro['created_at'])->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-0">
                        <strong>Última Actualización:</strong>
                        <br>
                        {{ \Carbon\Carbon::parse($registro['updated_at'])->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- Alertas Importantes -->
            @if(!($registro['dieta']['activa'] ?? true))
            <div class="card shadow mt-4 border-warning">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alerta Importante
                    </h6>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-toggle-off me-2"></i>
                        <strong>Dieta Inactiva:</strong> La dieta utilizada en este registro está inactiva.
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection