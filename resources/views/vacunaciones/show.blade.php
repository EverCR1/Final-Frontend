@extends('layouts.app')

@section('title', 'Detalle de Vacunación')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-syringe text-info"></i> Detalle de Vacunación
        </h1>
        <div>
            @if(in_array(session('user.role'), ['admin', 'veterinario']))
            <a href="{{ route('vacunaciones.edit', $vacunacion['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            @endif
            <a href="{{ route('vacunaciones.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información de la Vacunación -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-info-circle me-2"></i>Información de la Vacunación
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Animal:</strong> 
                                {{ $vacunacion['animal']['identificacion'] ?? 'N/A' }}
                                @if($vacunacion['animal']['nombre'] ?? false)
                                - {{ $vacunacion['animal']['nombre'] }}
                                @endif
                            </p>
                            <p><strong>Finca:</strong> 
                                {{ $vacunacion['animal']['finca']['nombre'] ?? 'N/A' }}
                            </p>
                            <p><strong>Medicamento:</strong> 
                                {{ $vacunacion['medicamento']['nombre'] ?? 'N/A' }}
                                <span class="badge bg-secondary">{{ $vacunacion['medicamento']['tipo'] ?? '' }}</span>
                            </p>
                            <p><strong>Vacuna:</strong> 
                                <span class="badge bg-primary">{{ $vacunacion['vacuna'] }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Fecha de Vacunación:</strong> 
                                <strong>{{ \Carbon\Carbon::parse($vacunacion['fecha_vacunacion'])->format('d/m/Y') }}</strong>
                            </p>
                            <p><strong>Lote:</strong> 
                                <code>{{ $vacunacion['lote'] }}</code>
                            </p>
                            <p><strong>Dosis:</strong> 
                                <span class="badge bg-success">{{ $vacunacion['dosis'] }} ml</span>
                            </p>
                            <p><strong>Veterinario:</strong> 
                                {{ $vacunacion['veterinario'] }}
                            </p>
                        </div>
                    </div>

                    <!-- Fecha Próxima Vacunación -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Próxima Vacunación:</strong> 
                                @if($vacunacion['fecha_proxima'])
                                    @php
                                        $hoy = now();
                                        $proxima = \Carbon\Carbon::parse($vacunacion['fecha_proxima']);
                                        $diasRestantes = $hoy->diffInDays($proxima, false);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="text-danger fw-bold">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Vencida el {{ $proxima->format('d/m/Y') }}
                                        </span>
                                    @elseif($diasRestantes <= 7)
                                        <span class="text-warning fw-bold">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $proxima->format('d/m/Y') }} (en {{ $diasRestantes }} días)
                                        </span>
                                    @else
                                        <span class="text-success">{{ $proxima->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                <span class="text-muted">No programada</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Observaciones -->
                    @if($vacunacion['observaciones'])
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Observaciones:</strong></p>
                            <div class="border rounded p-3 bg-light">
                                {{ $vacunacion['observaciones'] }}
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
                    @if(isset($vacunacion['animal']))
                    <p><strong>Identificación:</strong> {{ $vacunacion['animal']['identificacion'] }}</p>
                    <p><strong>Especie:</strong> 
                        <span class="badge bg-info text-capitalize">{{ $vacunacion['animal']['especie'] }}</span>
                    </p>
                    <p><strong>Raza:</strong> {{ $vacunacion['animal']['raza'] }}</p>
                    <p><strong>Sexo:</strong> 
                        <span class="badge bg-secondary text-capitalize">{{ $vacunacion['animal']['sexo'] }}</span>
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
                        <span class="badge bg-{{ $estadoColors[$vacunacion['animal']['estado']] ?? 'secondary' }} text-capitalize">
                            {{ $vacunacion['animal']['estado'] }}
                        </span>
                    </p>
                    <hr>
                    <a href="{{ route('animals.show', $vacunacion['animal']['id']) }}" class="btn btn-outline-success btn-sm">
                        <i class="fas fa-eye me-2"></i>Ver Animal
                    </a>
                    @else
                    <p class="text-muted">Información del animal no disponible</p>
                    @endif
                </div>
            </div>

            <!-- Información del Medicamento -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-pills me-2"></i>Información del Medicamento
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($vacunacion['medicamento']))
                    <p><strong>Nombre:</strong> {{ $vacunacion['medicamento']['nombre'] }}</p>
                    <p><strong>Tipo:</strong> 
                        <span class="badge bg-primary text-capitalize">{{ $vacunacion['medicamento']['tipo'] }}</span>
                    </p>
                    <p><strong>Stock Actual:</strong> 
                        <span class="{{ $vacunacion['medicamento']['stock_actual'] <= $vacunacion['medicamento']['stock_minimo'] ? 'text-danger fw-bold' : 'text-success' }}">
                            {{ $vacunacion['medicamento']['stock_actual'] }}
                        </span>
                    </p>
                    <hr>
                    <a href="{{ route('medicamentos.show', $vacunacion['medicamento']['id']) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-eye me-2"></i>Ver Medicamento
                    </a>
                    @else
                    <p class="text-muted">Información del medicamento no disponible</p>
                    @endif
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
                        <a href="{{ route('vacunaciones.edit', $vacunacion['id']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Vacunación
                        </a>
                        @if(session('user.role') === 'admin')
                        <form action="{{ route('vacunaciones.destroy', $vacunacion['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar este registro de vacunación?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Vacunación
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection