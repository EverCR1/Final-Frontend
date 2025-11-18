@extends('layouts.app')

@section('title', 'Detalles del Usuario')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user text-info"></i> Detalles del Usuario
        </h1>
        <div>
            <a href="{{ route('users.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Volver a Usuarios</span>
            </a>
            @if(session('user.role') === 'admin')
            <a href="{{ route('users.edit', $user['id']) }}" class="btn btn-warning btn-icon-split ms-2">
                <span class="icon text-white-50">
                    <i class="fas fa-edit"></i>
                </span>
                <span class="text">Editar</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Información del Usuario -->
    <div class="row">
        <div class="col-lg-4">
            <!-- Tarjeta de Perfil -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-info text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-id-card me-2"></i>Perfil del Usuario
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="avatar-circle bg-primary text-white mx-auto mb-3">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <h4 class="font-weight-bold text-gray-900">{{ $user['name'] }}</h4>
                        @php
                            $roleColors = [
                                'admin' => 'danger',
                                'veterinario' => 'warning',
                                'productor' => 'success'
                            ];
                            $roleLabels = [
                                'admin' => 'Administrador',
                                'veterinario' => 'Veterinario',
                                'productor' => 'Productor'
                            ];
                        @endphp
                        <span class="badge bg-{{ $roleColors[$user['role']] ?? 'secondary' }} fs-6">
                            <i class="fas fa-user-tag me-1"></i>{{ $roleLabels[$user['role']] }}
                        </span>
                    </div>
                    
                    <div class="text-start">
                        <div class="mb-3">
                            <strong><i class="fas fa-envelope text-info me-2"></i>Email:</strong><br>
                            <span class="text-muted">{{ $user['email'] }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <strong><i class="fas fa-calendar text-info me-2"></i>Fecha de Registro:</strong><br>
                            <span class="text-muted">
                                @if(isset($user['created_at']))
                                {{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y H:i') }}
                                @else
                                N/A
                                @endif
                            </span>
                        </div>
                        
                        <div class="mb-3">
                            <strong><i class="fas fa-sync-alt text-info me-2"></i>Última Actualización:</strong><br>
                            <span class="text-muted">
                                @if(isset($user['updated_at']))
                                {{ \Carbon\Carbon::parse($user['updated_at'])->format('d/m/Y H:i') }}
                                @else
                                N/A
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <!-- Información Detallada -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 bg-primary text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-info-circle me-2"></i>Información Detallada
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="font-weight-bold text-primary mb-2">
                                    <i class="fas fa-user-tag me-2"></i>Permisos del Rol
                                </h6>
                                @if($user['role'] === 'admin')
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Gestión completa de usuarios</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Acceso a todos los módulos</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Configuración del sistema</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Reportes y estadísticas</li>
                                </ul>
                                @elseif($user['role'] === 'veterinario')
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Gestión de animales</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Registro de vacunaciones</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Control de medicamentos</li>
                                    <li><i class="fas fa-times text-danger me-2"></i>No puede gestionar usuarios</li>
                                </ul>
                                @else
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>Consulta de sus fincas</li>
                                    <li><i class="fas fa-check text-success me-2"></i>Visualización de animales</li>
                                    <li><i class="fas fa-times text-danger me-2"></i>No puede gestionar usuarios</li>
                                    <li><i class="fas fa-times text-danger me-2"></i>Acceso limitado a reportes</li>
                                </ul>
                                @endif
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="info-item mb-4">
                                <h6 class="font-weight-bold text-primary mb-2">
                                    <i class="fas fa-chart-bar me-2"></i>Estadísticas
                                </h6>
                                <div class="small">
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Estado de la cuenta:</span>
                                        <span class="badge bg-success">Activa</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Usuario desde:</span>
                                        <span>
                                            @if(isset($user['created_at']))
                                            {{ \Carbon\Carbon::parse($user['created_at'])->diffForHumans() }}
                                            @else
                                            N/A
                                            @endif
                                        </span>
                                    </div>
                                    @if($user['id'] === session('user.id'))
                                    <div class="d-flex justify-content-between mb-1">
                                        <span>Tu cuenta:</span>
                                        <span class="badge bg-info">Sí</span>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Acciones Rápidas (Solo Admin) -->
            @if(session('user.role') === 'admin')
            <div class="card shadow mb-4 border-left-warning">
                <div class="card-header py-3 bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">
                        <i class="fas fa-cogs me-2"></i>Acciones de Administración
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if($user['id'] !== session('user.id'))
                        <div class="col-md-6 mb-3">
                            <form action="{{ route('users.destroy', $user['id']) }}" method="POST" 
                                  onsubmit="return confirm('¿Está seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Eliminar Usuario
                                </button>
                            </form>
                        </div>
                        @else
                        <div class="col-md-6 mb-3">
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="fas fa-trash me-2"></i>No puedes eliminarte a ti mismo
                            </button>
                        </div>
                        @endif
                        
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('users.edit', $user['id']) }}" class="btn btn-warning w-100">
                                <i class="fas fa-edit me-2"></i>Editar Usuario
                            </a>
                        </div>
                    </div>
                    
                    @if($user['id'] === session('user.id'))
                    <div class="alert alert-info mt-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Nota:</strong> Esta es tu cuenta de usuario. Algunas acciones están restringidas por seguridad.
                    </div>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
    }
    
    .info-item {
        padding: 15px;
        border-left: 4px solid #4e73df;
        background-color: #f8f9fc;
    }
</style>
@endsection