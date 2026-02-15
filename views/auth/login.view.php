<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>AquaKids - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
    </style>
</head>
<body class="d-flex align-items-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow-lg">
                    <div class="card-body p-5">
                        <h2 class="text-center mb-4 text-primary">AquaKids</h2>
                        <form action="login.php" method="POST">
                            <div class="mb-3">
                                <input type="text" name="usuario" class="form-control" placeholder="Usuario" required autofocus>
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                        </form>
                        <?php if (isset($_GET['error'])): ?>
                            <div class="alert alert-danger mt-3">Credenciales incorrectas</div>
                        <?php endif; ?>
                        <?php if (isset($_GET['logout'])): ?>
                            <div class="alert alert-success mt-3">Sesión cerrada correctamente</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>