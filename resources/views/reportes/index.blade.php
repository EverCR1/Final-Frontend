@extends('layouts.app')

@section('title', 'Sistema de Reportes')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-chart-bar text-primary"></i> Sistema de Reportes
        </h1>
    </div>

    <!-- Cards de Reportes -->
    <div class="row">
        <!-- Animales por Finca -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Animales por Finca</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Distribución de animales por finca
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-cow fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('reportes.animales-finca') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>Generar Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Producción Mensual -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Producción Mensual</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Reporte de leche por mes
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-milk-bottle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('reportes.produccion-mensual') }}" class="btn btn-info btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>Generar Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Salud Animal -->
        <div class="col-xl-4 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Salud Animal</div>
                            <div class="h6 mb-0 font-weight-bold text-gray-800">
                                Estados y vacunaciones
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-heartbeat fa-2x text-gray-300"></i>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('reportes.salud-animal') }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-external-link-alt me-2"></i>Generar Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Información del Sistema -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Acerca de los Reportes
                    </h6>
                </div>
                <div class="card-body">
                    <p>El sistema de reportes le permite analizar y visualizar la información de su ganadería de manera eficiente. Como administrador, tiene acceso a todos los reportes del sistema.</p>
                    
                    <h6 class="font-weight-bold text-primary mt-4">Reportes Disponibles:</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-cow text-success me-3 mt-1"></i>
                                <div>
                                    <strong>Animales por Finca</strong>
                                    <p class="text-muted mb-0">Distribución y concentración de animales en cada finca</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-milk-bottle text-info me-3 mt-1"></i>
                                <div>
                                    <strong>Producción Mensual</strong>
                                    <p class="text-muted mb-0">Análisis detallado de la producción de leche por período</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start mb-3">
                                <i class="fas fa-heartbeat text-warning me-3 mt-1"></i>
                                <div>
                                    <strong>Salud Animal</strong>
                                    <p class="text-muted mb-0">Estados de salud y seguimiento de vacunaciones</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="fas fa-lightbulb me-2"></i>
                        <strong>Tip:</strong> Los reportes se actualizan en tiempo real con la información más reciente del sistema.
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('animals.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-cow me-2"></i>Gestión de Animales
                        </a>
                        <a href="{{ route('produccion-leche.index') }}" class="btn btn-outline-info btn-sm">
                            <i class="fas fa-milk-bottle me-2"></i>Producción de Leche
                        </a>
                        <a href="{{ route('vacunaciones.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-syringe me-2"></i>Registro de Vacunaciones
                        </a>
                        <a href="{{ route('medicamentos.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-pills me-2"></i>Inventario de Medicamentos
                        </a>
                    </div>
                </div>
            </div>

            <!-- Estadísticas Rápidas -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-danger">
                        <i class="fas fa-user-shield me-2"></i>Información de Acceso
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Usuario:</span>
                        <span class="badge bg-secondary">{{ session('user.name') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Rol:</span>
                        <span class="badge bg-info text-capitalize">{{ session('user.role') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Permisos:</span>
                        <span class="badge bg-success">Administrador</span>
                    </div>
                    <hr>
                    <small class="text-muted">Última actualización: {{ now()->format('d/m/Y H:i') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection