<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Ganaderos GUA S.A.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #2E8B57;
            --primary-dark: #1F6B42;
            --secondary-color: #FF6B35;
            --text-dark: #2D3748;
            --text-light: #718096;
            --background-light: #F7FAFC;
        }
        
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .login-header {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            margin-bottom: 1rem;
        }
        
        .logo-icon {
            background: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
        }
        
        .login-body {
            padding: 2.5rem 2rem;
        }
        
        .form-control {
            border: 2px solid #E2E8F0;
            border-radius: 10px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(46, 139, 87, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--text-dark);
            margin-bottom: 8px;
        }
        
        .btn-login {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
            border: none;
            border-radius: 10px;
            padding: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(46, 139, 87, 0.3);
        }
        
        .alert {
            border-radius: 10px;
            border: none;
            padding: 15px 20px;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .login-header {
            padding: 1.5rem 2rem 1rem 2rem;
            text-align: center; 
        }

        .corporate-logo {
            display: flex;
            flex-direction: column; 
            align-items: center;
            justify-content: center;
            gap: 10px; 
            margin-bottom: 0.5rem;
        }

        .logo-symbol {
            background: rgba(255, 255, 255, 0.15);
            width: 140px; 
            height: 140px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 5px;
        }

        .logo-image {
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            filter: brightness(0) invert(1);
        }

        .brand-identity {
            text-align: center; 
        }
        
        .feature-list {
            list-style: none;
            padding: 0;
            margin-top: 2rem;
        }
        
        .feature-list li {
            padding: 8px 0;
            color: var(--text-light);
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .feature-list li i {
            color: var(--primary-color);
            font-size: 0.9rem;
        }
        
        .copyright {
            text-align: center;
            margin-top: 2rem;
            color: var(--text-light);
            font-size: 0.9rem;
        }
        
        .input-group {
            position: relative;
        }
        
        .input-group .form-control {
            padding-right: 45px;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--text-light);
            cursor: pointer;
            z-index: 10;
        }

        .shake-animation {
            animation: shake 0.5s ease-in-out;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        @media (max-width: 768px) {
            .login-container {
                margin: 20px;
            }
            
            .login-header,
            .login-body {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="login-container">
                    <!-- Header -->
                    <div class="login-header">
                        <div class="corporate-logo">
                            <div class="logo-symbol">
                                <img src="{{ asset('images/logos/logo-login.png') }}" alt="GANADEROS GUA S.A." class="logo-image">
                            </div>
                            <div class="brand-identity">
                                <h2 class="brand-name">Sistema Inteligente de Gestión Ganadera</h2>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Body -->
                    <div class="login-body">
                        <!-- Mensajes de error -->
                        @if($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show shake-animation" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                @foreach($errors->all() as $error)
                                    {{ $error }}
                                @endforeach
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show shake-animation" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        @if(session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="fas fa-check-circle me-2"></i>
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf
                            
                            <div class="mb-4">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Correo Electrónico
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" 
                                       value="{{ old('email') }}" required autofocus
                                       placeholder="usuario@ejemplo.com">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Contraseña
                                </label>
                                <div class="input-group">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" required
                                           placeholder="Ingresa tu contraseña">
                                    <button type="button" class="password-toggle" onclick="togglePassword()">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i> Iniciar Sesión
                                </button>
                            </div>
                        </form>

                        <!-- Features List -->
                        <ul class="feature-list">
                            <li>
                                <i class="fas fa-check-circle"></i>
                                Gestión de inventario animal
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                Control de producción lechera
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                Seguimiento de salud veterinaria
                            </li>
                            <li>
                                <i class="fas fa-check-circle"></i>
                                Reportes y estadísticas en tiempo real
                            </li>
                        </ul>

                        <!-- Copyright -->
                        <div class="copyright">
                            <p class="mb-0">&copy; 2025 Ganaderos GUA S.A. Todos los derechos reservados.</p>
                            <small>v1.0.0</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
        
        // Efecto de carga suave
        document.addEventListener('DOMContentLoaded', function() {
            document.body.style.opacity = '0';
            document.body.style.transition = 'opacity 0.5s ease';
            
            setTimeout(() => {
                document.body.style.opacity = '1';
            }, 100);

            // Agregar efecto shake a los campos con error
            const invalidFields = document.querySelectorAll('.is-invalid');
            invalidFields.forEach(field => {
                field.classList.add('shake-animation');
                setTimeout(() => {
                    field.classList.remove('shake-animation');
                }, 500);
            });
        });
    </script>
</body>
</html>