@extends('layouts.app')

@section('title', 'Registrar Alimentación')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-primary"></i> Registrar Alimentación
        </h1>
        <a href="{{ route('alimentacion.registros.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver a Registros</span>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-info-circle me-2"></i>Información del Registro
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('alimentacion.registros.store') }}" id="registroForm">
                @csrf
                
                <div class="row">
                    <!-- Finca -->
                    <div class="col-md-6 mb-3">
                        <label for="finca_id" class="form-label">Finca <span class="text-danger">*</span></label>
                        <select class="form-select" id="finca_id" name="finca_id" required>
                            <option value="">Seleccionar Finca</option>
                            @foreach($data['fincas'] as $finca)
                            <option value="{{ $finca['id'] }}" {{ old('finca_id') == $finca['id'] ? 'selected' : '' }}>
                                {{ $finca['nombre'] }} - {{ $finca['zona'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Fecha -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha" class="form-label">Fecha <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="fecha" name="fecha" 
                               value="{{ old('fecha', date('Y-m-d')) }}" 
                               max="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Animal (Opcional) -->
                    <div class="col-md-6 mb-3">
                        <label for="animal_id" class="form-label">Animal (Opcional)</label>
                        <select class="form-select" id="animal_id" name="animal_id">
                            <option value="">Seleccionar Animal (Opcional)</option>
                            <option value="">-- Alimentación Grupal --</option>
                            @foreach($data['animales'] as $animal)
                            <option value="{{ $animal['id'] }}" {{ old('animal_id') == $animal['id'] ? 'selected' : '' }}>
                                {{ $animal['identificacion'] }} - {{ $animal['nombre'] ?: 'Sin nombre' }} ({{ $animal['especie'] }})
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Dejar vacío para alimentación grupal</small>
                    </div>

                    <!-- Turno -->
                    <div class="col-md-6 mb-3">
                        <label for="turno" class="form-label">Turno <span class="text-danger">*</span></label>
                        <select class="form-select" id="turno" name="turno" required>
                            <option value="">Seleccionar Turno</option>
                            <option value="mañana" {{ old('turno') == 'mañana' ? 'selected' : '' }}>Mañana</option>
                            <option value="tarde" {{ old('turno') == 'tarde' ? 'selected' : '' }}>Tarde</option>
                            <option value="noche" {{ old('turno') == 'noche' ? 'selected' : '' }}>Noche</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Dieta -->
                    <div class="col-md-6 mb-3">
                        <label for="dieta_id" class="form-label">Dieta <span class="text-danger">*</span></label>
                        <select class="form-select" id="dieta_id" name="dieta_id" required>
                            <option value="">Seleccionar Dieta</option>
                            @foreach($data['dietas'] as $dieta)
                            @if($dieta['activa'])
                            <option value="{{ $dieta['id'] }}" 
                                    data-costo="{{ $dieta['costo_estimado_kg'] ?? 0 }}"
                                    {{ old('dieta_id') == $dieta['id'] ? 'selected' : '' }}>
                                {{ $dieta['nombre'] }} ({{ $dieta['tipo_animal'] }} - {{ $dieta['categoria'] }})
                                @if($dieta['costo_estimado_kg'])
                                - Q{{ number_format($dieta['costo_estimado_kg'], 2) }}/kg
                                @endif
                            </option>
                            @endif
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Solo se muestran dietas activas</small>
                    </div>

                    <!-- Cantidad Total -->
                    <div class="col-md-6 mb-3">
                        <label for="cantidad_total" class="form-label">Cantidad Total (kg) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0.1" class="form-control" 
                               id="cantidad_total" name="cantidad_total" 
                               value="{{ old('cantidad_total') }}" 
                               placeholder="0.00" required>
                        <small class="form-text text-muted">Cantidad en kilogramos a aplicar</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Responsable -->
                    <div class="col-md-6 mb-3">
                        <label for="responsable" class="form-label">Responsable <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="responsable" name="responsable" 
                               value="{{ old('responsable', session('user.name')) }}" 
                               placeholder="Nombre del responsable" required>
                    </div>

                    <!-- Costo Total (Calculado) -->
                    <div class="col-md-6 mb-3">
                        <label for="costo_total" class="form-label">Costo Total (Q)</label>
                        <input type="number" step="0.01" min="0" class="form-control" 
                               id="costo_total" name="costo_total" 
                               value="{{ old('costo_total') }}" 
                               placeholder="0.00" readonly>
                        <small class="form-text text-muted">Calculado automáticamente</small>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" 
                              id="observaciones" name="observaciones" 
                              rows="3" 
                              placeholder="Observaciones sobre la alimentación, comportamiento del animal, etc.">{{ old('observaciones') }}</textarea>
                </div>

                <!-- Información de la Dieta Seleccionada -->
                <div class="alert alert-info" id="dietaInfo" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Información de la dieta:</strong>
                    <span id="dietaInfoText"></span>
                </div>

                <!-- Resumen del Registro -->
                <div class="alert alert-success" id="resumenRegistro" style="display: none;">
                    <i class="fas fa-clipboard-check me-2"></i>
                    <strong>Resumen del registro:</strong>
                    <span id="resumenText"></span>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('alimentacion.registros.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Guardar Registro
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
        const dietaSelect = document.getElementById('dieta_id');
        const cantidadInput = document.getElementById('cantidad_total');
        const costoInput = document.getElementById('costo_total');
        const fechaInput = document.getElementById('fecha');
        const turnoSelect = document.getElementById('turno');
        const animalSelect = document.getElementById('animal_id');
        const responsableInput = document.getElementById('responsable');
        const dietaInfo = document.getElementById('dietaInfo');
        const dietaInfoText = document.getElementById('dietaInfoText');
        const resumenRegistro = document.getElementById('resumenRegistro');
        const resumenText = document.getElementById('resumenText');

        // Calcular costo automáticamente
        function calcularCosto() {
            const dietaOption = dietaSelect.options[dietaSelect.selectedIndex];
            const costoPorKg = parseFloat(dietaOption.getAttribute('data-costo')) || 0;
            const cantidad = parseFloat(cantidadInput.value) || 0;
            const costoTotal = costoPorKg * cantidad;
            
            costoInput.value = costoTotal.toFixed(2);
            actualizarResumen();
        }

        // Actualizar información de la dieta
        function actualizarInfoDieta() {
            const dietaOption = dietaSelect.options[dietaSelect.selectedIndex];
            if (dietaSelect.value && dietaOption) {
                const dietaNombre = dietaOption.text.split(' (')[0];
                const costoPorKg = parseFloat(dietaOption.getAttribute('data-costo')) || 0;
                
                dietaInfoText.innerHTML = `
                    <strong>${dietaNombre}</strong> | 
                    Costo por kg: <strong>Q${costoPorKg.toFixed(2)}</strong>
                `;
                dietaInfo.style.display = 'block';
            } else {
                dietaInfo.style.display = 'none';
            }
            calcularCosto();
        }

        // Actualizar resumen del registro
        function actualizarResumen() {
            const fecha = fechaInput.value ? new Date(fechaInput.value).toLocaleDateString('es-ES') : '[Fecha no seleccionada]';
            const turno = turnoSelect.options[turnoSelect.selectedIndex]?.text || '[Turno no seleccionado]';
            const dieta = dietaSelect.options[dietaSelect.selectedIndex]?.text.split(' (')[0] || '[Dieta no seleccionada]';
            const animal = animalSelect.value ? animalSelect.options[animalSelect.selectedIndex]?.text : 'Alimentación Grupal';
            const cantidad = cantidadInput.value || '0';
            const costo = costoInput.value || '0';
            const responsable = responsableInput.value || '[Responsable no definido]';

            if (fechaInput.value && turnoSelect.value && dietaSelect.value && cantidadInput.value && responsableInput.value) {
                resumenText.innerHTML = `
                    <strong>${fecha}</strong> - ${turno}<br>
                    <strong>Dieta:</strong> ${dieta} | <strong>Animal:</strong> ${animal}<br>
                    <strong>Cantidad:</strong> ${cantidad} kg | <strong>Costo:</strong> Q${parseFloat(costo).toFixed(2)}<br>
                    <strong>Responsable:</strong> ${responsable}
                `;
                resumenRegistro.style.display = 'block';
            } else {
                resumenRegistro.style.display = 'none';
            }
        }

        // Event listeners
        dietaSelect.addEventListener('change', function() {
            actualizarInfoDieta();
            actualizarResumen();
        });

        cantidadInput.addEventListener('input', function() {
            calcularCosto();
            actualizarResumen();
        });

        fechaInput.addEventListener('change', actualizarResumen);
        turnoSelect.addEventListener('change', actualizarResumen);
        animalSelect.addEventListener('change', actualizarResumen);
        responsableInput.addEventListener('input', actualizarResumen);

        // Validación del formulario
        document.getElementById('registroForm').addEventListener('submit', function(e) {
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

            // Validar cantidad mínima
            if (parseFloat(cantidadInput.value) < 0.1) {
                valid = false;
                cantidadInput.classList.add('is-invalid');
                alert('La cantidad debe ser al menos 0.1 kg.');
            }

            // Validar fecha no futura
            const selectedDate = new Date(fechaInput.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (selectedDate > today) {
                valid = false;
                fechaInput.classList.add('is-invalid');
                alert('La fecha no puede ser futura.');
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos correctamente.');
            }
        });

        // Establecer fecha máxima como hoy
        const today = new Date().toISOString().split('T')[0];
        fechaInput.max = today;

        // Inicializar
        actualizarInfoDieta();
        actualizarResumen();
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
    
    .form-text {
        font-size: 0.875em;
        color: #6c757d;
    }
    
    #costo_total {
        background-color: #f8f9fa;
        font-weight: bold;
    }
</style>
@endsection