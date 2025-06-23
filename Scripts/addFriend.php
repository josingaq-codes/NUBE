<?php
include 'Config.php'; // Contiene la conexión $conn a MySQL

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Validar si se ha iniciado sesión y se recibió un correo
if (!isset($_SESSION['user_id']) || !isset($_POST['friend_email'])) {
    echo "<script>alert('Error: Faltan datos necesarios.'); window.location.href = '../html/MisDatos.php';</script>";
    exit;
}

$usuario_id = $_SESSION['user_id'];
$correo_amigo = trim($_POST['friend_email']);

// Buscar el ID del amigo por su correo
$sql = "SELECT id FROM usuarios WHERE correo = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $correo_amigo);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $amigo_id = $row['id'];

    // Verificar que no se agregue a sí mismo
    if ($usuario_id == $amigo_id) {
        echo "<script>alert('No puedes agregarte a ti mismo como amigo.'); window.location.href = '../html/MisDatos.php';</script>";
        exit;
    }

    // Verificar si ya son amigos
    $check_sql = "SELECT * FROM amigos WHERE usuario_id = ? AND amigo_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $usuario_id, $amigo_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('Este usuario ya está en tu lista de amigos.'); window.location.href = '../html/MisDatos.php';</script>";
        exit;
    }

    // Insertar relación en la tabla de amigos
    $insert_sql = "INSERT INTO amigos (usuario_id, amigo_id) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("ii", $usuario_id, $amigo_id);

    if ($insert_stmt->execute()) {
        echo "<script>alert('Amigo agregado exitosamente.'); window.location.href = '../html/MisDatos.php';</script>";
        exit;
    } else {
        echo "<script>alert('Error al agregar el amigo.'); window.location.href = '../html/MisDatos.php';</script>";
        exit;
    }

} else {
    echo "<script>alert('El correo del amigo no existe.'); window.location.href = '../html/MisDatos.php';</script>";
    exit;
}

$conn->close(); 
?>
