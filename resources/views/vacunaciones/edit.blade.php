@extends('layouts.app')

@section('title', 'Editar Vacunación')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-warning"></i> Editar Vacunación
        </h1>
        <a href="{{ route('vacunaciones.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver al Historial</span>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-pencil-alt me-2"></i>Editar Información de la Vacunación
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('vacunaciones.update', $vacunacion['id']) }}" id="vacunacionForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Animal -->
                    <div class="col-md-6 mb-3">
                        <label for="animal_id" class="form-label">Animal <span class="text-danger">*</span></label>
                        <select class="form-select" id="animal_id" name="animal_id" required>
                            <option value="">Seleccionar Animal</option>
                            @foreach($animales as $animal)
                            <option value="{{ $animal['id'] }}" 
                                {{ (old('animal_id', $vacunacion['animal_id']) == $animal['id']) ? 'selected' : '' }}>
                                {{ $animal['identificacion'] }} - {{ $animal['nombre'] ?: 'Sin nombre' }} 
                                ({{ $animal['especie'] }} - {{ $animal['finca']['nombre'] ?? 'N/A' }})
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Seleccione el animal vacunado</small>
                    </div>

                    <!-- Medicamento -->
                    <div class="col-md-6 mb-3">
                        <label for="medicamento_id" class="form-label">Medicamento <span class="text-danger">*</span></label>
                        <select class="form-select" id="medicamento_id" name="medicamento_id" required>
                            <option value="">Seleccionar Medicamento</option>
                            @foreach($medicamentos as $medicamento)
                            <option value="{{ $medicamento['id'] }}" 
                                    data-stock="{{ $medicamento['stock_actual'] }}"
                                    {{ (old('medicamento_id', $vacunacion['medicamento_id']) == $medicamento['id']) ? 'selected' : '' }}>
                                {{ $medicamento['nombre'] }} ({{ $medicamento['tipo'] }})
                                - Stock: {{ $medicamento['stock_actual'] }}
                                @if($medicamento['stock_actual'] <= $medicamento['stock_minimo'])
                                ⚠️
                                @endif
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Stock disponible se muestra entre paréntesis</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Vacuna -->
                    <div class="col-md-6 mb-3">
                        <label for="vacuna" class="form-label">Nombre de la Vacuna <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="vacuna" name="vacuna" 
                               value="{{ old('vacuna', $vacunacion['vacuna']) }}" 
                               placeholder="Ej: Vacuna Fiebre Aftosa" required>
                    </div>

                    <!-- Lote -->
                    <div class="col-md-6 mb-3">
                        <label for="lote" class="form-label">Número de Lote <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="lote" name="lote" 
                               value="{{ old('lote', $vacunacion['lote']) }}" 
                               placeholder="Ej: LOTE-2024-001" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Fecha Vacunación -->
                    <div class="col-md-4 mb-3">
                        <label for="fecha_vacunacion" class="form-label">Fecha de Vacunación <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" 
                               id="fecha_vacunacion" name="fecha_vacunacion" 
                               value="{{ old('fecha_vacunacion', $vacunacion['fecha_vacunacion']) }}" 
                               max="{{ date('Y-m-d') }}" required>
                        <small class="form-text text-muted">Fecha cuando se aplicó la vacuna</small>
                    </div>

                    <!-- Fecha Próxima -->
                    <div class="col-md-4 mb-3">
                        <label for="fecha_proxima" class="form-label">Próxima Vacunación</label>
                        <input type="date" class="form-control" 
                               id="fecha_proxima" name="fecha_proxima" 
                               value="{{ old('fecha_proxima', $vacunacion['fecha_proxima']) }}">
                        <small class="form-text text-muted">Fecha para siguiente dosis (opcional)</small>
                    </div>

                    <!-- Dosis -->
                    <div class="col-md-4 mb-3">
                        <label for="dosis" class="form-label">Dosis (ml) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control" 
                               id="dosis" name="dosis" 
                               value="{{ old('dosis', $vacunacion['dosis']) }}" 
                               min="0.01" placeholder="0.00" required>
                        <small class="form-text text-muted">Cantidad en mililitros</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Veterinario -->
                    <div class="col-md-6 mb-3">
                        <label for="veterinario" class="form-label">Veterinario Responsable <span class="text-danger">*</span></label>
                        <select class="form-select" id="veterinario" name="veterinario" required>
                            <option value="">Seleccionar Veterinario</option>
                            @foreach($veterinarios as $vet)
                            <option value="{{ $vet['name'] }}" 
                                {{ (old('veterinario', $vacunacion['veterinario']) == $vet['name']) ? 'selected' : '' }}>
                                {{ $vet['name'] }} 
                                @if($vet['email'])
                                - {{ $vet['email'] }}
                                @endif
                            </option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Seleccione el veterinario responsable</small>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" 
                              id="observaciones" name="observaciones" 
                              rows="3" 
                              placeholder="Observaciones adicionales sobre la vacunación...">{{ old('observaciones', $vacunacion['observaciones']) }}</textarea>
                </div>

                <!-- Alertas -->
                <div class="alert alert-warning" id="stockAlert" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Advertencia:</strong> El stock del medicamento seleccionado es bajo.
                </div>

                <div class="alert alert-danger" id="dosisAlert" style="display: none;">
                    <i class="fas fa-times-circle me-2"></i>
                    <strong>Error:</strong> La dosis ingresada es mayor al stock disponible.
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('vacunaciones.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Actualizar Vacunación
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
        const medicamentoSelect = document.getElementById('medicamento_id');
        const dosisInput = document.getElementById('dosis');
        const stockAlert = document.getElementById('stockAlert');
        const dosisAlert = document.getElementById('dosisAlert');
        const fechaVacunacion = document.getElementById('fecha_vacunacion');
        const fechaProxima = document.getElementById('fecha_proxima');

        // Validar stock y dosis
        function validarStockYDosis() {
            const selectedOption = medicamentoSelect.options[medicamentoSelect.selectedIndex];
            const stockDisponible = selectedOption ? parseInt(selectedOption.getAttribute('data-stock')) : 0;
            const dosis = parseFloat(dosisInput.value) || 0;

            // Ocultar alertas inicialmente
            stockAlert.style.display = 'none';
            dosisAlert.style.display = 'none';

            if (selectedOption && stockDisponible > 0) {
                // Mostrar alerta de stock bajo
                if (stockDisponible <= 10) {
                    stockAlert.style.display = 'block';
                }

                // Validar que la dosis no sea mayor al stock
                if (dosis > stockDisponible) {
                    dosisAlert.style.display = 'block';
                    dosisInput.classList.add('is-invalid');
                } else {
                    dosisInput.classList.remove('is-invalid');
                }
            }
        }

        // Event listeners
        medicamentoSelect.addEventListener('change', validarStockYDosis);
        dosisInput.addEventListener('input', validarStockYDosis);

        // Validar que fecha próxima sea después de fecha vacunación
        fechaVacunacion.addEventListener('change', function() {
            if (fechaProxima.value) {
                const fechaVac = new Date(this.value);
                const fechaProx = new Date(fechaProxima.value);
                
                if (fechaProx <= fechaVac) {
                    fechaProxima.classList.add('is-invalid');
                    alert('La fecha próxima debe ser posterior a la fecha de vacunación.');
                } else {
                    fechaProxima.classList.remove('is-invalid');
                }
            }
        });

        fechaProxima.addEventListener('change', function() {
            if (fechaVacunacion.value) {
                const fechaVac = new Date(fechaVacunacion.value);
                const fechaProx = new Date(this.value);
                
                if (fechaProx <= fechaVac) {
                    this.classList.add('is-invalid');
                    alert('La fecha próxima debe ser posterior a la fecha de vacunación.');
                } else {
                    this.classList.remove('is-invalid');
                }
            }
        });

        // Validación del formulario
        document.getElementById('vacunacionForm').addEventListener('submit', function(e) {
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

            // Validar dosis vs stock
            const selectedOption = medicamentoSelect.options[medicamentoSelect.selectedIndex];
            const stockDisponible = selectedOption ? parseInt(selectedOption.getAttribute('data-stock')) : 0;
            const dosis = parseFloat(dosisInput.value) || 0;

            if (dosis > stockDisponible) {
                valid = false;
                dosisAlert.style.display = 'block';
            }

            // Validar fechas
            if (fechaProxima.value && fechaVacunacion.value) {
                const fechaVac = new Date(fechaVacunacion.value);
                const fechaProx = new Date(fechaProxima.value);
                
                if (fechaProx <= fechaVac) {
                    valid = false;
                    fechaProxima.classList.add('is-invalid');
                    alert('La fecha próxima debe ser posterior a la fecha de vacunación.');
                }
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos correctamente.');
            }
        });

        // Establecer fecha máxima para vacunación (hoy)
        const today = new Date().toISOString().split('T')[0];
        fechaVacunacion.max = today;

        // Validación inicial
        validarStockYDosis();
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