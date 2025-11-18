@extends('layouts.app')

@section('title', 'Detalle del Medicamento')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-info"></i> Detalle del Medicamento
        </h1>
        <div>
            @if(in_array(session('user.role'), ['admin', 'veterinario']))
            <a href="{{ route('medicamentos.edit', $medicamento['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            @endif
            <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información del Medicamento -->
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
                            <p><strong>Nombre:</strong> {{ $medicamento['nombre'] }}</p>
                            <p><strong>Tipo:</strong> 
                                @php
                                    $tipoColors = [
                                        'vacuna' => 'success',
                                        'antibiotico' => 'warning',
                                        'vitaminas' => 'primary',
                                        'desparasitante' => 'danger',
                                        'otro' => 'secondary'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $tipoColors[$medicamento['tipo']] ?? 'secondary' }} text-capitalize">
                                    <i class="fas fa-syringe me-1"></i>{{ $medicamento['tipo'] }}
                                </span>
                            </p>
                            <p><strong>Finca:</strong> {{ $medicamento['finca_nombre'] ?? 'N/A' }}</p>
                            <p><strong>Proveedor:</strong> 
                                {{ $medicamento['proveedor'] ?: '<span class="text-muted">No especificado</span>' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Stock Actual:</strong> 
                                <span class="{{ $medicamento['stock_actual'] <= $medicamento['stock_minimo'] ? 'text-danger fw-bold' : 'text-success' }}">
                                    {{ $medicamento['stock_actual'] }}
                                </span>
                            </p>
                            <p><strong>Stock Mínimo:</strong> {{ $medicamento['stock_minimo'] }}</p>
                            <p><strong>Precio Unitario:</strong> 
                                {{ $medicamento['precio_unitario'] ? 'Q' . number_format($medicamento['precio_unitario'], 2) : '<span class="text-muted">No especificado</span>' }}
                            </p>
                            <p><strong>Estado:</strong> 
                                @if($medicamento['stock_actual'] <= 0)
                                <span class="badge bg-danger">Agotado</span>
                                @elseif($medicamento['stock_actual'] <= $medicamento['stock_minimo'])
                                <span class="badge bg-warning text-dark">Stock Bajo</span>
                                @else
                                <span class="badge bg-success">Disponible</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    
                    <!-- Fecha de Vencimiento -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Fecha de Vencimiento:</strong> 
                                @if($medicamento['fecha_vencimiento'])
                                    @php
                                        $hoy = now();
                                        $vencimiento = \Carbon\Carbon::parse($medicamento['fecha_vencimiento']);
                                        $diasRestantes = $hoy->diffInDays($vencimiento, false);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="text-danger fw-bold">
                                            <i class="fas fa-exclamation-triangle me-1"></i>
                                            Vencido el {{ $vencimiento->format('d/m/Y') }}
                                        </span>
                                    @elseif($diasRestantes <= 30)
                                        <span class="text-warning fw-bold">
                                            <i class="fas fa-clock me-1"></i>
                                            Vence el {{ $vencimiento->format('d/m/Y') }} (en {{ $diasRestantes }} días)
                                        </span>
                                    @else
                                        <span class="text-success">
                                            {{ $vencimiento->format('d/m/Y') }}
                                        </span>
                                    @endif
                                @else
                                <span class="text-muted">No aplica</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <!-- Descripción -->
                    @if($medicamento['descripcion'])
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Descripción:</strong></p>
                            <div class="border rounded p-3 bg-light">
                                {{ $medicamento['descripcion'] }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Historial de Vacunaciones -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-syringe me-2"></i>Historial de Vacunaciones
                        <span class="badge bg-success ms-2">{{ count($vacunaciones) }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($vacunaciones) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Animal</th>
                                    <th>Veterinario</th>
                                    <th>Dosis</th>
                                    <th>Próxima</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($vacunaciones as $vacunacion)
                                <tr>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($vacunacion['fecha_vacunacion'])->format('d/m/Y') }}</strong>
                                    </td>
                                    <td>
                                        @if(isset($vacunacion['animal_nombre']))
                                        <a href="{{ route('animals.show', $vacunacion['animal_id']) }}" class="text-decoration-none">
                                            {{ $vacunacion['animal_nombre'] }} ({{ $vacunacion['animal_identificacion'] ?? 'N/A' }})
                                        </a>
                                        @else
                                        Animal #{{ $vacunacion['animal_id'] }}
                                        @endif
                                    </td>
                                    <td>{{ $vacunacion['veterinario'] }}</td>
                                    <td>{{ $vacunacion['dosis'] }} ml</td>
                                    <td>
                                        @if($vacunacion['fecha_proxima'])
                                            {{ \Carbon\Carbon::parse($vacunacion['fecha_proxima'])->format('d/m/Y') }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-syringe fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No hay registros de vacunación con este medicamento</p>
                        @if(in_array(session('user.role'), ['admin', 'veterinario']))
                        <button class="btn btn-success btn-sm" disabled title="Módulo de Vacunaciones próximamente">
                            <i class="fas fa-plus me-1"></i>Registrar Vacunación
                        </button>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Estadísticas del Medicamento -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-info">{{ $medicamento['stock_actual'] }}</h2>
                        <p class="text-muted">Unidades en Stock</p>
                    </div>
                    
                    <!-- Indicador de Stock -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Nivel de Stock</span>
                            <span>{{ $medicamento['stock_actual'] }} / {{ max($medicamento['stock_minimo'] * 2, $medicamento['stock_actual']) }}</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            @php
                                $porcentaje = min(100, ($medicamento['stock_actual'] / max($medicamento['stock_minimo'] * 2, 1)) * 100);
                                $color = $medicamento['stock_actual'] <= $medicamento['stock_minimo'] ? 'bg-danger' : 
                                        ($porcentaje < 50 ? 'bg-warning' : 'bg-success');
                            @endphp
                            <div class="progress-bar {{ $color }}" role="progressbar" 
                                 style="width: {{ $porcentaje }}%" 
                                 aria-valuenow="{{ $porcentaje }}" 
                                 aria-valuemin="0" 
                                 aria-valuemax="100">
                                {{ number_format($porcentaje, 0) }}%
                            </div>
                        </div>
                        <small class="text-muted mt-1 d-block">
                            Mínimo: {{ $medicamento['stock_minimo'] }} unidades
                        </small>
                    </div>

                    <!-- Información de Uso -->
                    <hr>
                    <h6 class="text-muted mb-3">Información de Uso:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Vacunaciones:</span>
                        <span class="badge bg-info">{{ count($vacunaciones) }}</span>
                    </div>
                    
                    @if($medicamento['precio_unitario'])
                    <div class="d-flex justify-content-between mb-2">
                        <span>Valor Inventario:</span>
                        <span class="badge bg-success">Q{{ number_format($medicamento['stock_actual'] * $medicamento['precio_unitario'], 2) }}</span>
                    </div>
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
                        <button class="btn btn-outline-success btn-sm" disabled title="Módulo de Vacunaciones próximamente">
                            <i class="fas fa-syringe me-2"></i>Registrar Vacunación
                        </button>
                        <a href="{{ route('medicamentos.edit', $medicamento['id']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Medicamento
                        </a>
                        @if(session('user.role') === 'admin')
                        <form action="{{ route('medicamentos.destroy', $medicamento['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar este medicamento? También se eliminarán las vacunaciones asociadas.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Medicamento
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Alertas Importantes -->
            @if($medicamento['stock_actual'] <= $medicamento['stock_minimo'] || ($medicamento['fecha_vencimiento'] && \Carbon\Carbon::parse($medicamento['fecha_vencimiento'])->diffInDays(now(), false) >= 0))
            <div class="card shadow mt-4 border-danger">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alertas Importantes
                    </h6>
                </div>
                <div class="card-body">
                    @if($medicamento['stock_actual'] <= $medicamento['stock_minimo'])
                    <div class="alert alert-warning mb-2">
                        <i class="fas fa-boxes me-2"></i>
                        <strong>Stock Bajo:</strong> El inventario está por debajo del mínimo.
                    </div>
                    @endif
                    
                    @if($medicamento['fecha_vencimiento'] && \Carbon\Carbon::parse($medicamento['fecha_vencimiento'])->diffInDays(now(), false) >= 0)
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Producto Vencido:</strong> Este medicamento ha vencido.
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection