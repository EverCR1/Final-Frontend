@extends('layouts.app')

@section('title', 'Gestión de Medicamentos')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-pills text-info"></i> Gestión de Medicamentos
        </h1>
        
        <!-- Botón Nuevo Medicamento - SOLO ADMIN y VETERINARIO -->
        @if(in_array(session('user.role'), ['admin', 'veterinario']))
        <a href="{{ route('medicamentos.create') }}" class="btn btn-info btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nuevo Medicamento</span>
        </a>
        @endif
    </div>


    <!-- Medicamentos Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-list me-2"></i>Inventario de Medicamentos
            </h6>
            <span class="badge bg-info">{{ count($medicamentos) }} medicamentos registrados</span>
        </div>
        <div class="card-body">
            @if(count($medicamentos) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="medicamentosTable" width="100%" cellspacing="0">
                    <thead class="table-info">
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Tipo</th>
                            <th>Finca</th>
                            <th>Stock</th>
                            <th>Precio</th>
                            <th>Vencimiento</th>
                            <th>Estado</th>
                            @if(in_array(session('user.role'), ['admin', 'veterinario']))
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($medicamentos as $medicamento)
                        <tr>
                            <td><strong>#{{ $medicamento['id'] }}</strong></td>
                            <td>
                                <strong>{{ $medicamento['nombre'] }}</strong>
                                @if($medicamento['descripcion'])
                                <br><small class="text-muted">{{ Str::limit($medicamento['descripcion'], 50) }}</small>
                                @endif
                            </td>
                            <td>
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
                            </td>
                            <td>{{ $medicamento['finca_nombre'] ?? 'N/A' }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="me-2 {{ $medicamento['stock_actual'] <= $medicamento['stock_minimo'] ? 'text-danger fw-bold' : 'text-success' }}">
                                        {{ $medicamento['stock_actual'] }}
                                    </span>
                                    <small class="text-muted">/ min {{ $medicamento['stock_minimo'] }}</small>
                                </div>
                                @if($medicamento['stock_actual'] <= $medicamento['stock_minimo'])
                                <small class="text-danger">
                                    <i class="fas fa-exclamation-triangle"></i> Stock bajo
                                </small>
                                @endif
                            </td>
                            <td>
                                @if($medicamento['precio_unitario'])
                                Q{{ number_format($medicamento['precio_unitario'], 2) }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($medicamento['fecha_vencimiento'])
                                    @php
                                        $hoy = now();
                                        $vencimiento = \Carbon\Carbon::parse($medicamento['fecha_vencimiento']);
                                        $diasRestantes = $hoy->diffInDays($vencimiento, false);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="text-danger" title="Vencido">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $vencimiento->format('d/m/Y') }}
                                        </span>
                                    @elseif($diasRestantes <= 30)
                                        <span class="text-warning" title="Por vencer">
                                            <i class="fas fa-clock"></i> {{ $vencimiento->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-success">{{ $vencimiento->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                <span class="text-muted">No aplica</span>
                                @endif
                            </td>
                            <td>
                                @if($medicamento['stock_actual'] <= 0)
                                <span class="badge bg-danger">Agotado</span>
                                @elseif($medicamento['stock_actual'] <= $medicamento['stock_minimo'])
                                <span class="badge bg-warning">Stock Bajo</span>
                                @else
                                <span class="badge bg-success">Disponible</span>
                                @endif
                            </td>
                            
                            <!-- Columna de Acciones - SOLO ADMIN y VETERINARIO -->
                            @if(in_array(session('user.role'), ['admin', 'veterinario']))
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('medicamentos.show', $medicamento['id']) }}" 
                                       class="btn btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('medicamentos.edit', $medicamento['id']) }}" 
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(session('user.role') === 'admin')
                                    <form action="{{ route('medicamentos.destroy', $medicamento['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este medicamento?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="fas fa-pills fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay medicamentos registrados</h4>
                    <p class="text-muted">Comienza agregando el primer medicamento al inventario</p>
                </div>
                <!-- Botón solo para admin y veterinario cuando no hay medicamentos -->
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('medicamentos.create') }}" class="btn btn-info btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primer Medicamento
                </a>
                @else
                <p class="text-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Contacta al administrador o veterinario para gestionar medicamentos
                </p>
                @endif
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .table-hover tbody tr:hover { 
        background-color: rgba(23, 162, 184, 0.1) !important; 
        cursor: pointer;
    }
</style>
@endsection