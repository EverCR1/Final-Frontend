@extends('layouts.app')

@section('title', 'Registrar Nueva Finca')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-success"></i> Registrar Nueva Finca
        </h1>
        <a href="{{ route('fincas.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver al Listado</span>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-info-circle me-2"></i>Información de la Finca
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('fincas.store') }}" id="fincaForm">
                @csrf
                
                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre de la Finca <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre') }}" 
                               placeholder="Ej: Finca Norte San José" required>
                    </div>

                    <!-- Ubicación -->
                    <div class="col-md-6 mb-3">
                        <label for="ubicacion" class="form-label">Ubicación <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="ubicacion" name="ubicacion" 
                               value="{{ old('ubicacion') }}" 
                               placeholder="Ej: Zona Norte, Cobán" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Responsable -->
                    <div class="col-md-6 mb-3">
                        <label for="responsable" class="form-label">Responsable <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="responsable" name="responsable" 
                               value="{{ old('responsable') }}" 
                               placeholder="Nombre del responsable" required>
                    </div>

                    <!-- Teléfono -->
                    <div class="col-md-6 mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" 
                               id="telefono" name="telefono" 
                               value="{{ old('telefono') }}" 
                               placeholder="Ej: 12345678">
                    </div>
                </div>

                <div class="row">
                    <!-- Zona -->
                    <div class="col-md-6 mb-3">
                        <label for="zona" class="form-label">Zona <span class="text-danger">*</span></label>
                        <select class="form-select" id="zona" name="zona" required>
                            <option value="">Seleccionar Zona</option>
                            <option value="norte" {{ old('zona') == 'norte' ? 'selected' : '' }}>Zona Norte</option>
                            <option value="sur" {{ old('zona') == 'sur' ? 'selected' : '' }}>Zona Sur</option>
                            <option value="este" {{ old('zona') == 'este' ? 'selected' : '' }}>Zona Este</option>
                        </select>
                    </div>

                    <!-- Subred IP -->
                    <div class="col-md-6 mb-3">
                        <label for="ip_subred" class="form-label">Subred IP</label>
                        <input type="text" class="form-control" 
                               id="ip_subred" name="ip_subred" 
                               value="{{ old('ip_subred') }}" 
                               placeholder="Ej: 192.168.20.0/24">
                        <small class="form-text text-muted">
                            Para integración con infraestructura de red
                        </small>
                    </div>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('fincas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Guardar Finca
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Validación del formulario
        document.getElementById('fincaForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos.');
            }
        });
    });
</script>
@endsection