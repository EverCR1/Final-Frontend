@extends('layouts.app')

@section('title', 'Crear Nueva Dieta')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-success"></i> Crear Nueva Dieta
        </h1>
        <a href="{{ route('alimentacion.dietas.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver a Dietas</span>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-info-circle me-2"></i>Información de la Dieta
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('alimentacion.dietas.store') }}" id="dietaForm">
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

                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre de la Dieta <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre') }}" 
                               placeholder="Ej: Dieta Alta Producción Lechera" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Tipo Animal -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo_animal" class="form-label">Tipo de Animal <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipo_animal" name="tipo_animal" required>
                            <option value="">Seleccionar Tipo</option>
                            <option value="bovino" {{ old('tipo_animal') == 'bovino' ? 'selected' : '' }}>Bovino</option>
                            <option value="porcino" {{ old('tipo_animal') == 'porcino' ? 'selected' : '' }}>Porcino</option>
                            <option value="caprino" {{ old('tipo_animal') == 'caprino' ? 'selected' : '' }}>Caprino</option>
                            <option value="ovina" {{ old('tipo_animal') == 'ovina' ? 'selected' : '' }}>Ovina</option>
                        </select>
                    </div>

                    <!-- Categoría -->
                    <div class="col-md-6 mb-3">
                        <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                        <select class="form-select" id="categoria" name="categoria" required>
                            <option value="">Seleccionar Categoría</option>
                            <option value="ternero" {{ old('categoria') == 'ternero' ? 'selected' : '' }}>Ternero</option>
                            <option value="desarrollo" {{ old('categoria') == 'desarrollo' ? 'selected' : '' }}>Desarrollo</option>
                            <option value="adulto" {{ old('categoria') == 'adulto' ? 'selected' : '' }}>Adulto</option>
                            <option value="lactancia" {{ old('categoria') == 'lactancia' ? 'selected' : '' }}>Lactancia</option>
                            <option value="gestacion" {{ old('categoria') == 'gestacion' ? 'selected' : '' }}>Gestación</option>
                        </select>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" 
                              id="descripcion" name="descripcion" 
                              rows="3" 
                              placeholder="Descripción de la dieta, objetivos nutricionales, etc.">{{ old('descripcion') }}</textarea>
                </div>

                <!-- Estado Activa -->
                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="activa" name="activa" value="1" {{ old('activa', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="activa">
                            <strong>Dieta Activa</strong>
                        </label>
                    </div>
                    <small class="form-text text-muted">Las dietas inactivas no estarán disponibles para nuevos registros</small>
                </div>

                <hr class="my-4">

                <!-- Sección de Alimentos -->
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="m-0 font-weight-bold text-success">
                            <i class="fas fa-apple-alt me-2"></i>Alimentos de la Dieta
                        </h6>
                        <button type="button" class="btn btn-success btn-sm" id="addAlimento">
                            <i class="fas fa-plus me-1"></i>Agregar Alimento
                        </button>
                    </div>
                    
                    <div id="alimentosContainer">
                        <!-- Los alimentos se agregarán dinámicamente aquí -->
                    </div>

                    <div class="alert alert-warning" id="noAlimentosAlert">
                        <i class="fas fa-info-circle me-2"></i>
                        Agregue al menos un alimento a la dieta
                    </div>
                </div>

                <!-- Resumen de la Dieta -->
                <div class="alert alert-info" id="infoResumen" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Resumen:</strong> 
                    <span id="resumenText"></span>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('alimentacion.dietas.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Guardar Dieta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Template para alimento -->
<template id="alimentoTemplate">
    <div class="card alimento-item mb-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4 mb-2">
                    <label class="form-label">Alimento <span class="text-danger">*</span></label>
                    <select class="form-select alimento-select" name="alimentos[INDEX][alimento_id]" required>
                        <option value="">Seleccionar Alimento</option>
                        @foreach($data['alimentos'] as $alimento)
                        <option value="{{ $alimento['id'] }}" data-precio="{{ $alimento['precio_unitario'] ?? 0 }}" data-unidad="{{ $alimento['unidad_medida'] }}">
                            {{ $alimento['nombre'] }} ({{ $alimento['unidad_medida'] }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Cantidad <span class="text-danger">*</span></label>
                    <input type="number" step="0.01" min="0" class="form-control cantidad-input" 
                           name="alimentos[INDEX][cantidad]" placeholder="0.00" required>
                    <small class="form-text text-muted unidad-text"></small>
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label">Frecuencia <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" 
                           name="alimentos[INDEX][frecuencia]" 
                           placeholder="Ej: diaria, 2 veces al día" required>
                </div>
                <div class="col-md-2 mb-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-alimento w-100">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <input type="text" class="form-control mt-2" 
                           name="alimentos[INDEX][observaciones]" 
                           placeholder="Observaciones (opcional)">
                </div>
            </div>
        </div>
    </div>
</template>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const alimentosContainer = document.getElementById('alimentosContainer');
        const noAlimentosAlert = document.getElementById('noAlimentosAlert');
        const addAlimentoBtn = document.getElementById('addAlimento');
        const alimentoTemplate = document.getElementById('alimentoTemplate');
        const infoResumen = document.getElementById('infoResumen');
        const resumenText = document.getElementById('resumenText');
        const nombre = document.getElementById('nombre');
        const tipoAnimal = document.getElementById('tipo_animal');
        const categoria = document.getElementById('categoria');
        
        let alimentoCount = 0;

        // Agregar alimento
        function addAlimento() {
            const template = alimentoTemplate.innerHTML.replace(/INDEX/g, alimentoCount);
            const div = document.createElement('div');
            div.innerHTML = template;
            alimentosContainer.appendChild(div);
            
            alimentoCount++;
            updateAlimentosView();
            attachAlimentoEvents(div);
            actualizarResumen();
        }

        // Actualizar vista de alimentos
        function updateAlimentosView() {
            if (alimentoCount > 0) {
                noAlimentosAlert.style.display = 'none';
            } else {
                noAlimentosAlert.style.display = 'block';
            }
        }

        // Adjuntar eventos a un alimento
        function attachAlimentoEvents(alimentoDiv) {
            // Remover alimento
            const removeBtn = alimentoDiv.querySelector('.remove-alimento');
            removeBtn.addEventListener('click', function() {
                alimentoDiv.remove();
                alimentoCount--;
                updateAlimentosView();
                actualizarResumen();
            });

            // Actualizar unidad cuando se selecciona alimento
            const alimentoSelect = alimentoDiv.querySelector('.alimento-select');
            const unidadText = alimentoDiv.querySelector('.unidad-text');
            
            alimentoSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const unidad = selectedOption.getAttribute('data-unidad') || '';
                unidadText.textContent = unidad;
                actualizarResumen();
            });

            // Actualizar resumen cuando cambia cantidad
            const cantidadInput = alimentoDiv.querySelector('.cantidad-input');
            cantidadInput.addEventListener('input', actualizarResumen);
        }

        // Actualizar resumen
        function actualizarResumen() {
            const nombreVal = nombre.value || '[Nombre no definido]';
            const tipoAnimalVal = tipoAnimal.options[tipoAnimal.selectedIndex]?.text || '[Tipo no seleccionado]';
            const categoriaVal = categoria.options[categoria.selectedIndex]?.text || '[Categoría no seleccionada]';
            
            let alimentosInfo = '';
            let costoTotal = 0;
            
            // Calcular costo total
            document.querySelectorAll('.alimento-item').forEach(item => {
                const alimentoSelect = item.querySelector('.alimento-select');
                const cantidadInput = item.querySelector('.cantidad-input');
                
                if (alimentoSelect.value && cantidadInput.value) {
                    const selectedOption = alimentoSelect.options[alimentoSelect.selectedIndex];
                    const alimentoNombre = selectedOption.text.split(' (')[0];
                    const cantidad = parseFloat(cantidadInput.value) || 0;
                    const precio = parseFloat(selectedOption.getAttribute('data-precio')) || 0;
                    const unidad = selectedOption.getAttribute('data-unidad') || '';
                    
                    alimentosInfo += `<br>• ${alimentoNombre}: ${cantidad} ${unidad}`;
                    costoTotal += cantidad * precio;
                }
            });

            if (nombre.value && tipoAnimal.value && categoria.value && alimentoCount > 0) {
                resumenText.innerHTML = `
                    <strong>${nombreVal}</strong> - ${tipoAnimalVal} (${categoriaVal})
                    ${alimentosInfo}
                    ${costoTotal > 0 ? `<br><strong>Costo estimado por kg:</strong> Q${costoTotal.toFixed(2)}` : ''}
                `;
                infoResumen.style.display = 'block';
            } else {
                infoResumen.style.display = 'none';
            }
        }

        // Event listeners
        addAlimentoBtn.addEventListener('click', addAlimento);
        nombre.addEventListener('input', actualizarResumen);
        tipoAnimal.addEventListener('change', actualizarResumen);
        categoria.addEventListener('change', actualizarResumen);

        // Validación del formulario
        document.getElementById('dietaForm').addEventListener('submit', function(e) {
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

            // Validar que haya al menos un alimento
            if (alimentoCount === 0) {
                valid = false;
                alert('Debe agregar al menos un alimento a la dieta.');
            }

            // Validar que todos los alimentos tengan los campos requeridos
            const alimentosInvalidos = document.querySelectorAll('.alimento-item').length === 0 || 
                Array.from(document.querySelectorAll('.alimento-item')).some(item => {
                    const alimentoSelect = item.querySelector('.alimento-select');
                    const cantidadInput = item.querySelector('.cantidad-input');
                    const frecuenciaInput = item.querySelector('input[name*="frecuencia"]');
                    
                    return !alimentoSelect.value || !cantidadInput.value || !frecuenciaInput.value;
                });

            if (alimentosInvalidos) {
                valid = false;
                alert('Complete todos los campos requeridos para cada alimento.');
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos correctamente.');
            }
        });

        // Agregar un alimento inicial si no hay datos antiguos
        if (alimentoCount === 0) {
            addAlimento();
        }

        // Inicializar resumen
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
    
    .alimento-item {
        border-left: 4px solid #28a745 !important;
    }
    
    .remove-alimento {
        transition: all 0.3s ease;
    }
    
    .remove-alimento:hover {
        transform: scale(1.1);
    }
</style>
@endsection