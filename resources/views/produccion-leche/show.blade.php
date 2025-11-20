@extends('layouts.app')

@section('title', 'Detalle de Producción de Leche')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info"></i> Detalle de Producción de Leche
        </h1>
        <div>
            <a href="{{ route('produccion-leche.edit', $produccion['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            <a href="{{ route('produccion-leche.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información de la Producción -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Información de la Producción
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Fecha:</strong> 
                                <strong>{{ \Carbon\Carbon::parse($produccion['fecha'])->format('d/m/Y') }}</strong>
                            </p>
                            <p><strong>Animal:</strong> 
                                {{ $produccion['animal']['identificacion'] ?? 'N/A' }}
                                @if($produccion['animal']['nombre'] ?? false)
                                - {{ $produccion['animal']['nombre'] }}
                                @endif
                            </p>
                            <p><strong>Finca:</strong> 
                                {{ $produccion['animal']['finca']['nombre'] ?? 'N/A' }}
                            </p>
                            <p><strong>Cantidad de Leche:</strong> 
                                <span class="badge bg-success fs-6">{{ $produccion['cantidad_leche'] }} litros</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Turno:</strong> 
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
                            </p>
                            <p><strong>Calidad de Grasa:</strong> 
                                @if($produccion['calidad_grasa'])
                                <span class="badge bg-info">{{ $produccion['calidad_grasa'] }}%</span>
                                @else
                                <span class="text-muted">No registrada</span>
                                @endif
                            </p>
                            <p><strong>Calidad de Proteína:</strong> 
                                @if($produccion['calidad_proteina'])
                                <span class="badge bg-primary">{{ $produccion['calidad_proteina'] }}%</span>
                                @else
                                <span class="text-muted">No registrada</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($produccion['observaciones'])
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Observaciones:</strong></p>
                            <div class="border rounded p-3 bg-light">
                                {{ $produccion['observaciones'] }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Información del Animal -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-cow me-2"></i>Información del Animal
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($produccion['animal']))
                    <p><strong>Identificación:</strong> {{ $produccion['animal']['identificacion'] }}</p>
                    <p><strong>Especie:</strong> 
                        <span class="badge bg-info text-capitalize">{{ $produccion['animal']['especie'] }}</span>
                    </p>
                    <p><strong>Raza:</strong> {{ $produccion['animal']['raza'] }}</p>
                    <p><strong>Sexo:</strong> 
                        <span class="badge bg-secondary text-capitalize">{{ $produccion['animal']['sexo'] }}</span>
                    </p>
                    <p><strong>Estado:</strong> 
                        @php
                            $estadoColors = [
                                'activo' => 'success',
                                'enfermo' => 'warning',
                                'vendido' => 'secondary',
                                'muerto' => 'danger'
                            ];
                        @endphp
                        <span class="badge bg-{{ $estadoColors[$produccion['animal']['estado']] ?? 'secondary' }} text-capitalize">
                            {{ $produccion['animal']['estado'] }}
                        </span>
                    </p>
                    <hr>
                    <a href="{{ route('animals.show', $produccion['animal']['id']) }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-eye me-2"></i>Ver Animal
                    </a>
                    @else
                    <p class="text-muted">Información del animal no disponible</p>
                    @endif
                </div>
            </div>

            <!-- Indicadores de Calidad -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>Indicadores de Calidad
                    </h6>
                </div>
                <div class="card-body">
                    @if($produccion['calidad_grasa'] || $produccion['calidad_proteina'])
                        @if($produccion['calidad_grasa'])
                        <div class="mb-3">
                            <label class="form-label">Grasa: {{ $produccion['calidad_grasa'] }}%</label>
                            <div class="progress" style="height: 20px;">
                                @php
                                    $grasaColor = $produccion['calidad_grasa'] >= 3.5 ? 'bg-success' : 
                                                ($produccion['calidad_grasa'] >= 2.5 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="progress-bar {{ $grasaColor }}" role="progressbar" 
                                     style="width: {{ min($produccion['calidad_grasa'] * 20, 100) }}%" 
                                     aria-valuenow="{{ $produccion['calidad_grasa'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="5">
                                    {{ $produccion['calidad_grasa'] }}%
                                </div>
                            </div>
                            <small class="text-muted">Óptimo: 3.5% - 5%</small>
                        </div>
                        @endif

                        @if($produccion['calidad_proteina'])
                        <div class="mb-3">
                            <label class="form-label">Proteína: {{ $produccion['calidad_proteina'] }}%</label>
                            <div class="progress" style="height: 20px;">
                                @php
                                    $proteinaColor = $produccion['calidad_proteina'] >= 3.2 ? 'bg-success' : 
                                                   ($produccion['calidad_proteina'] >= 2.8 ? 'bg-warning' : 'bg-danger');
                                @endphp
                                <div class="progress-bar {{ $proteinaColor }}" role="progressbar" 
                                     style="width: {{ min($produccion['calidad_proteina'] * 25, 100) }}%" 
                                     aria-valuenow="{{ $produccion['calidad_proteina'] }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="4">
                                    {{ $produccion['calidad_proteina'] }}%
                                </div>
                            </div>
                            <small class="text-muted">Óptimo: 3.2% - 4%</small>
                        </div>
                        @endif
                    @else
                    <p class="text-muted text-center">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay datos de calidad registrados
                    </p>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('produccion-leche.edit', $produccion['id']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Producción
                        </a>
                        <form action="{{ route('produccion-leche.destroy', $produccion['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar este registro de producción?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Producción
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection