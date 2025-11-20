@extends('layouts.app')

@section('title', 'Detalle del Alimento')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-warning"></i> Detalle del Alimento
        </h1>
        <div>
            @if(in_array(session('user.role'), ['admin', 'veterinario']))
            <a href="{{ route('alimentacion.alimentos.edit', $alimento['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            @endif
            <a href="{{ route('alimentacion.alimentos.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información del Alimento -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> {{ $alimento['nombre'] }}</p>
                            <p><strong>Tipo:</strong> 
                                @php
                                    $tipoColors = [
                                        'concentrado' => 'primary',
                                        'forraje' => 'success',
                                        'suplemento' => 'info',
                                        'mineral' => 'secondary',
                                        'otro' => 'dark'
                                    ];
                                    $tipoIconos = [
                                        'concentrado' => 'fa-weight',
                                        'forraje' => 'fa-leaf',
                                        'suplemento' => 'fa-pills',
                                        'mineral' => 'fa-gem',
                                        'otro' => 'fa-box'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $tipoColors[$alimento['tipo']] ?? 'secondary' }} text-capitalize">
                                    <i class="fas {{ $tipoIconos[$alimento['tipo']] ?? 'fa-box' }} me-1"></i>{{ $alimento['tipo'] }}
                                </span>
                            </p>
                            <p><strong>Finca:</strong> {{ $alimento['finca']['nombre'] ?? 'N/A' }}</p>
                            <p><strong>Proveedor:</strong> 
                                {{ $alimento['proveedor'] ?: '<span class="text-muted">No especificado</span>' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Stock Actual:</strong> 
                                <span class="{{ $alimento['stock_actual'] <= $alimento['stock_minimo'] ? 'text-danger fw-bold' : 'text-success' }}">
                                    {{ $alimento['stock_actual'] }} {{ $alimento['unidad_medida'] }}
                                </span>
                            </p>
                            <p><strong>Stock Mínimo:</strong> {{ $alimento['stock_minimo'] }} {{ $alimento['unidad_medida'] }}</p>
                            <p><strong>Precio Unitario:</strong> 
                                {{ $alimento['precio_unitario'] ? 'Q' . number_format($alimento['precio_unitario'], 2) : '<span class="text-muted">No especificado</span>' }}
                            </p>
                            <p><strong>Estado:</strong> 
                                @if($alimento['stock_actual'] <= 0)
                                <span class="badge bg-danger">Agotado</span>
                                @elseif($alimento['stock_actual'] <= $alimento['stock_minimo'])
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
                                @if($alimento['fecha_vencimiento'])
                                    @php
                                        $hoy = now();
                                        $vencimiento = \Carbon\Carbon::parse($alimento['fecha_vencimiento']);
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
                    @if($alimento['descripcion'])
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Descripción:</strong></p>
                            <div class="border rounded p-3 bg-light">
                                {{ $alimento['descripcion'] }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Dietas que usan este alimento -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-utensils me-2"></i>Dietas que usan este alimento
                        <span class="badge bg-success ms-2">{{ count($alimento['dieta_alimentos'] ?? []) }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($alimento['dieta_alimentos']) && count($alimento['dieta_alimentos']) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>Dieta</th>
                                    <th>Cantidad</th>
                                    <th>Frecuencia</th>
                                    <th>Tipo Animal</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($alimento['dieta_alimentos'] as $dietaAlimento)
                                @if(isset($dietaAlimento['dieta']))
                                <tr>
                                    <td>
                                        <strong>{{ $dietaAlimento['dieta']['nombre'] }}</strong>
                                    </td>
                                    <td>{{ $dietaAlimento['cantidad'] }} {{ $alimento['unidad_medida'] }}</td>
                                    <td>{{ $dietaAlimento['frecuencia'] }}</td>
                                    <td>
                                        <span class="badge bg-info text-capitalize">
                                            {{ $dietaAlimento['dieta']['tipo_animal'] }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($dietaAlimento['dieta']['activa'])
                                        <span class="badge bg-success">Activa</span>
                                        @else
                                        <span class="badge bg-secondary">Inactiva</span>
                                        @endif
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-utensils fa-2x text-muted mb-3"></i>
                        <p class="text-muted">Este alimento no está siendo utilizado en ninguna dieta</p>
                        @if(in_array(session('user.role'), ['admin', 'veterinario']))
                        <a href="{{ route('alimentacion.dietas.create') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-plus me-1"></i>Crear Dieta
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Estadísticas del Alimento -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-warning">{{ $alimento['stock_actual'] }}</h2>
                        <p class="text-muted">{{ $alimento['unidad_medida'] }} en Stock</p>
                    </div>
                    
                    <!-- Indicador de Stock -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Nivel de Stock</span>
                            <span>{{ $alimento['stock_actual'] }} / {{ max($alimento['stock_minimo'] * 2, $alimento['stock_actual']) }} {{ $alimento['unidad_medida'] }}</span>
                        </div>
                        <div class="progress" style="height: 20px;">
                            @php
                                $porcentaje = min(100, ($alimento['stock_actual'] / max($alimento['stock_minimo'] * 2, 1)) * 100);
                                $color = $alimento['stock_actual'] <= $alimento['stock_minimo'] ? 'bg-danger' : 
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
                            Mínimo: {{ $alimento['stock_minimo'] }} {{ $alimento['unidad_medida'] }}
                        </small>
                    </div>

                    <!-- Información de Uso -->
                    <hr>
                    <h6 class="text-muted mb-3">Información de Uso:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Total Dietas:</span>
                        <span class="badge bg-info">{{ count($alimento['dieta_alimentos'] ?? []) }}</span>
                    </div>
                    
                    @if($alimento['precio_unitario'])
                    <div class="d-flex justify-content-between mb-2">
                        <span>Valor Inventario:</span>
                        <span class="badge bg-success">Q{{ number_format($alimento['stock_actual'] * $alimento['precio_unitario'], 2) }}</span>
                    </div>
                    @endif

                    <!-- Consumo Estimado -->
                    @if(isset($alimento['dieta_alimentos']) && count($alimento['dieta_alimentos']) > 0)
                    <div class="d-flex justify-content-between mb-2">
                        <span>Consumo Diario Est.:</span>
                        <span class="badge bg-warning text-dark">
                            {{ array_sum(array_column($alimento['dieta_alimentos'], 'cantidad')) }} {{ $alimento['unidad_medida'] }}
                        </span>
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
                        <a href="{{ route('alimentacion.alimentos.edit', $alimento['id']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Alimento
                        </a>
                        <a href="{{ route('alimentacion.dietas.create') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-utensils me-2"></i>Usar en Dieta
                        </a>
                        @if(session('user.role') === 'admin')
                        <form action="{{ route('alimentacion.alimentos.destroy', $alimento['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar este alimento? También se eliminará de las dietas asociadas.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Alimento
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Alertas Importantes -->
            @if($alimento['stock_actual'] <= $alimento['stock_minimo'] || ($alimento['fecha_vencimiento'] && \Carbon\Carbon::parse($alimento['fecha_vencimiento'])->diffInDays(now(), false) >= 0))
            <div class="card shadow mt-4 border-danger">
                <div class="card-header py-3 bg-danger text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alertas Importantes
                    </h6>
                </div>
                <div class="card-body">
                    @if($alimento['stock_actual'] <= $alimento['stock_minimo'])
                    <div class="alert alert-warning mb-2">
                        <i class="fas fa-boxes me-2"></i>
                        <strong>Stock Bajo:</strong> El inventario está por debajo del mínimo.
                        @if(in_array(session('user.role'), ['admin', 'veterinario']))
                        <br><small><a href="{{ route('alimentacion.alimentos.edit', $alimento['id']) }}" class="alert-link">Reponer stock</a></small>
                        @endif
                    </div>
                    @endif
                    
                    @if($alimento['fecha_vencimiento'] && \Carbon\Carbon::parse($alimento['fecha_vencimiento'])->diffInDays(now(), false) >= 0)
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-clock me-2"></i>
                        <strong>Producto Vencido:</strong> Este alimento ha vencido.
                    </div>
                    @endif
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
                        <strong>Creado:</strong> 
                        {{ \Carbon\Carbon::parse($alimento['created_at'])->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-0">
                        <strong>Actualizado:</strong> 
                        {{ \Carbon\Carbon::parse($alimento['updated_at'])->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection