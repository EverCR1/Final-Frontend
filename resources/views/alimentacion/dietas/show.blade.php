@extends('layouts.app')

@section('title', 'Detalle de la Dieta')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-eye text-success"></i> Detalle de la Dieta
        </h1>
        <div>
            @if(in_array(session('user.role'), ['admin', 'veterinario']))
            <a href="{{ route('alimentacion.dietas.edit', $dieta['id']) }}" class="btn btn-warning btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            @endif
            <a href="{{ route('alimentacion.dietas.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver</span>
            </a>
        </div>
    </div>

    <!-- Información de la Dieta -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-info-circle me-2"></i>Información General
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Nombre:</strong> {{ $dieta['nombre'] }}</p>
                            <p><strong>Tipo de Animal:</strong> 
                                @php
                                    $tiposAnimal = [
                                        'bovino' => ['fa-cow', 'primary', 'Bovino'],
                                        'porcino' => ['fa-piggy', 'pink', 'Porcino'],
                                        'caprino' => ['fa-goat', 'warning', 'Caprino'],
                                        'ovina' => ['fa-sheep', 'light', 'Ovina']
                                    ];
                                    list($animalIcono, $animalColor, $animalTexto) = $tiposAnimal[$dieta['tipo_animal']] ?? ['fa-paw', 'secondary', $dieta['tipo_animal']];
                                @endphp
                                <span class="badge bg-{{ $animalColor }} text-capitalize">
                                    <i class="fas {{ $animalIcono }} me-1"></i>{{ $animalTexto }}
                                </span>
                            </p>
                            <p><strong>Finca:</strong> {{ $dieta['finca']['nombre'] ?? 'N/A' }}</p>
                            <p><strong>Categoría:</strong> 
                                @php
                                    $categoriaColors = [
                                        'ternero' => 'info',
                                        'desarrollo' => 'warning',
                                        'adulto' => 'success',
                                        'lactancia' => 'primary',
                                        'gestacion' => 'danger'
                                    ];
                                @endphp
                                <span class="badge bg-{{ $categoriaColors[$dieta['categoria']] ?? 'secondary' }} text-capitalize">
                                    {{ $dieta['categoria'] }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Estado:</strong> 
                                @if($dieta['activa'])
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Activa
                                </span>
                                @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-times-circle me-1"></i>Inactiva
                                </span>
                                @endif
                            </p>
                            <p><strong>Costo Estimado:</strong> 
                                @if($dieta['costo_estimado_kg'])
                                <span class="text-success fw-bold">
                                    Q{{ number_format($dieta['costo_estimado_kg'], 2) }}/kg
                                </span>
                                @else
                                <span class="text-muted">No calculado</span>
                                @endif
                            </p>
                            <p><strong>Total Alimentos:</strong> 
                                <span class="badge bg-info">
                                    {{ count($dieta['alimentos'] ?? []) }} alimentos
                                </span>
                            </p>
                            <p><strong>Registros de Uso:</strong> 
                                <span class="badge bg-warning text-dark">
                                    {{ $dieta['registros_count'] ?? 0 }} registros
                                </span>
                            </p>
                        </div>
                    </div>
                    
                    <!-- Descripción -->
                    @if($dieta['descripcion'])
                    <div class="row mt-3">
                        <div class="col-12">
                            <p><strong>Descripción:</strong></p>
                            <div class="border rounded p-3 bg-light">
                                {{ $dieta['descripcion'] }}
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Alimentos de la Dieta -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-apple-alt me-2"></i>Composición de la Dieta
                        <span class="badge bg-warning ms-2">{{ count($dieta['alimentos'] ?? []) }}</span>
                    </h6>
                </div>
                <div class="card-body">
                    @if(isset($dieta['alimentos']) && count($dieta['alimentos']) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-warning">
                                <tr>
                                    <th>Alimento</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                    <th>Frecuencia</th>
                                    <th>Costo Unitario</th>
                                    <th>Costo Total</th>
                                    <th>Observaciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $costoTotalDieta = 0;
                                @endphp
                                @foreach($dieta['alimentos'] as $alimento)
                                @php
                                    $cantidad = $alimento['pivot']['cantidad'] ?? $alimento['cantidad'] ?? 0;
                                    $precioUnitario = $alimento['precio_unitario'] ?? 0;
                                    $costoAlimento = $cantidad * $precioUnitario;
                                    $costoTotalDieta += $costoAlimento;
                                    
                                    $tipoColors = [
                                        'concentrado' => 'primary',
                                        'forraje' => 'success',
                                        'suplemento' => 'info',
                                        'mineral' => 'secondary',
                                        'otro' => 'dark'
                                    ];
                                @endphp
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-apple-alt text-warning me-2"></i>
                                            <div>
                                                <strong>{{ $alimento['nombre'] }}</strong>
                                                <br>
                                                <small class="text-muted">
                                                    {{ $alimento['unidad_medida'] }} | 
                                                    Stock: {{ $alimento['stock_actual'] }}
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $tipoColors[$alimento['tipo']] ?? 'secondary' }} text-capitalize">
                                            {{ $alimento['tipo'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <strong class="text-primary">
                                            {{ $cantidad }} {{ $alimento['unidad_medida'] }}
                                        </strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $alimento['pivot']['frecuencia'] ?? $alimento['frecuencia'] ?? 'N/A' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($precioUnitario)
                                        <span class="text-success">
                                            Q{{ number_format($precioUnitario, 2) }}
                                        </span>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($costoAlimento > 0)
                                        <span class="text-success fw-bold">
                                            Q{{ number_format($costoAlimento, 2) }}
                                        </span>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $alimento['pivot']['observaciones'] ?? $alimento['observaciones'] ?? 'Sin observaciones' }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-success">
                                <tr>
                                    <td colspan="5" class="text-end"><strong>Total por kg de dieta:</strong></td>
                                    <td colspan="2">
                                        <strong class="text-success fs-5">
                                            Q{{ number_format($costoTotalDieta, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Resumen Nutricional -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card border-success">
                                <div class="card-header bg-success text-white py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-calculator me-2"></i>Resumen de Costos
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Costo por kg:</span>
                                        <strong class="text-success">Q{{ number_format($costoTotalDieta, 2) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Alimentos incluidos:</span>
                                        <strong>{{ count($dieta['alimentos'] ?? []) }}</strong>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span>Costo promedio:</span>
                                        <strong>Q{{ number_format($costoTotalDieta / max(count($dieta['alimentos']), 1), 2) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card border-info">
                                <div class="card-header bg-info text-white py-2">
                                    <h6 class="m-0 font-weight-bold">
                                        <i class="fas fa-chart-pie me-2"></i>Distribución por Tipo
                                    </h6>
                                </div>
                                <div class="card-body">
                                    @php
                                        $tiposCount = [];
                                        foreach($dieta['alimentos'] as $alimento) {
                                            $tipo = $alimento['tipo'];
                                            $tiposCount[$tipo] = ($tiposCount[$tipo] ?? 0) + 1;
                                        }
                                    @endphp
                                    @foreach($tiposCount as $tipo => $count)
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="text-capitalize">{{ $tipo }}:</span>
                                        <strong>{{ $count }}</strong>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-apple-alt fa-2x text-muted mb-3"></i>
                        <h5 class="text-muted">No hay alimentos en esta dieta</h5>
                        <p class="text-muted">Esta dieta no tiene alimentos asignados</p>
                        @if(in_array(session('user.role'), ['admin', 'veterinario']))
                        <a href="{{ route('alimentacion.dietas.edit', $dieta['id']) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>Agregar Alimentos
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Estadísticas de la Dieta -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Estadísticas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h2 class="text-success">{{ count($dieta['alimentos'] ?? []) }}</h2>
                        <p class="text-muted">Alimentos en la Dieta</p>
                    </div>
                    
                    <!-- Información de Uso -->
                    <hr>
                    <h6 class="text-muted mb-3">Información de Uso:</h6>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Estado:</span>
                        @if($dieta['activa'])
                        <span class="badge bg-success">Activa</span>
                        @else
                        <span class="badge bg-secondary">Inactiva</span>
                        @endif
                    </div>
                    
                    <div class="d-flex justify-content-between mb-2">
                        <span>Registros de Uso:</span>
                        <span class="badge bg-warning text-dark">{{ $dieta['registros_count'] ?? 0 }}</span>
                    </div>
                    
                    @if($dieta['costo_estimado_kg'])
                    <div class="d-flex justify-content-between mb-2">
                        <span>Costo por kg:</span>
                        <span class="badge bg-success">Q{{ number_format($dieta['costo_estimado_kg'], 2) }}</span>
                    </div>
                    @endif

                    <!-- Eficiencia de la Dieta -->
                    <hr>
                    <h6 class="text-muted mb-3">Eficiencia:</h6>
                    @php
                        $alimentosConStock = collect($dieta['alimentos'] ?? [])->filter(function($alimento) {
                            return ($alimento['stock_actual'] ?? 0) >= ($alimento['pivot']['cantidad'] ?? $alimento['cantidad'] ?? 0);
                        })->count();
                        
                        $porcentajeStock = count($dieta['alimentos'] ?? []) > 0 ? 
                            ($alimentosConStock / count($dieta['alimentos'])) * 100 : 0;
                    @endphp
                    <div class="d-flex justify-content-between mb-2">
                        <span>Alimentos con stock:</span>
                        <span class="badge bg-{{ $porcentajeStock == 100 ? 'success' : ($porcentajeStock >= 50 ? 'warning' : 'danger') }}">
                            {{ $alimentosConStock }}/{{ count($dieta['alimentos'] ?? []) }}
                        </span>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        <div class="progress-bar bg-{{ $porcentajeStock == 100 ? 'success' : ($porcentajeStock >= 50 ? 'warning' : 'danger') }}" 
                             role="progressbar" 
                             style="width: {{ $porcentajeStock }}%" 
                             aria-valuenow="{{ $porcentajeStock }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
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
                        <a href="{{ route('alimentacion.registros.create') }}?dieta_id={{ $dieta['id'] }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-utensils me-2"></i>Usar en Registro
                        </a>
                        <a href="{{ route('alimentacion.dietas.edit', $dieta['id']) }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>Editar Dieta
                        </a>
                        @if(session('user.role') === 'admin')
                        <form action="{{ route('alimentacion.dietas.destroy', $dieta['id']) }}" 
                              method="POST" class="d-grid"
                              onsubmit="return confirm('¿Está seguro de eliminar esta dieta? También se eliminarán los registros asociados.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-trash me-2"></i>Eliminar Dieta
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Alertas Importantes -->
            @if(!$dieta['activa'] || $porcentajeStock < 100)
            <div class="card shadow mt-4 border-warning">
                <div class="card-header py-3 bg-warning text-dark">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Alertas Importantes
                    </h6>
                </div>
                <div class="card-body">
                    @if(!$dieta['activa'])
                    <div class="alert alert-warning mb-2">
                        <i class="fas fa-toggle-off me-2"></i>
                        <strong>Dieta Inactiva:</strong> No está disponible para nuevos registros.
                    </div>
                    @endif
                    
                    @if($porcentajeStock < 100)
                    <div class="alert alert-danger mb-0">
                        <i class="fas fa-boxes me-2"></i>
                        <strong>Stock Insuficiente:</strong> Algunos alimentos no tienen stock suficiente.
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
                        {{ \Carbon\Carbon::parse($dieta['created_at'])->format('d/m/Y H:i') }}
                    </p>
                    <p class="mb-0">
                        <strong>Actualizado:</strong> 
                        {{ \Carbon\Carbon::parse($dieta['updated_at'])->format('d/m/Y H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection