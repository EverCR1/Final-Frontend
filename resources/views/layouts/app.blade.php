<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Ganaderos GUA S.A.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background: #2c3e50;
            min-height: 100vh;
            color: white;
            position: fixed;
            width: 250px;
            display: flex;
            flex-direction: column;
            transition: all 0.3s ease;
            z-index: 1000;
            left: 0;
        }
        .main-content {
            margin-left: 250px;
            width: calc(100% - 250px);
            transition: all 0.3s ease;
        }
        .sidebar .nav-link {
            color: #ecf0f1;
            padding: 15px 20px;
            border-left: 4px solid transparent;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .sidebar .nav-link:hover {
            background: #34495e;
            border-left: 4px solid #28a745;
        }
        .sidebar .nav-link.active {
            background: #34495e;
            border-left: 4px solid #28a745;
        }
        .user-role-badge {
            font-size: 0.7rem;
            margin-left: 5px;
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
        
        /* Estilos para el copyright */
        .sidebar-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        .sidebar-nav {
            flex: 1;
            overflow-y: auto;
        }
        .copyright {
            padding: 15px;
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            margin-top: auto;
            flex-shrink: 0;
        }
        .copyright p {
            margin-bottom: 3px;
            font-size: 0.75rem;
            color: #bdc3c7;
            line-height: 1.2;
        }
        .copyright .version {
            font-size: 0.65rem;
            color: #95a5a6;
        }

        /* Sidebar colapsado */
        .sidebar-collapsed {
            width: 70px;
        }
        .sidebar-collapsed .nav-link {
            padding: 15px;
            text-align: center;
        }
        .sidebar-collapsed .nav-link span,
        .sidebar-collapsed .nav-link .dropdown-toggle::after {
            display: none;
        }
        .sidebar-collapsed .logo-text,
        .sidebar-collapsed .copyright {
            display: none;
        }
        .sidebar-collapsed .logo-image {
            max-height: 40px !important;
        }
        .sidebar-collapsed + .main-content {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* Botón toggle móvil */
        .sidebar-toggle {
            position: fixed;
            top: 15px;
            left: 15px;
            z-index: 1001;
            background: #2c3e50;
            border: none;
            color: white;
            border-radius: 5px;
            padding: 8px 12px;
            cursor: pointer;
            display: none;
        }

        /* Flecha para desktop */
        .sidebar-arrow {
            position: absolute;
            top: 20px;
            right: -12px;
            background: #2c3e50;
            border: none;
            color: white;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 1002;
            transition: all 0.3s ease;
            font-size: 0.8rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }
        .sidebar-arrow:hover {
            background: #34495e;
            transform: scale(1.1);
        }
        .sidebar-collapsed .sidebar-arrow {
            transform: rotate(180deg);
        }
        .sidebar-collapsed .sidebar-arrow:hover {
            transform: rotate(180deg) scale(1.1);
        }

        /* Dropdown en sidebar colapsado */
        .sidebar-collapsed .dropdown-menu {
            position: fixed !important;
            left: 70px !important;
            top: auto !important;
            margin-top: -40px !important;
            width: 200px;
        }
        .sidebar-collapsed .dropdown-menu {
            position: fixed !important;
            left: 70px !important;
            top: auto !important;
            transform: none !important;
            margin-top: 0 !important;
            width: 200px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
        }

        .sidebar-collapsed .dropdown-item {
            color: #212529;
            padding: 0.5rem 1rem;
        }

        .sidebar-collapsed .dropdown-item:hover {
            background-color: #f8f9fa;
            color: #16181b;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                width: 250px;
                transform: translateX(-100%);
            }
            .sidebar.sidebar-mobile-visible {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .sidebar-toggle {
                display: block;
            }
            .sidebar-arrow {
                display: none;
            }
        }

        /* Scroll personalizado */
        .sidebar-nav::-webkit-scrollbar {
            width: 5px;
        }
        .sidebar-nav::-webkit-scrollbar-track {
            background: rgba(255,255,255,0.1);
        }
        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255,255,255,0.3);
            border-radius: 10px;
        }
    </style>
    @yield('styles')
</head>
<body>
    <!-- Botón Toggle para Mobile -->
    <button class="sidebar-toggle" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="container-fluid p-0">
        <div class="row g-0">
            <!-- Sidebar Fijo -->
            <div class="sidebar" id="sidebar">
                <!-- Flecha para desktop -->
                <button class="sidebar-arrow" id="sidebarArrow">
                    <i class="fas fa-chevron-left"></i>
                </button>

                <div class="sidebar-content">
                    <div class="p-3 text-center border-bottom">
                        <a href="{{ url('/') }}" class="text-decoration-none">
                            <div class="logo-symbol">
                                <img src="{{ asset('images/logos/logo-login.png') }}" alt="GANADEROS GUA S.A." class="logo-image" style="max-height: 100px;">
                            </div>
                            <div class="logo-text mt-2">
                                <small class="text-white-50">GANADEROS GUA S.A.</small>
                            </div>
                        </a>
                    </div>
                    
                    <nav class="nav flex-column mt-3 sidebar-nav">
                        <!-- Dashboard - Todos los roles -->
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                           href="{{ route('dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i> 
                            <span>Dashboard</span>
                        </a>
                        
                        <!-- Animales - Todos los roles -->
                        <a class="nav-link {{ request()->routeIs('animals.*') ? 'active' : '' }}" 
                           href="{{ route('animals.index') }}">
                            <i class="fas fa-horse me-2"></i> 
                            <span>Animales</span>
                        </a>
                        
                        <!-- Fincas - Solo Admin -->
                        @if(session('user.role') === 'admin')
                        <a class="nav-link {{ request()->routeIs('fincas.*') ? 'active' : '' }}" 
                           href="{{ route('fincas.index') }}">
                            <i class="fas fa-tractor me-2"></i> 
                            <span>Fincas</span>
                        </a>
                        @endif

                        <!-- Usuarios - Solo Admin -->
                        @if(session('user.role') === 'admin')
                        <a class="nav-link {{ request()->routeIs('usuarios.*') ? 'active' : '' }}" 
                           href="{{ route('users.index') }}">
                            <i class="fas fa-users me-2"></i> 
                            <span>Usuarios</span>
                        </a>
                        @endif
                        
                        <!-- Vacunaciones - Admin y Veterinario -->
                        @if(in_array(session('user.role'), ['admin', 'veterinario']))
                        <a class="nav-link {{ request()->routeIs('vacunaciones.#') ? 'active' : '' }}" 
                           href="{{ route('vacunaciones.index') }}">
                            <i class="fas fa-syringe me-2"></i> 
                            <span>Vacunaciones</span>
                        </a>
                        @endif

                        <!-- Alimentación - Admin y Veterinario -->
                        @if(in_array(session('user.role'), ['admin', 'veterinario']))
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('alimentacion.*') ? 'active' : '' }}" 
                            href="#" id="alimentacionDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-apple-alt me-2"></i> 
                                <span>Alimentación</span>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="alimentacionDropdown">
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('alimentacion.alimentos.*') ? 'active' : '' }}" 
                                    href="{{ route('alimentacion.alimentos.index') }}">
                                        <i class="fas fa-warehouse me-2"></i> Alimentos
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('alimentacion.dietas.*') ? 'active' : '' }}" 
                                    href="{{ route('alimentacion.dietas.index') }}">
                                        <i class="fas fa-utensils me-2"></i> Dietas
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('alimentacion.registros.*') ? 'active' : '' }}" 
                                    href="{{ route('alimentacion.registros.index') }}">
                                        <i class="fas fa-clipboard-list me-2"></i> Registros
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item {{ request()->routeIs('alimentacion.index') ? 'active' : '' }}" 
                                    href="{{ route('alimentacion.index') }}">
                                        <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                    </a>
                                </li>
                            </ul>
                        </li>
                        @endif
                        
                        <!-- Producción Leche - Todos los roles -->
                        <a class="nav-link {{ request()->routeIs('produccion-leche.*') ? 'active' : '' }}" 
                           href="{{ route('produccion-leche.index') }}">
                            <i class="fas fa-wine-bottle me-2"></i> 
                            <span>Producción Leche</span>
                        </a>
                        
                        <!-- Medicamentos - Admin y Veterinario -->
                        @if(in_array(session('user.role'), ['admin', 'veterinario']))
                        <a class="nav-link {{ request()->routeIs('medicamentos.*') ? 'active' : '' }}" 
                           href="{{ route('medicamentos.index') }}">
                            <i class="fas fa-pills me-2"></i> 
                            <span>Medicamentos</span>
                        </a>
                        @endif
                        
                        <!-- Reportes - Solo Admin -->
                        @if(session('user.role') === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('reportes.index') }}">
                                <i class="fas fa-chart-bar fa-fw me-2"></i>
                                <span>Reportes</span>
                            </a>
                        </li>
                        @endif
                    </nav>

                    <!-- Copyright -->
                    <div class="copyright">
                        <p class="mb-1">&copy; 2025 Ganaderos GUA S.A. Todos los derechos reservados.</p>
                        <small class="version">v1.0.0</small>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="main-content" id="mainContent">
                <!-- Navbar Top -->
                <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                    <div class="container-fluid">
                        <span class="navbar-brand"></span>
                        
                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" role="button" 
                                   data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user me-1"></i> 
                                    {{ session('user.name', 'Usuario') }}
                                    <span class="user-role-badge badge 
                                        @if(session('user.role') === 'admin') bg-success
                                        @elseif(session('user.role') === 'veterinario') bg-info
                                        @else bg-secondary @endif">
                                        {{ session('user.role') }}
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <span class="dropdown-item-text">
                                            <small class="text-muted">
                                                <i class="fas fa-user me-2"></i>
                                                {{ session('user.name') }}
                                            </small>
                                        </span>
                                    </li>
                                    <li>
                                        <span class="dropdown-item-text">
                                            <small class="text-muted">
                                                <i class="fas fa-envelope me-2"></i>
                                                {{ session('user.email') }}
                                            </small>
                                        </span>
                                    </li>
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
                    <!-- ... contenido existente ... -->
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('mainContent');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarArrow = document.getElementById('sidebarArrow');
            const dropdowns = document.querySelectorAll('.dropdown-toggle');

            // Toggle sidebar con flecha (desktop)
            sidebarArrow.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                
                // Guardar estado en localStorage
                const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                localStorage.setItem('sidebarCollapsed', isCollapsed);
            });

            // Toggle sidebar móvil
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-mobile-visible');
            });

            // Cargar estado del sidebar desde localStorage
            const savedState = localStorage.getItem('sidebarCollapsed');
            if (savedState === 'true') {
                sidebar.classList.add('sidebar-collapsed');
            }

            // Cerrar sidebar al hacer clic fuera en móvil
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768 && 
                    !sidebar.contains(event.target) && 
                    !sidebarToggle.contains(event.target)) {
                    sidebar.classList.remove('sidebar-mobile-visible');
                }
            });

            

            // Manejar dropdowns en sidebar colapsado
            dropdowns.forEach(dropdown => {
                dropdown.addEventListener('click', function(e) {
                    if (sidebar.classList.contains('sidebar-collapsed') || window.innerWidth <= 768) {
                        e.preventDefault();
                        e.stopPropagation();
                        const menu = this.nextElementSibling;
                        
                        // Cerrar otros dropdowns
                        document.querySelectorAll('.dropdown-menu.show').forEach(otherMenu => {
                            if (otherMenu !== menu) {
                                otherMenu.classList.remove('show');
                            }
                        });
                        
                        menu.classList.toggle('show');
                    }
                });
            });

            // Cerrar dropdowns al hacer clic fuera
            document.addEventListener('click', function(e) {
                if (!e.target.matches('.dropdown-toggle')) {
                    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                        menu.classList.remove('show');
                    });
                }
            });

            // Ajustar en resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('sidebar-mobile-visible');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>