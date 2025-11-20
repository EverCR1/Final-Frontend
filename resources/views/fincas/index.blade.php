@extends('layouts.app')

@section('title', 'Gestión de Fincas')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tractor text-success"></i> Gestión de Fincas
        </h1>
        
        <!-- Botón Nueva Finca - SOLO ADMIN -->
        @if(session('user.role') === 'admin')
        <a href="{{ route('fincas.create') }}" class="btn btn-success btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-plus"></i>
            </span>
            <span class="text">Nueva Finca</span>
        </a>
        @endif
    </div>

    <!-- Búsqueda y Filtros -->
    <div class="row mb-3">
        <div class="col-md-4">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar por nombre, ubicación, responsable...">
            </div>
        </div>
        <div class="col-md-2">
            <select id="filterZona" class="form-select">
                <option value="">Todas las zonas</option>
                <option value="norte">Norte</option>
                <option value="sur">Sur</option>
                <option value="este">Este</option>
                <option value="oeste">Oeste</option>
            </select>
        </div>
        <div class="col-md-2">
            <button id="clearFilters" class="btn btn-outline-secondary w-100">
                <i class="fas fa-times"></i> Limpiar
            </button>
        </div>
    </div>

    <!-- Fincas Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-list me-2"></i>Listado de Fincas
            </h6>
            <span class="badge bg-success" id="fincasCount">{{ count($fincas) }} fincas registradas</span>
        </div>
        <div class="card-body">
            @if(count($fincas) > 0)
            <div class="table-responsive">
                <table class="table table-bordered table-hover table-striped" id="fincasTable" width="100%" cellspacing="0">
                    <thead class="table-success">
                        <tr>
                            <th>No.</th>
                            <th>Nombre</th>
                            <th>Ubicación</th>
                            <th>Zona</th>
                            <th>Responsable</th>
                            <th>Teléfono</th>
                            <th>Subred IP</th>
                            @if(session('user.role') === 'admin')
                            <th>Acciones</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($fincas as $index => $finca)
                        <tr>
                            <td><strong>#{{ $index + 1 }}</strong></td>
                            <td>
                                <strong>{{ $finca['nombre'] }}</strong>
                            </td>
                            <td>{{ $finca['ubicacion'] }}</td>
                            <td>
                                @php
                                    $zonaColors = [
                                        'norte' => 'primary',
                                        'sur' => 'info',
                                        'este' => 'warning',
                                        'oeste' => 'secondary',
                                    ];
                                @endphp
                                <span class="badge bg-{{ $zonaColors[$finca['zona']] ?? 'secondary' }} text-capitalize">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $finca['zona'] }}
                                </span>
                            </td>
                            <td>{{ $finca['responsable'] }}</td>
                            <td>
                                @if($finca['telefono'])
                                <i class="fas fa-phone me-1 text-muted"></i>{{ $finca['telefono'] }}
                                @else
                                <span class="text-muted">No especificado</span>
                                @endif
                            </td>
                            <td>
                                <code class="text-muted">{{ $finca['ip_subred'] ?? 'N/A' }}</code>
                            </td>
                            
                            <!-- Columna de Acciones - SOLO ADMIN -->
                            @if(session('user.role') === 'admin')
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('fincas.show', $finca['id']) }}" 
                                       class="btn btn-info" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('fincas.edit', $finca['id']) }}" 
                                       class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('fincas.destroy', $finca['id']) }}" 
                                        method="POST" class="d-inline"
                                        onsubmit="return confirmDelete(event, '{{ $finca['nombre'] }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
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
                    <i class="fas fa-tractor fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay fincas registradas</h4>
                    <p class="text-muted">Comienza agregando la primera finca al sistema</p>
                </div>
                <!-- Botón solo para admin cuando no hay fincas -->
                @if(session('user.role') === 'admin')
                <a href="{{ route('fincas.create') }}" class="btn btn-success btn-lg">
                    <i class="fas fa-plus me-2"></i>Registrar Primera Finca
                </a>
                @else
                <p class="text-warning">
                    <i class="fas fa-info-circle me-2"></i>
                    Contacta al administrador para registrar fincas
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
        background-color: rgba(40, 167, 69, 0.1) !important; 
        cursor: pointer;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Funcionalidad de filtrado
        initializeFilters();
    });

    function initializeFilters() {
        const searchInput = document.getElementById('searchInput');
        const filterZona = document.getElementById('filterZona');
        const clearFilters = document.getElementById('clearFilters');
        const fincasCount = document.getElementById('fincasCount');
        const table = document.getElementById('fincasTable');
        const rows = table ? table.getElementsByTagName('tbody')[0].getElementsByTagName('tr') : [];
        
        function filterTable() {
            const searchText = searchInput.value.toLowerCase();
            const zonaValue = filterZona.value;
            
            let visibleCount = 0;

            for (let row of rows) {
                const cells = row.getElementsByTagName('td');
                const nombre = cells[1].textContent.toLowerCase();
                const ubicacion = cells[2].textContent.toLowerCase();
                const zona = cells[3].textContent.toLowerCase();
                const responsable = cells[4].textContent.toLowerCase();

                const matchesSearch = !searchText || 
                    nombre.includes(searchText) ||
                    ubicacion.includes(searchText) ||
                    responsable.includes(searchText);

                const matchesZona = !zonaValue || 
                    zona.includes(zonaValue.toLowerCase());

                const isVisible = matchesSearch && matchesZona;
                row.style.display = isVisible ? '' : 'none';
                
                if (isVisible) {
                    visibleCount++;
                }
            }

            fincasCount.textContent = visibleCount + ' fincas registradas';
            fincasCount.className = visibleCount === 0 ? 'badge bg-danger' : 'badge bg-success';
        }

        searchInput.addEventListener('keyup', filterTable);
        filterZona.addEventListener('change', filterTable);

        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            filterZona.value = '';
            filterTable();
        });
    }

    // Función para confirmar eliminación
    async function confirmDelete(event, fincaNombre) {
        event.preventDefault();
        
        if (!confirm(`¿Estás seguro de eliminar la finca "${fincaNombre}"?`)) {
            return false;
        }
        
        const form = event.target.closest('form');
        const formData = new FormData();
        formData.append('_method', 'DELETE');
        formData.append('_token', form.querySelector('input[name="_token"]').value);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });
            
            // Verificar si la respuesta es JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                const data = await response.json();
                
                if (response.ok) {
                    alert('✅ ' + data.message);
                    window.location.reload();
                } else {
                    alert('❌ ' + data.message);
                }
            } else {
                // Si no es JSON, recargar la página
                window.location.reload();
            }
            
        } catch (error) {
            console.error('Error:', error);
            alert('❌ Error de conexión');
            // En caso de error, enviar el formulario tradicionalmente
            form.submit();
        }
        
        return false;
    }
</script>
@endsection