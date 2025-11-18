@extends('layouts.app')

@section('title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-users text-primary"></i> Gestión de Usuarios
        </h1>
        
        <!-- Botón Nuevo Usuario - SOLO ADMIN -->
        @if(session('user.role') === 'admin')
        <a href="{{ route('users.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nuevo Usuario</span>
        </a>
        @endif
    </div>


    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Lista de Usuarios
            </h6>
            <span class="badge bg-primary">{{ count($users) }} usuarios registrados</span>
        </div>
        <div class="card-body">
            @if(count($users) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="usersTable" width="100%" cellspacing="0">
                    <thead class="table-primary">
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Fecha Registro</th>
                            <th>Estado</th>
                            @if(session('user.role') === 'admin')
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $index => $user)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <strong>{{ $user['name'] }}</strong>
                                @if($user['id'] === session('user.id'))
                                <span class="badge bg-info ms-1">Tú</span>
                                @endif
                            </td>
                            <td>{{ $user['email'] }}</td>
                            <td>
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
                                <span class="badge bg-{{ $roleColors[$user['role']] ?? 'secondary' }}">
                                    <i class="fas fa-user-tag me-1"></i>{{ $roleLabels[$user['role']] }}
                                </span>
                            </td>
                            <td>
                                @if(isset($user['created_at']))
                                {{ \Carbon\Carbon::parse($user['created_at'])->format('d/m/Y') }}
                                @else
                                <span class="text-muted">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Activo
                                </span>
                            </td>
                            
                            <!-- Columna de Acciones - SOLO ADMIN -->
                            @if(session('user.role') === 'admin')
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('users.show', $user['id']) }}" 
                                       class="btn btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('users.edit', $user['id']) }}" 
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    @if($user['id'] !== session('user.id'))
                                    <form action="{{ route('users.destroy', $user['id']) }}" 
                                          method="POST" class="d-inline"
                                          onsubmit="return confirm('¿Estás seguro de eliminar este usuario? Esta acción no se puede deshacer.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @else
                                    <button class="btn btn-secondary" disabled title="No puedes eliminarte a ti mismo">
                                        <i class="fas fa-trash"></i>
                                    </button>
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
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay usuarios registrados</h4>
                    <p class="text-muted">Comienza agregando el primer usuario al sistema</p>
                </div>
                <!-- Botón solo para admin cuando no hay usuarios -->
                @if(session('user.role') === 'admin')
                <a href="{{ route('users.create') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primer Usuario
                </a>
                @else
                <p class="text-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Solo los administradores pueden gestionar usuarios
                </p>
                @endif
            </div>
            @endif
        </div>
    </div>

    <!-- Estadísticas Rápidas -->
    @if(count($users) > 0 && session('user.role') === 'admin')
    <div class="row">
        <div class="col-md-3 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Usuarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ count($users) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Administradores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($users)->where('role', 'admin')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-shield fa-2x text-gray-300"></i>
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
                                Veterinarios</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($users)->where('role', 'veterinario')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-md fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Productores</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ collect($users)->where('role', 'productor')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
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
    .table-hover tbody tr:hover { 
        background-color: rgba(13, 110, 253, 0.1) !important; 
        cursor: pointer;
    }
    
    .btn-group .btn {
        border-radius: 0.35rem;
        margin-right: 0.25rem;
    }
</style>
@endsection

@section('scripts')
<script>
    // Hacer filas clickeables para ver detalles
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('#usersTable tbody tr');
        rows.forEach(row => {
            row.addEventListener('click', function(e) {
                // Evitar click en botones de acciones
                if (!e.target.closest('.btn-group')) {
                    const userId = this.querySelector('td:first-child strong').textContent.replace('#', '');
                    window.location.href = "{{ route('users.show', '') }}/" + userId;
                }
            });
        });
    });
</script>
@endsection