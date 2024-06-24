<?php
session_start();
require 'config.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id']) || $_SESSION['role'] !== 'Admin') {
    header("location: login.php");
    exit;
}

// Obtener todos los usuarios de la base de datos
$sql = "SELECT id, first_name, last_name, email, phone, rol, created_at FROM users";
$result = mysqli_query($conexion, $sql);

// Incluir estilos CSS para la tabla
echo '<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }
    table, th, td {
        border: 1px solid black;
    }
    th, td {
        padding: 10px;
        text-align: left;
    }
    th {
        background-color: #f2f2f2;
    }
    tr:hover {
        background-color: #f5f5f5;
    }
</style>';

echo '<h1>Lista de Usuarios</h1>';
echo '<table>';
echo '<tr><th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th><th>Fecha de Registro</th><th>Rol</th></tr>';

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['id']) . '</td>';
        echo '<td>' . htmlspecialchars($row['first_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['last_name']) . '</td>';
        echo '<td>' . htmlspecialchars($row['email']) . '</td>';
        echo '<td>' . htmlspecialchars($row['phone']) . '</td>';
        echo '<td>' . htmlspecialchars($row['created_at']) . '</td>';
        //pones en la tabla tambien los roles de cada usuario
        echo '<td>' . htmlspecialchars($row['rol']) . '</td>';
        echo '<td><a href="usuarios-edit.php?id=' . $row['id'] . '">Editar</a> | <a href="eliminacion-usuarios.php?id=' . $row['id'] . '">Eliminar</a></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="7">No se encontraron usuarios.</td></tr>';
}
echo '</table>';

//cerrar sesion
echo '<a href="cerrar-sesion.php">Cerrar Sesion</a>';

mysqli_close($conexion);
?>
