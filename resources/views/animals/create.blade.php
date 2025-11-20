@extends('layouts.app')

@section('title', 'Registrar Nuevo Animal')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-plus-circle text-success"></i> Registrar Nuevo Animal
        </h1>
        <a href="{{ route('animals.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver al Listado</span>
        </a>
    </div>

    <!-- Información de permisos para Veterinario -->
    @if(session('user.role') === 'veterinario')
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <i class="fas fa-user-md me-2"></i>
        <strong>Modo Veterinario:</strong> Estás registrando un nuevo animal en el sistema.
    </div>
    @endif

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-success">
                <i class="fas fa-info-circle me-2"></i>Información del Animal
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('animals.store') }}" id="animalForm">
                @csrf
                
                <div class="row">
                    <!-- Finca -->
                    <div class="col-md-6 mb-3">
                        <label for="finca_id" class="form-label">Finca <span class="text-danger">*</span></label>
                        <select class="form-select" id="finca_id" name="finca_id" required>
                            <option value="">Seleccionar Finca</option>
                            @foreach($fincas as $finca)
                            <option value="{{ $finca['id'] }}" 
                                {{ old('finca_id') == $finca['id'] ? 'selected' : '' }}>
                                {{ $finca['nombre'] }} - {{ $finca['zona'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Identificación -->
                    <div class="col-md-6 mb-3">
                        <label for="identificacion" class="form-label">
                            Identificación <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control" 
                               id="identificacion" name="identificacion" 
                               value="{{ old('identificacion') }}" 
                               placeholder="Ej: BOV-001" required>
                        <small class="form-text text-muted">Identificación única del animal</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre') }}" 
                               placeholder="Nombre del animal (opcional)">
                    </div>

                    <!-- Especie -->
                    <div class="col-md-6 mb-3">
                        <label for="especie" class="form-label">Especie <span class="text-danger">*</span></label>
                        <select class="form-select" id="especie" name="especie" required>
                            <option value="">Seleccionar Especie</option>
                            <option value="bovino" {{ old('especie') == 'bovino' ? 'selected' : '' }}>Bovino</option>
                            <option value="porcino" {{ old('especie') == 'porcino' ? 'selected' : '' }}>Porcino</option>
                            <option value="caprino" {{ old('especie') == 'caprino' ? 'selected' : '' }}>Caprino</option>
                            <option value="ovina" {{ old('especie') == 'ovina' ? 'selected' : '' }}>Ovina</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Raza -->
                    <div class="col-md-6 mb-3">
                        <label for="raza" class="form-label">Raza <span class="text-danger">*</span></label>
                        <select class="form-select" id="raza" name="raza" required>
                            <option value="">Seleccionar Raza</option>
                            <option value="holstein" {{ old('raza') == 'holstein' ? 'selected' : '' }}>Holstein</option>
                            <option value="brahman" {{ old('raza') == 'brahman' ? 'selected' : '' }}>Brahman</option>
                            <option value="angus" {{ old('raza') == 'angus' ? 'selected' : '' }}>Angus</option>
                            <option value="criolla" {{ old('raza') == 'criolla' ? 'selected' : '' }}>Criolla</option>
                            <option value="otra" {{ old('raza') == 'otra' ? 'selected' : '' }}>Otra</option>
                        </select>
                    </div>

                    <!-- Fecha Nacimiento -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_nacimiento" class="form-label">
                            Fecha de Nacimiento <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" 
                               id="fecha_nacimiento" name="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento') }}" required>
                        <small class="form-text text-muted">La fecha debe ser anterior a hoy</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Sexo -->
                    <div class="col-md-6 mb-3">
                        <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                        <select class="form-select" id="sexo" name="sexo" required>
                            <option value="">Seleccionar Sexo</option>
                            <option value="macho" {{ old('sexo') == 'macho' ? 'selected' : '' }}>Macho</option>
                            <option value="hembra" {{ old('sexo') == 'hembra' ? 'selected' : '' }}>Hembra</option>
                        </select>
                    </div>

                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-select" id="estado" name="estado" required>
                            <option value="">Seleccionar Estado</option>
                            <option value="activo" {{ old('estado', 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                            <option value="enfermo" {{ old('estado') == 'enfermo' ? 'selected' : '' }}>Enfermo</option>
                            <option value="vendido" {{ old('estado') == 'vendido' ? 'selected' : '' }}>Vendido</option>
                            <option value="muerto" {{ old('estado') == 'muerto' ? 'selected' : '' }}>Muerto</option>
                        </select>
                        <small class="form-text text-muted">Estado actual del animal</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Peso Inicial -->
                    <div class="col-md-6 mb-3">
                        <label for="peso_inicial" class="form-label">Peso Inicial (lbs)</label>
                        <input type="number" step="0.01" min="0" class="form-control" 
                               id="peso_inicial" name="peso_inicial" 
                               value="{{ old('peso_inicial') }}" 
                               placeholder="Ej: 350.50">
                        <small class="form-text text-muted">Peso en libras al registro (opcional)</small>
                    </div>

                    <!-- Peso Actual -->
                    <div class="col-md-6 mb-3">
                        <label for="peso_actual" class="form-label">Peso Actual (lbs)</label>
                        <input type="number" step="0.01" min="0" class="form-control" 
                               id="peso_actual" name="peso_actual" 
                               value="{{ old('peso_actual') }}" 
                               placeholder="Ej: 420.75">
                        <small class="form-text text-muted">Peso actual en libras (opcional)</small>
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control" 
                              id="observaciones" name="observaciones" 
                              rows="3" placeholder="Observaciones adicionales sobre el animal...">{{ old('observaciones') }}</textarea>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('animals.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Guardar Animal
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
        // Establecer fecha máxima como hoy
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('fecha_nacimiento').max = today;
        
        // Validación del formulario
        document.getElementById('animalForm').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('[required]');
            let valid = true;
            
            console.log('Validando formulario...');
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    valid = false;
                    field.classList.add('is-invalid');
                    console.log(`Campo requerido vacío: ${field.name}`);
                } else {
                    field.classList.remove('is-invalid');
                }
            });

            // Validación adicional de fecha
            const fechaField = document.getElementById('fecha_nacimiento');
            if (fechaField.value) {
                const selectedDate = new Date(fechaField.value);
                const today = new Date();
                if (selectedDate > today) {
                    valid = false;
                    fechaField.classList.add('is-invalid');
                    console.log('Fecha futura no permitida');
                    alert('La fecha de nacimiento no puede ser futura.');
                }
            }

            // Validación de pesos
            const pesoInicial = document.getElementById('peso_inicial');
            const pesoActual = document.getElementById('peso_actual');
            
            if (pesoInicial.value && parseFloat(pesoInicial.value) <= 0) {
                valid = false;
                pesoInicial.classList.add('is-invalid');
                alert('El peso inicial debe ser mayor a 0.');
            }
            
            if (pesoActual.value && parseFloat(pesoActual.value) <= 0) {
                valid = false;
                pesoActual.classList.add('is-invalid');
                alert('El peso actual debe ser mayor a 0.');
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos correctamente.');
            } else {
                console.log('Formulario válido, enviando...');
            }
        });

    });
</script>
@endsection