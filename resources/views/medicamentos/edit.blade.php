@extends('layouts.app')

@section('title', 'Editar Medicamento')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-warning"></i> Editar Medicamento
        </h1>
        <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Volver al Inventario</span>
        </a>
    </div>

    <!-- Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">
                <i class="fas fa-pencil-alt me-2"></i>Editar Información del Medicamento
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('medicamentos.update', $medicamento['id']) }}" id="medicamentoForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Finca -->
                    <div class="col-md-6 mb-3">
                        <label for="finca_id" class="form-label">Finca <span class="text-danger">*</span></label>
                        <select class="form-select" id="finca_id" name="finca_id" required>
                            <option value="">Seleccionar Finca</option>
                            @foreach($fincas as $finca)
                            <option value="{{ $finca['id'] }}" 
                                {{ (old('finca_id', $medicamento['finca_id']) == $finca['id']) ? 'selected' : '' }}>
                                {{ $finca['nombre'] }} - {{ $finca['zona'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre del Medicamento <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre', $medicamento['nombre']) }}" 
                               placeholder="Ej: Vacuna Fiebre Aftosa" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Tipo -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="">Seleccionar Tipo</option>
                            <option value="vacuna" {{ (old('tipo', $medicamento['tipo']) == 'vacuna') ? 'selected' : '' }}>Vacuna</option>
                            <option value="antibiotico" {{ (old('tipo', $medicamento['tipo']) == 'antibiotico') ? 'selected' : '' }}>Antibiótico</option>
                            <option value="vitaminas" {{ (old('tipo', $medicamento['tipo']) == 'vitaminas') ? 'selected' : '' }}>Vitaminas</option>
                            <option value="desparasitante" {{ (old('tipo', $medicamento['tipo']) == 'desparasitante') ? 'selected' : '' }}>Desparasitante</option>
                            <option value="otro" {{ (old('tipo', $medicamento['tipo']) == 'otro') ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <!-- Proveedor -->
                    <div class="col-md-6 mb-3">
                        <label for="proveedor" class="form-label">Proveedor</label>
                        <input type="text" class="form-control" 
                               id="proveedor" name="proveedor" 
                               value="{{ old('proveedor', $medicamento['proveedor']) }}" 
                               placeholder="Ej: Laboratorios Veterinarios S.A.">
                    </div>
                </div>

                <div class="row">
                    <!-- Stock Actual -->
                    <div class="col-md-4 mb-3">
                        <label for="stock_actual" class="form-label">Stock Actual <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" 
                               id="stock_actual" name="stock_actual" 
                               value="{{ old('stock_actual', $medicamento['stock_actual']) }}" 
                               min="0" required>
                        <small class="form-text text-muted">Cantidad disponible</small>
                    </div>

                    <!-- Stock Mínimo -->
                    <div class="col-md-4 mb-3">
                        <label for="stock_minimo" class="form-label">Stock Mínimo <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" 
                               id="stock_minimo" name="stock_minimo" 
                               value="{{ old('stock_minimo', $medicamento['stock_minimo']) }}" 
                               min="0" required>
                        <small class="form-text text-muted">Alerta cuando baje de esta cantidad</small>
                    </div>

                    <!-- Precio Unitario -->
                    <div class="col-md-4 mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario (Q)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="precio_unitario" name="precio_unitario" 
                               value="{{ old('precio_unitario', $medicamento['precio_unitario']) }}" 
                               min="0" placeholder="0.00">
                        <small class="form-text text-muted">Precio por unidad</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Fecha Vencimiento -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" 
                               id="fecha_vencimiento" name="fecha_vencimiento" 
                               value="{{ old('fecha_vencimiento', $medicamento['fecha_vencimiento']) }}"
                               min="{{ date('Y-m-d') }}">
                        <small class="form-text text-muted">Solo para productos perecederos</small>
                    </div>

                    <!-- Descripción -->
                    <div class="col-md-6 mb-3">
                        <label for="descripcion" class="form-label">Descripción</label>
                        <textarea class="form-control" 
                                  id="descripcion" name="descripcion" 
                                  rows="3" 
                                  placeholder="Descripción del medicamento, composición, etc.">{{ old('descripcion', $medicamento['descripcion']) }}</textarea>
                    </div>
                </div>

                <!-- Alertas de Stock -->
                <div class="alert alert-warning" id="stockAlert" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Advertencia:</strong> El stock actual es menor o igual al stock mínimo.
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('medicamentos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Actualizar Medicamento
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
        const stockActual = document.getElementById('stock_actual');
        const stockMinimo = document.getElementById('stock_minimo');
        const stockAlert = document.getElementById('stockAlert');
        const fechaVencimiento = document.getElementById('fecha_vencimiento');

        // Validar stock
        function validarStock() {
            const actual = parseInt(stockActual.value) || 0;
            const minimo = parseInt(stockMinimo.value) || 0;
            
            if (actual <= minimo) {
                stockAlert.style.display = 'block';
                stockActual.classList.add('is-invalid');
            } else {
                stockAlert.style.display = 'none';
                stockActual.classList.remove('is-invalid');
            }
        }

        stockActual.addEventListener('input', validarStock);
        stockMinimo.addEventListener('input', validarStock);

        // Validación del formulario
        document.getElementById('medicamentoForm').addEventListener('submit', function(e) {
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

            // Validar fecha de vencimiento
            if (fechaVencimiento.value) {
                const selectedDate = new Date(fechaVencimiento.value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                
                if (selectedDate <= today) {
                    valid = false;
                    fechaVencimiento.classList.add('is-invalid');
                    alert('La fecha de vencimiento debe ser futura.');
                } else {
                    fechaVencimiento.classList.remove('is-invalid');
                }
            }
            
            if (!valid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos correctamente.');
            }
        });

        // Validar fecha mínima (hoy)
        const today = new Date().toISOString().split('T')[0];
        if (fechaVencimiento) {
            fechaVencimiento.min = today;
        }

        // Validación inicial
        validarStock();
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