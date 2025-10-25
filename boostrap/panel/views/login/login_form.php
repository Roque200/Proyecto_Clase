<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Red de Investigación</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            width: 100%;
            max-width: 450px;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .login-header h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .login-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .login-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            display: block;
        }

        .login-body {
            padding: 40px 30px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }

        .form-control {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 12px 15px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid:focus {
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.1);
        }

        .alert {
            border-radius: 8px;
            border: none;
            margin-bottom: 20px;
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            color: white;
            padding: 12px 20px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 8px;
            width: 100%;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
            color: white;
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 2px solid #e0e0e0;
            cursor: pointer;
        }

        .input-group .form-control {
            border-right: none;
        }

        .input-group .form-control:focus + .input-group-text {
            border-color: #667eea;
        }

        .form-check {
            margin-bottom: 15px;
        }

        .form-check-input {
            cursor: pointer;
            width: 18px;
            height: 18px;
            margin-top: 0.35rem;
        }

        .form-check-input:checked {
            background-color: #667eea;
            border-color: #667eea;
        }

        .form-check-label {
            cursor: pointer;
            margin-left: 5px;
        }

        .login-footer {
            text-align: center;
            padding: 20px;
            background-color: #f8f9fa;
            border-top: 1px solid #e0e0e0;
        }

        .login-footer small {
            color: #666;
        }

        .login-footer a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .spinner-border-sm {
            width: 1rem;
            height: 1rem;
            margin-right: 5px;
        }

        @media (max-width: 576px) {
            .login-container {
                max-width: 100%;
            }

            .login-header {
                padding: 30px 15px;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }

            .login-body {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <!-- Header -->
            <div class="login-header">
                <i class="fas fa-flask-vial login-icon"></i>
                <h1>Red de Investigación</h1>
                <p>Panel de Administración</p>
            </div>

            <!-- Body -->
            <div class="login-body">
                <?php if (!empty($mensaje_error)): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle"></i> 
                        <strong>Error:</strong> <?php echo htmlspecialchars($mensaje_error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (!empty($mensaje_exito)): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> 
                        <strong>Éxito:</strong> <?php echo htmlspecialchars($mensaje_exito); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="login.php">
                    <!-- Email -->
                    <div class="form-group">
                        <label for="correo" class="form-label">
                            <i class="fas fa-envelope"></i> Correo Electrónico
                        </label>
                        <input type="email" 
                               class="form-control" 
                               id="correo" 
                               name="correo" 
                               placeholder="ejemplo@correo.com"
                               required 
                               autofocus>
                    </div>

                    <!-- Contraseña -->
                    <div class="form-group">
                        <label for="password" class="form-label">
                            <i class="fas fa-lock"></i> Contraseña
                        </label>
                        <div class="input-group">
                            <input type="password" 
                                   class="form-control" 
                                   id="password" 
                                   name="password" 
                                   placeholder="••••••••"
                                   required>
                            <button class="input-group-text" type="button" id="togglePassword">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Recuérdame -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                        <label class="form-check-label" for="remember">
                            Recuérdame en este dispositivo
                        </label>
                    </div>

                    <!-- Botón Login -->
                    <button type="submit" name="login" class="btn-login">
                        <i class="fas fa-sign-in-alt"></i> Iniciar Sesión
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <div class="login-footer">
                <small>
                    © 2025 Red de Investigación TecNM
                </small>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle mostrar/ocultar contraseña
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Validación básica antes de enviar
        document.querySelector('form').addEventListener('submit', function(e) {
            const correo = document.getElementById('correo').value;
            const password = document.getElementById('password').value;
            
            if (!correo || !password) {
                e.preventDefault();
                alert('Por favor completa todos los campos');
                return false;
            }
        });
    </script>
</body>
</html>