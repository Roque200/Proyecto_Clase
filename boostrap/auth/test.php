<?php
require_once("../models/usuario.php");

$email = 'maria.gonzalez@itcelaya.edu.mx';
$password = 'docente123';

echo "<h3>🔐 Probando Login</h3>";
echo "Email: $email<br>";
echo "Password: $password<br><br>";

$usuario = new Usuario();
$resultado = $usuario->login($email, $password);

if ($resultado) {
    echo "✅ <strong>Login EXITOSO</strong><br><br>";
    echo "<pre>";
    print_r($resultado);
    echo "</pre>";
} else {
    echo "❌ <strong>Login FALLÓ</strong><br>";
    echo "Revisa:<br>";
    echo "- Que el usuario exista en la base de datos<br>";
    echo "- Que la contraseña sea correcta<br>";
    echo "- Que el usuario esté activo (activo = 1)<br>";
}
?>