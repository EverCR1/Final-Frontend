@extends('layouts.app')

@section('title', 'Detalle del Animal')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info"></i> Detalle del Animal
        </h1>
        <div>
            <a href="{{ route('animals.edit', $animal['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            <a href="{{ route('animals.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información del Animal -->
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
                            <p><strong>Identificación:</strong> 
                                <span class="badge bg-primary">{{ $animal['identificacion'] }}</span>
                            </p>
                            <p><strong>Nombre:</strong> 
                                {{ $animal['nombre'] ?: '<span class="text-muted">Sin nombre</span>' }}
                            </p>
                            <p><strong>Especie:</strong> 
                                <span class="badge bg-info text-dark text-capitalize">{{ $animal['especie'] }}</span>
                            </p>
                            <p><strong>Raza:</strong> {{ $animal['raza'] }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Sexo:</strong> 
                                @if($animal['sexo'] == 'macho')
                                <span class="badge bg-primary">
                                    <i class="fas fa-mars me-1"></i>Macho
                                </span>
                                @else
                                <span class="badge bg-pink text-white">
                                    <i class="fas fa-venus me-1"></i>Hembra
                                </span>
                                @endif
                            </p>
                            <p><strong>Estado:</strong> 
                                @php
                                    $badgeClass = [
                                        'activo' => 'bg-success',
                                        'enfermo' => 'bg-warning text-dark',
                                        'vendido' => 'bg-secondary',
                                        'muerto' => 'bg-danger'
                                    ][$animal['estado']] ?? 'bg-secondary';
                                @endphp
                                <span class="badge {{ $badgeClass }} text-capitalize">{{ $animal['estado'] }}</span>
                            </p>
                            <p><strong>Fecha Nacimiento:</strong> 
                                {{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->format('d/m/Y') }}
                            </p>
                            <p><strong>Edad:</strong> 
                                {{ \Carbon\Carbon::parse($animal['fecha_nacimiento'])->age }} años
                            </p>
                        </div>
                    </div>
                    
                    @if($animal['peso_inicial'])
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Peso Inicial:</strong> {{ $animal['peso_inicial'] }} kg</p>
                        </div>
                    </div>
                    @endif

                    @if($animal['observaciones'])
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Observaciones:</strong></p>
                            <div class="alert alert-light border">
                                {{ $animal['observaciones'] }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Información de Finca -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-tractor me-2"></i>Información de Finca
                    </h6>
                </div>
                <div class="card-body">
                    @if($animal['finca'])
                    <p><strong>Finca:</strong> {{ $animal['finca']['nombre'] }}</p>
                    <p><strong>Ubicación:</strong> {{ $animal['finca']['ubicacion'] }}</p>
                    <p><strong>Zona:</strong> 
                        <span class="badge bg-secondary text-capitalize">{{ $animal['finca']['zona'] }}</span>
                    </p>
                    <p><strong>Responsable:</strong> {{ $animal['finca']['responsable'] }}</p>
                    @else
                    <p class="text-muted">No hay información de finca disponible</p>
                    @endif
                </div>
            </div>

            <!-- Acciones Rápidas -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="#" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-syringe me-2"></i>Registrar Vacunación
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-wine-bottle me-2"></i>Registrar Producción
                        </a>
                        <form action="{{ route('animals.destroy', $animal['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar este animal?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Animal
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .bg-pink { 
        background-color: #e83e8c !important; 
        color: white !important; 
    }
</style>
@endsection