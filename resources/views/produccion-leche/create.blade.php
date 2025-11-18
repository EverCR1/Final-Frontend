@extends('layouts.app')

@section('title', 'Registrar Producción de Leche')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-warning"></i> Registrar Producción de Leche
        </h1>
        <a href="{{ route('produccion-leche.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver al Listado</span>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-info-circle me-2"></i>Información de la Producción
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('produccion-leche.store') }}" id="produccionForm">
                @csrf
                
                <div class="row">
                    <!-- Animal -->
                    <div class="col-md-6 mb-3">
                        <label for="animal_id" class="form-label">Animal <span class="text-danger">*</span></label>
                        <select class="form-select" id="animal_id" name="animal_id" required>
                            <option value="">Seleccionar Animal</option>
                            @foreach($animales as $animal)
                            <option value="{{ $animal['id'] }}" {{ old('animal_id') == $animal['id'] ? 'selected' : '' }}>
                                {{ $animal['identificacion'] }} - {{ $animal['nombre'] ?: 'Sin nombre' }} 
                                ({{ $animal['especie'] }} - {{ $animal['finca']['nombre'] ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Solo se muestran hembras activas</small>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="fecha" name="fecha" 
                               value="{{ old('fecha', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}" required>
                        <small class="form-text text-muted">Fecha de la producción</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Cantidad Leche -->
                    <div class="col-md-4 mb-3">
                        <label for="cantidad_leche" class="form-label">Cantidad de Leche (L) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               id="cantidad_leche" name="cantidad_leche" 
                               value="{{ old('cantidad_leche') }}" 
                               min="0" placeholder="0.00" required>
                        <small class="form-text text-muted">Litros producidos</small>
                    </div>

                    <!-- Calidad Grasa -->
                    <div class="col-md-4 mb-3">
                        <label for="calidad_grasa" class="form-label">Calidad de Grasa (%)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="calidad_grasa" name="calidad_grasa" 
                               value="{{ old('calidad_grasa') }}" 
                               min="0" max="100" placeholder="0.00">
                        <small class="form-text text-muted">Porcentaje de grasa (0-100%)</small>
                    </div>

                    <!-- Calidad Proteína -->
                    <div class="col-md-4 mb-3">
                        <label for="calidad_proteina" class="form-label">Calidad de Proteína (%)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="calidad_proteina" name="calidad_proteina" 
                               value="{{ old('calidad_proteina') }}" 
                               min="0" max="100" placeholder="0.00">
                        <small class="form-text text-muted">Porcentaje de proteína (0-100%)</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Turno -->
                    <div class="col-md-6 mb-3">
                        <label for="turno" class="form-label">Turno <span class="text-danger">*</span></label>
                        <select class="form-select" id="turno" name="turno" required>
                            <option value="">Seleccionar Turno</option>
                            <option value="mañana" {{ old('turno') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="tarde" {{ old('turno') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noche" {{ old('turno') == 'noche' ? 'selected' : '' }}>Noche</option>
                        </select>
                        <small class="form-text text-muted">Turno de ordeño</small>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" 
                              id="observaciones" name="observaciones" 
                              rows="3" 
                              placeholder="Observaciones sobre la producción, condiciones del animal, etc.">{{ old('observaciones') }}</textarea>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('produccion-leche.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Guardar Producción
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
        const fechaInput = document.getElementById('fecha');
        const cantidadLeche = document.getElementById('cantidad_leche');
        const calidadGrasa = document.getElementById('calidad_grasa');
        const calidadProteina = document.getElementById('calidad_proteina');

        // Establecer fecha máxima (hoy)
        const today = new Date().toISOString().split('T')[0];
        fechaInput.max = today;

        // Validación de porcentajes
        function validarPorcentaje(input) {
            if (input.value) {
                const valor = parseFloat(input.value);
                if (valor < 0 || valor > 100) {
                    input.classList.add('is-invalid');
                    return false;
                } else {
                    input.classList.remove('is-invalid');
                    return true;
                }
            }
            return true;
        }

        calidadGrasa.addEventListener('blur', () => validarPorcentaje(calidadGrasa));
        calidadProteina.addEventListener('blur', () => validarPorcentaje(calidadProteina));

        // Validación del formulario
        document.getElementById('produccionForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Validar porcentajes
            if (!validarPorcentaje(calidadGrasa) || !validarPorcentaje(calidadProteina)) {
                valid = false;
            }

            // Validar cantidad de leche
            if (cantidadLeche.value && parseFloat(cantidadLeche.value) <= 0) {
                valid = false;
                cantidadLeche.classList.add('is-invalid');
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos correctamente.');
            }
        });
    });
</script>

<style>
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .invalid-feedback {
        display: block;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875em;
        color: #dc3545;
    }
</style>
@endsection                                                                