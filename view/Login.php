<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login MVC</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url("imagenes/Fondo.png") no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            background-color: rgba(255, 255, 255, 1);
        }
        .card img {
            width: 270px;
            margin: 0 auto 15px auto;
            display: block;
        }
        .btn-lila {
            background-color: #8b5cf6;
            border: none;
            transition: 0.3s;
        }
        .btn-lila:hover {
            background-color: #7c3aed;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <div class="card p-4" style="width: 400px;">

        <img src="imagenes/Logo.png" alt="Logo">

        <h3 class="text-center mb-4" style="color: #8b5cf6;">INICIAR SESIÓN</h3>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger text-center">Usuario o contraseña incorrectos</div>
        <?php endif; ?>

        <form method="POST" action="/TPWEB/ProyectoInventario/Index.php">
            <div class="mb-3">
                <label class="form-label">Usuario:</label>
                <input type="text" name="usuario" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Contraseña:</label>
                <div class="input-group">
                    <input type="password" id="clave" name="clave" class="form-control" required autocomplete="off">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                        <i class="bi bi-eye" id="iconoClave"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn btn-lila w-100 text-white fw-semibold">Ingresar</button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const input = document.getElementById("clave");
            const icono = document.getElementById("iconoClave");
            if (input.type === "password") {
                input.type = "text";
                icono.classList.remove("bi-eye");
                icono.classList.add("bi-eye-slash");
            } else {
                input.type = "password";
                icono.classList.remove("bi-eye-slash");
                icono.classList.add("bi-eye");
            }
        }
    </script>
</body>
</html>
