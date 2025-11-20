@extends('layouts.app')

@section('title', 'Editar Alimento')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-edit text-warning"></i> Editar Alimento
        </h1>
        <a href="{{ route('alimentacion.alimentos.index') }}" class="btn btn-secondary btn-icon-split">
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
                <i class="fas fa-info-circle me-2"></i>Información del Alimento
            </h6>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('alimentacion.alimentos.update', $alimento['id']) }}" id="alimentoForm">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <!-- Finca -->
                    <div class="col-md-6 mb-3">
                        <label for="finca_id" class="form-label">Finca <span class="text-danger">*</span></label>
                        <select class="form-select" id="finca_id" name="finca_id" required>
                            <option value="">Seleccionar Finca</option>
                            @foreach($fincas as $finca)
                            <option value="{{ $finca['id'] }}" {{ $alimento['finca_id'] == $finca['id'] ? 'selected' : '' }}>
                                {{ $finca['nombre'] }} - {{ $finca['zona'] }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nombre -->
                    <div class="col-md-6 mb-3">
                        <label for="nombre" class="form-label">Nombre del Alimento <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="nombre" name="nombre" 
                               value="{{ old('nombre', $alimento['nombre']) }}" 
                               placeholder="Ej: Concentrado para Ganado Lechero" required>
                    </div>
                </div>

                <div class="row">
                    <!-- Tipo -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                        <select class="form-select" id="tipo" name="tipo" required>
                            <option value="">Seleccionar Tipo</option>
                            <option value="concentrado" {{ old('tipo', $alimento['tipo']) == 'concentrado' ? 'selected' : '' }}>Concentrado</option>
                            <option value="forraje" {{ old('tipo', $alimento['tipo']) == 'forraje' ? 'selected' : '' }}>Forraje</option>
                            <option value="suplemento" {{ old('tipo', $alimento['tipo']) == 'suplemento' ? 'selected' : '' }}>Suplemento</option>
                            <option value="mineral" {{ old('tipo', $alimento['tipo']) == 'mineral' ? 'selected' : '' }}>Mineral</option>
                            <option value="otro" {{ old('tipo', $alimento['tipo']) == 'otro' ? 'selected' : '' }}>Otro</option>
                        </select>
                    </div>

                    <!-- Unidad de Medida -->
                    <div class="col-md-6 mb-3">
                        <label for="unidad_medida" class="form-label">Unidad de Medida <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" 
                               id="unidad_medida" name="unidad_medida" 
                               value="{{ old('unidad_medida', $alimento['unidad_medida']) }}" 
                               placeholder="Ej: kg, lb, saco, unidad" required>
                        <small class="form-text text-muted">Unidad en que se mide el alimento</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Stock Actual -->
                    <div class="col-md-4 mb-3">
                        <label for="stock_actual" class="form-label">Stock Actual <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" 
                               id="stock_actual" name="stock_actual" 
                               value="{{ old('stock_actual', $alimento['stock_actual']) }}" 
                               min="0" step="0.01" required>
                        <small class="form-text text-muted">Cantidad disponible</small>
                    </div>

                    <!-- Stock Mínimo -->
                    <div class="col-md-4 mb-3">
                        <label for="stock_minimo" class="form-label">Stock Mínimo <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" 
                               id="stock_minimo" name="stock_minimo" 
                               value="{{ old('stock_minimo', $alimento['stock_minimo']) }}" 
                               min="0" step="0.01" required>
                        <small class="form-text text-muted">Alerta cuando baje de esta cantidad</small>
                    </div>

                    <!-- Precio Unitario -->
                    <div class="col-md-4 mb-3">
                        <label for="precio_unitario" class="form-label">Precio Unitario (Q)</label>
                        <input type="number" step="0.01" class="form-control" 
                               id="precio_unitario" name="precio_unitario" 
                               value="{{ old('precio_unitario', $alimento['precio_unitario']) }}" 
                               min="0" placeholder="0.00">
                        <small class="form-text text-muted">Precio por unidad de medida</small>
                    </div>
                </div>

                <div class="row">
                    <!-- Proveedor -->
                    <div class="col-md-6 mb-3">
                        <label for="proveedor" class="form-label">Proveedor</label>
                        <input type="text" class="form-control" 
                               id="proveedor" name="proveedor" 
                               value="{{ old('proveedor', $alimento['proveedor']) }}" 
                               placeholder="Ej: Alimentos Ganaderos S.A.">
                    </div>

                    <!-- Fecha Vencimiento -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                        <input type="date" class="form-control" 
                               id="fecha_vencimiento" name="fecha_vencimiento" 
                               value="{{ old('fecha_vencimiento', $alimento['fecha_vencimiento']) }}"
                               min="{{ date('Y-m-d') }}">
                        <small class="form-text text-muted">Solo para alimentos perecederos</small>
                    </div>
                </div>

                <!-- Descripción -->
                <div class="mb-3">
                    <label for="descripcion" class="form-label">Descripción</label>
                    <textarea class="form-control" 
                              id="descripcion" name="descripcion" 
                              rows="3" 
                              placeholder="Descripción del alimento, composición, instrucciones de uso, etc.">{{ old('descripcion', $alimento['descripcion']) }}</textarea>
                </div>

                <!-- Alertas de Stock -->
                <div class="alert alert-warning" id="stockAlert" style="display: none;">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Advertencia:</strong> El stock actual es menor o igual al stock mínimo.
                </div>

                <!-- Resumen de Información -->
                <div class="alert alert-info" id="infoResumen">
                    <i class="fas fa-info-circle me-2"></i>
                    <strong>Resumen:</strong> 
                    <span id="resumenText"></span>
                </div>

                <!-- Botones -->
                <div class="d-flex justify-content-between">
                    <a href="{{ route('alimentacion.alimentos.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times me-2"></i>Cancelar
                    </a>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Actualizar Alimento
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
        const infoResumen = document.getElementById('infoResumen');
        const resumenText = document.getElementById('resumenText');
        const nombre = document.getElementById('nombre');
        const tipo = document.getElementById('tipo');
        const unidadMedida = document.getElementById('unidad_medida');
        const precioUnitario = document.getElementById('precio_unitario');

        // Validar stock
        function validarStock() {
            const actual = parseFloat(stockActual.value) || 0;
            const minimo = parseFloat(stockMinimo.value) || 0;
            
            if (actual <= minimo) {
                stockAlert.style.display = 'block';
                stockActual.classList.add('is-invalid');
            } else {
                stockAlert.style.display = 'none';
                stockActual.classList.remove('is-invalid');
            }
        }

        // Actualizar resumen
        function actualizarResumen() {
            const nombreVal = nombre.value || '[Nombre no definido]';
            const tipoVal = tipo.options[tipo.selectedIndex]?.text || '[Tipo no seleccionado]';
            const unidadVal = unidadMedida.value || '[Unidad no definida]';
            const stockVal = stockActual.value || '0';
            const precioVal = precioUnitario.value ? `Q${parseFloat(precioUnitario.value).toFixed(2)}` : 'Precio no definido';
            
            if (nombre.value && tipo.value && unidadMedida.value) {
                resumenText.innerHTML = `
                    <strong>${nombreVal}</strong> - ${tipoVal}<br>
                    Stock actual: <strong>${stockVal} ${unidadVal}</strong> | Precio: ${precioVal}
                `;
                infoResumen.style.display = 'block';
            } else {
                infoResumen.style.display = 'none';
            }
        }

        // Event listeners para validación y resumen
        stockActual.addEventListener('input', function() {
            validarStock();
            actualizarResumen();
        });
        
        stockMinimo.addEventListener('input', function() {
            validarStock();
            actualizarResumen();
        });

        nombre.addEventListener('input', actualizarResumen);
        tipo.addEventListener('change', actualizarResumen);
        unidadMedida.addEventListener('input', actualizarResumen);
        precioUnitario.addEventListener('input', actualizarResumen);

        // Validación del formulario
        document.getElementById('alimentoForm').addEventListener('submit', function(e) {
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

            // Validar que stock mínimo sea positivo
            if (parseFloat(stockMinimo.value) <= 0) {
                valid = false;
                stockMinimo.classList.add('is-invalid');
                alert('El stock mínimo debe ser mayor a 0.');
            }

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
        actualizarResumen();

        // Sugerencias de unidades de medida basadas en el tipo
        tipo.addEventListener('change', function() {
            const sugerencias = {
                'concentrado': 'kg, lb, saco',
                'forraje': 'kg, lb, fardo, rollo',
                'suplemento': 'kg, lb, bolsa',
                'mineral': 'kg, lb, bloque, unidad',
                'otro': 'kg, lb, unidad'
            };
            
            const sugerencia = sugerencias[this.value];
            if (sugerencia && !unidadMedida.value) {
                unidadMedida.placeholder = `Ej: ${sugerencia}`;
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
    
    .form-text {
        font-size: 0.875em;
        color: #6c757d;
    }
</style>
@endsection