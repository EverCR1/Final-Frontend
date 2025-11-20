@extends('layouts.app')

@section('title', 'Detalle de la Finca')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info"></i> Detalle de la Finca
        </h1>
        <div>
            <a href="{{ route('fincas.edit', $finca['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            <a href="{{ route('fincas.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información de la Finca -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> {{ $finca['nombre'] }}</p>
                            <p><strong>Ubicación:</strong> {{ $finca['ubicacion'] }}</p>
                            <p><strong>Responsable:</strong> {{ $finca['responsable'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Zona:</strong> 
                                @php
                                    $zonaColors = [
                                        'norte' => 'primary',
                                        'sur' => 'info', 
                                        'este' => 'warning'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $zonaColors[$finca['zona']] ?? 'secondary' }} text-capitalize">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $finca['zona'] }}
                                </span>
                            </p>
                            <p><strong>Teléfono:</strong> 
                                {{ $finca['telefono'] ?: '<span class="text-muted">No especificado</span>' }}
                            </p>
                            <p><strong>Subred IP:</strong> 
                                <code class="{{ $finca['ip_subred'] ? 'text-success' : 'text-muted' }}">
                                    {{ $finca['ip_subred'] ?: 'No configurada' }}
                                </code>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Animales de esta Finca -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-cow me-2"></i>Animales en esta Finca
                        <span class="badge bg-success ms-2">{{ count($animales) }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($animales) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Identificación</th>
                                    <th>Nombre</th>
                                    <th>Especie</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($animales as $animal)
                                <tr>
                                    <td>
                                        <a href="{{ route('animals.show', $animal['id']) }}" class="text-decoration-none">
                                            <strong>{{ $animal['identificacion'] }}</strong>
                                        </a>
                                    </td>
                                    <td>{{ $animal['nombre'] ?: 'Sin nombre' }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark text-capitalize">
                                            {{ $animal['especie'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $badgeClass = [
                                                'activo' => 'bg-success',
                                                'enfermo' => 'bg-warning text-dark',
                                                'vendido' => 'bg-secondary',
                                                'muerto' => 'bg-danger'
                                            ][$animal['estado']] ?? 'bg-secondary';
                                        @endphp
                                        <span class="badge {{ $badgeClass }} text-capitalize">
                                            {{ $animal['estado'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-cow fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No hay animales registrados en esta finca</p>
                        <a href="{{ route('animals.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Registrar Animal
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Estadísticas -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <h4 class="text-success">{{ count($animales) }}</h4>
                        <p class="text-muted">Animales Totales</p>
                    </div>
                    
                    @if(count($animales) > 0)
                    <hr>
                    @php
                        $estados = collect($animales)->groupBy('estado')->map->count();
                        $especies = collect($animales)->groupBy('especie')->map->count();
                    @endphp
                    
                    <h6 class="text-muted">Por Estado:</h6>
                    @foreach($estados as $estado => $cantidad)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-capitalize">{{ $estado }}</span>
                        <span class="badge bg-secondary">{{ $cantidad }}</span>
                    </div>
                    @endforeach
                    
                    <hr>
                    <h6 class="text-muted">Por Especie:</h6>
                    @foreach($especies as $especie => $cantidad)
                    <div class="d-flex justify-content-between mb-1">
                        <span class="text-capitalize">{{ $especie }}</span>
                        <span class="badge bg-info text-dark">{{ $cantidad }}</span>
                    </div>
                    @endforeach
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
                        <a href="{{ route('animals.create') }}?finca_id={{ $finca['id'] }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-plus me-2"></i>Agregar Animal
                        </a>
                        <form action="{{ route('fincas.destroy', $finca['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar esta finca y todos sus animales?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Finca
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection