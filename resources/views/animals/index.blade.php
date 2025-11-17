@extends('layouts.app')

@section('title', 'Gestión de Animales')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-cow text-success"></i> Gestión de Animales
        </h1>
        <a href="{{ route('animals.create') }}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nuevo Animal</span>
        </a>
    </div>

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Animals Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-list me-2"></i>Listado de Animales
            </h6>
            <span class="badge bg-success">{{ count($animals) }} animales registrados</span>
        </div>
        <div class="card-body">
            @if(count($animals) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="animalsTable" width="100%" cellspacing="0">
                    <thead class="table-success">
                        <tr>
                            <th>ID</th>
                            <th>Identificación</th>
                            <th>Nombre</th>
                            <th>Especie</th>
                            <th>Raza</th>
                            <th>Sexo</th>
                            <th>Estado</th>
                            <th>Finca</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($animals as $animal)
                        <tr>
                            <td><strong>#{{ $animal['id'] }}</strong></td>
                            <td>
                                <span class="badge bg-primary">{{ $animal['identificacion'] }}</span>
                            </td>
                            <td>
                                @if($animal['nombre'])
                                <i class="fas fa-tag me-1 text-muted"></i>{{ $animal['nombre'] }}
                                @else
                                <span class="text-muted">Sin nombre</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info text-dark text-capitalize">
                                    <i class="fas fa-paw me-1"></i>{{ $animal['especie'] }}
                                </span>
                            </td>
                            <td>{{ $animal['raza'] }}</td>
                            <td>
                                @if($animal['sexo'] == 'macho')
                                <span class="badge bg-primary">
                                    <i class="fas fa-mars me-1"></i>Macho
                                </span>
                                @else
                                <span class="badge bg-pink text-white">
                                    <i class="fas fa-venus me-1"></i>Hembra
                                </span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $badgeClass = [
                                        'activo' => 'bg-success',
                                        'enfermo' => 'bg-warning text-dark',
                                        'vendido' => 'bg-secondary',
                                        'muerto' => 'bg-danger'
                                    ][$animal['estado']] ?? 'bg-secondary';
                                    
                                    $icon = [
                                        'activo' => 'fa-check-circle',
                                        'enfermo' => 'fa-first-aid',
                                        'vendido' => 'fa-dollar-sign',
                                        'muerto' => 'fa-skull'
                                    ][$animal['estado']] ?? 'fa-question';
                                @endphp
                                <span class="badge {{ $badgeClass }} text-capitalize">
                                    <i class="fas {{ $icon }} me-1"></i>{{ $animal['estado'] }}
                                </span>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-tractor me-1"></i>
                                    {{ $animal['finca']['nombre'] ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('animals.show', $animal['id']) }}" 
                                       class="btn btn-info btn-sm" 
                                       title="Ver detalles"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('animals.edit', $animal['id']) }}" 
                                       class="btn btn-warning btn-sm"
                                       title="Editar"
                                       data-bs-toggle="tooltip">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('animals.destroy', $animal['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar el animal {{ $animal['identificacion'] }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm"
                                                title="Eliminar"
                                                data-bs-toggle="tooltip">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-cow fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay animales registrados</h4>
                    <p class="text-muted">Comienza agregando el primer animal al sistema</p>
                </div>
                <a href="{{ route('animals.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primer Animal
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas rápidas -->
    @if(count($animals) > 0)
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Animales Activos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('estado', 'activo')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Animales Enfermos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('estado', 'enfermo')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-first-aid fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Machos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('sexo', 'macho')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-mars fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-pink shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-pink text-uppercase mb-1">
                                Hembras
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($animals)->where('sexo', 'hembra')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-venus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .bg-pink { 
        background-color: #e83e8c !important; 
        color: white !important; 
    }
    .border-left-pink { 
        border-left: 0.25rem solid #e83e8c !important; 
    }
    .text-pink {
        color: #e83e8c !important;
    }
    .table-hover tbody tr:hover { 
        background-color: rgba(40, 167, 69, 0.1) !important; 
        cursor: pointer;
    }
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endsection

@section('scripts')
<script>
    // Inicializar tooltips
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endsection