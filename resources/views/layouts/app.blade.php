<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Sistema Ganadero CUNOR</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: #2c3e50;
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
        }
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
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
    @yield('styles')
</head>
<body>
    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar Fijo -->
            <div class="sidebar">
                <div class="p-3 text-center border-bottom">
                    <i class="fas fa-cow fa-2x text-success"></i>
                    <h5 class="mt-2">Sistema Ganadero</h5>
                    <small class="text-muted">CUNOR</small>
                </div>
                
                <nav class="nav flex-column mt-3">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                       href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                    </a>
                    <a class="nav-link {{ request()->routeIs('animals.*') ? 'active' : '' }}" 
                       href="{{ route('animals.index') }}">
                        <i class="fas fa-cow me-2"></i> Animales
                    </a>
                    <a class="nav-link {{ request()->routeIs('fincas.*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-tractor me-2"></i> Fincas
                    </a>
                    <a class="nav-link {{ request()->routeIs('vacunaciones.*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-syringe me-2"></i> Vacunaciones
                    </a>
                    <a class="nav-link {{ request()->routeIs('produccion.*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-wine-bottle me-2"></i> Producción Leche
                    </a>
                    <a class="nav-link {{ request()->routeIs('medicamentos.*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-pills me-2"></i> Medicamentos
                    </a>
                    <a class="nav-link {{ request()->routeIs('reportes.*') ? 'active' : '' }}" href="#">
                        <i class="fas fa-chart-bar me-2"></i> Reportes
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="main-content">
                <!-- Navbar Top -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand">@yield('title', 'Sistema Ganadero')</span>
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

                <!-- Content Dinámico -->
                <div class="container-fluid mt-4">
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>