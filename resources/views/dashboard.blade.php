@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Estadísticas -->
<div class="row">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-animales text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Animales
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $data['estadisticas_generales']['total_animales'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-cow fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-fincas text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Total Fincas
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $data['estadisticas_generales']['total_fincas'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-tractor fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-leche text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Producción Semanal (L)
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $data['estadisticas_generales']['produccion_semanal_leche'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-wine-bottle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card stat-card bg-salud text-white shadow">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-uppercase mb-1">
                            Animales Enfermos
                        </div>
                        <div class="h5 mb-0 font-weight-bold">
                            {{ $data['estadisticas_generales']['animales_enfermos'] ?? 0 }}
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-first-aid fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alertas -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-exclamation-triangle me-2"></i> Alertas
                </h6>
            </div>
            <div class="card-body">
                @if(($data['estadisticas_generales']['medicamentos_stock_bajo'] ?? 0) > 0)
                <div class="alert alert-warning d-flex align-items-center">
                    <i class="fas fa-pills me-2"></i>
                    <span>
                        {{ $data['estadisticas_generales']['medicamentos_stock_bajo'] }} medicamentos con stock bajo
                    </span>
                </div>
                @endif

                @if(($data['estadisticas_generales']['vacunaciones_proximas'] ?? 0) > 0)
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-syringe me-2"></i>
                    <span>
                        {{ $data['estadisticas_generales']['vacunaciones_proximas'] }} vacunaciones próximas
                    </span>
                </div>
                @endif

                @if(($data['estadisticas_generales']['animales_enfermos'] ?? 0) > 0)
                <div class="alert alert-danger d-flex align-items-center">
                    <i class="fas fa-first-aid me-2"></i>
                    <span>
                        {{ $data['estadisticas_generales']['animales_enfermos'] }} animales requieren atención
                    </span>
                </div>
                @endif

                @if(($data['estadisticas_generales']['medicamentos_stock_bajo'] ?? 0) == 0 && 
                    ($data['estadisticas_generales']['animales_enfermos'] ?? 0) == 0)
                <div class="alert alert-success d-flex align-items-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>No hay alertas críticas en este momento</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h6 class="m-0 font-weight-bold">
                    <i class="fas fa-info-circle me-2"></i> Información del Sistema
                </h6>
            </div>
            <div class="card-body">
                <p><strong>Usuario:</strong> {{ session('user.name') }}</p>
                <p><strong>Rol:</strong> {{ session('user.role') }}</p>
                <p><strong>Email:</strong> {{ session('user.email') }}</p>
                <p><strong>Último acceso:</strong> {{ date('d/m/Y H:i') }}</p>
            </div>
        </div>
    </div>
</div>
@endsection