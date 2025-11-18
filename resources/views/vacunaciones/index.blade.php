@extends('layouts.app')

@section('title', 'Gestión de Vacunaciones')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-syringe text-primary"></i> Gestión de Vacunaciones
        </h1>
        
        <!-- Botón Nueva Vacunación - SOLO ADMIN y VETERINARIO -->
        @if(in_array(session('user.role'), ['admin', 'veterinario']))
        <a href="{{ route('vacunaciones.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nueva Vacunación</span>
        </a>
        @endif
    </div>


    <!-- Vacunaciones Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Historial de Vacunaciones
            </h6>
            <span class="badge bg-primary">{{ count($vacunaciones) }} registros</span>
        </div>
        <div class="card-body">
            @if(count($vacunaciones) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="vacunacionesTable" width="100%" cellspacing="0">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Fecha</th>
                            <th>Animal</th>
                            <th>Finca</th>
                            <th>Medicamento</th>
                            <th>Vacuna</th>
                            <th>Veterinario</th>
                            <th>Próxima</th>
                            <th>Estado</th>
                            @if(in_array(session('user.role'), ['admin', 'veterinario']))
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vacunaciones as $index => $vacunacion)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($vacunacion['fecha_vacunacion'])->format('d/m/Y') }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $vacunacion['animal']['identificacion'] ?? 'N/A' }}</strong>
                                    @if($vacunacion['animal']['nombre'] ?? false)
                                    <br><small class="text-muted">{{ $vacunacion['animal']['nombre'] }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                {{ $vacunacion['animal']['finca']['nombre'] ?? 'N/A' }}
                            </td>
                            <td>
                                {{ $vacunacion['medicamento']['nombre'] ?? 'N/A' }}
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">
                                    {{ $vacunacion['vacuna'] }}
                                </span>
                                <br>
                                <small class="text-muted">Lote: {{ $vacunacion['lote'] }}</small>
                            </td>
                            <td>{{ $vacunacion['veterinario'] }}</td>
                            <td>
                                @if($vacunacion['fecha_proxima'])
                                    @php
                                        $hoy = now();
                                        $proxima = \Carbon\Carbon::parse($vacunacion['fecha_proxima']);
                                        $diasRestantes = $hoy->diffInDays($proxima, false);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="text-danger" title="Vencida">
                                            <i class="fas fa-exclamation-triangle"></i> {{ $proxima->format('d/m/Y') }}
                                        </span>
                                    @elseif($diasRestantes <= 7)
                                        <span class="text-warning" title="Próxima">
                                            <i class="fas fa-clock"></i> {{ $proxima->format('d/m/Y') }}
                                        </span>
                                    @else
                                        <span class="text-success">{{ $proxima->format('d/m/Y') }}</span>
                                    @endif
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                @if($vacunacion['fecha_proxima'])
                                    @php
                                        $hoy = now();
                                        $proxima = \Carbon\Carbon::parse($vacunacion['fecha_proxima']);
                                        $diasRestantes = $hoy->diffInDays($proxima, false);
                                    @endphp
                                    @if($diasRestantes < 0)
                                        <span class="badge bg-danger">Vencida</span>
                                    @elseif($diasRestantes <= 7)
                                        <span class="badge bg-warning text-dark">Próxima</span>
                                    @else
                                        <span class="badge bg-success">Al día</span>
                                    @endif
                                @else
                                <span class="badge bg-secondary">Sin próxima</span>
                                @endif
                            </td>
                            
                            <!-- Columna de Acciones - SOLO ADMIN y VETERINARIO -->
                            @if(in_array(session('user.role'), ['admin', 'veterinario']))
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('vacunaciones.show', $vacunacion['id']) }}" 
                                       class="btn btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('vacunaciones.edit', $vacunacion['id']) }}" 
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if(in_array(session('user.role'), ['admin', 'veterinario']))
                                    <form action="{{ route('vacunaciones.destroy', $vacunacion['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este registro de vacunación?')">
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
                    <i class="fas fa-syringe fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay vacunaciones registradas</h4>
                    <p class="text-muted">Comienza registrando la primera vacunación</p>
                </div>
                <!-- Botón solo para admin y veterinario cuando no hay vacunaciones -->
                @if(in_array(session('user.role'), ['admin', 'veterinario']))
                <a href="{{ route('vacunaciones.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primera Vacunación
                </a>
                @else
                <p class="text-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Contacta al administrador o veterinario para gestionar vacunaciones
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
        background-color: rgba(13, 110, 253, 0.1) !important; 
        cursor: pointer;
    }
</style>
@endsection