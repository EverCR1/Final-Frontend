<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema Ganadero CUNOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: #2c3e50;
            min-height: 100vh;
            color: white;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-left: 4px solid transparent;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            border-left: 4px solid #28a745;
        }
        .sidebar .nav-link.active {
            background: #34495e;
            border-left: 4px solid #28a745;
        }
        .stat-card {
            border-radius: 10px;
            transition: transform 0.3s;
        }
        .stat-card:hover {
            transform: translateY(-5px);
        }
        .bg-animales { background: linear-gradient(45deg, #667eea, #764ba2); }
        .bg-fincas { background: linear-gradient(45deg, #f093fb, #f5576c); }
        .bg-leche { background: linear-gradient(45deg, #4facfe, #00f2fe); }
        .bg-salud { background: linear-gradient(45deg, #43e97b, #38f9d7); }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3 col-lg-2 sidebar p-0">
                <div class="p-3 text-center border-bottom">
                    <i class="fas fa-cow fa-2x text-success"></i>
                    <h5 class="mt-2">Sistema Ganadero</h5>
                    <small class="text-muted">CUNOR</small>
                </div>
                
                <nav class="nav flex-column mt-3">
                    <a class="nav-link active" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="{{ route('animals.index') }}">
                        <i class="fas fa-cow me-2"></i> Animales
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-tractor me-2"></i> Fincas
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-syringe me-2"></i> Vacunaciones
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-wine-bottle me-2"></i> Producción Leche
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-pills me-2"></i> Medicamentos
                    </a>
                    <a class="nav-link" href="#">
                        <i class="fas fa-chart-bar me-2"></i> Reportes
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 ms-sm-auto px-4">
                <!-- Navbar Top -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand">Dashboard</span>
                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-1"></i> 
                                    {{ session('user.name', 'Usuario') }}
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">
                                        <i class="fas fa-user me-2"></i> Perfil
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Content -->
                <div class="container-fluid mt-4">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

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
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>