@extends('layouts.app')

@section('title', 'Editar Animal')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-warning"></i> Editar Animal
        </h1>
        <a href="{{ route('animals.index') }}" class="btn btn-secondary btn-icon-split">
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
                <i class="fas fa-pencil-alt me-2"></i>Editar Información del Animal
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('animals.update', $animal['id']) }}" id="animalForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Finca -->
                    <div class="col-md-6 mb-3">
                        <label for="finca_id" class="form-label">Finca <span class="text-danger">*</span></label>
                        <select class="form-select @error('finca_id') is-invalid @enderror" 
                                id="finca_id" name="finca_id" required>
                            <option value="">Seleccionar Finca</option>
                            @foreach($fincas as $finca)
                            <option value="{{ $finca['id'] }}" 
                                {{ (old('finca_id', $animal['finca_id']) == $finca['id']) ? 'selected' : '' }}>
                                {{ $finca['nombre'] }} - {{ $finca['zona'] }}
                            </option>
                            @endforeach
                        </select>
                        @error('finca_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Identificación -->
                    <div class="col-md-6 mb-3">
                        <label for="identificacion" class="form-label">
                            Identificación <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('identificacion') is-invalid @enderror" 
                               id="identificacion" name="identificacion" 
                               value="{{ old('identificacion', $animal['identificacion']) }}" 
                               placeholder="Ej: BOV-001" required>
                        @error('identificacion')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre', $animal['nombre']) }}" 
                               placeholder="Nombre del animal">
                        @error('nombre')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Especie -->
                    <div class="col-md-6 mb-3">
                        <label for="especie" class="form-label">Especie <span class="text-danger">*</span></label>
                        <select class="form-select @error('especie') is-invalid @enderror" 
                                id="especie" name="especie" required>
                            <option value="">Seleccionar Especie</option>
                            <option value="bovino" {{ (old('especie', $animal['especie']) == 'bovino') ? 'selected' : '' }}>Bovino</option>
                            <option value="porcino" {{ (old('especie', $animal['especie']) == 'porcino') ? 'selected' : '' }}>Porcino</option>
                            <option value="caprino" {{ (old('especie', $animal['especie']) == 'caprino') ? 'selected' : '' }}>Caprino</option>
                            <option value="ovina" {{ (old('especie', $animal['especie']) == 'ovina') ? 'selected' : '' }}>Ovina</option>
                        </select>
                        @error('especie')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        <input type="date" class="form-control @error('fecha_nacimiento') is-invalid @enderror" 
                               id="fecha_nacimiento" name="fecha_nacimiento" 
                               value="{{ old('fecha_nacimiento', $animal['fecha_nacimiento']) }}" required>
                        @error('fecha_nacimiento')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Sexo -->
                    <div class="col-md-6 mb-3">
                        <label for="sexo" class="form-label">Sexo <span class="text-danger">*</span></label>
                        <select class="form-select @error('sexo') is-invalid @enderror" 
                                id="sexo" name="sexo" required>
                            <option value="">Seleccionar Sexo</option>
                            <option value="macho" {{ (old('sexo', $animal['sexo']) == 'macho') ? 'selected' : '' }}>Macho</option>
                            <option value="hembra" {{ (old('sexo', $animal['sexo']) == 'hembra') ? 'selected' : '' }}>Hembra</option>
                        </select>
                        @error('sexo')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Estado -->
                    <div class="col-md-6 mb-3">
                        <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                        <select class="form-select @error('estado') is-invalid @enderror" 
                                id="estado" name="estado" required>
                            <option value="">Seleccionar Estado</option>
                            <option value="activo" {{ (old('estado', $animal['estado']) == 'activo') ? 'selected' : '' }}>Activo</option>
                            <option value="enfermo" {{ (old('estado', $animal['estado']) == 'enfermo') ? 'selected' : '' }}>Enfermo</option>
                            <option value="vendido" {{ (old('estado', $animal['estado']) == 'vendido') ? 'selected' : '' }}>Vendido</option>
                            <option value="muerto" {{ (old('estado', $animal['estado']) == 'muerto') ? 'selected' : '' }}>Muerto</option>
                        </select>
                        @error('estado')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <!-- Peso Inicial -->
                    <div class="col-md-6 mb-3">
                        <label for="peso_inicial" class="form-label">Peso Inicial (kg)</label>
                        <input type="number" step="0.01" class="form-control @error('peso_inicial') is-invalid @enderror" 
                               id="peso_inicial" name="peso_inicial" 
                               value="{{ old('peso_inicial', $animal['peso_inicial']) }}" 
                               placeholder="Ej: 350.50">
                        @error('peso_inicial')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Observaciones -->
                <div class="mb-3">
                    <label for="observaciones" class="form-label">Observaciones</label>
                    <textarea class="form-control @error('observaciones') is-invalid @enderror" 
                              id="observaciones" name="observaciones" 
                              rows="3" placeholder="Observaciones adicionales...">{{ old('observaciones', $animal['observaciones']) }}</textarea>
                    @error('observaciones')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('animals.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Actualizar Animal
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
    });
</script>
@endsection