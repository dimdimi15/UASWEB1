<?php
header("Access-Control-Allow-Origin: *");
include 'con.php';

// Tangkap data dari permintaan Fetch
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['user'];
    $password = $_POST['pwd'];

    // Periksa apakah username sudah terdaftar
    $checkStatement = $database_connection->prepare("SELECT id FROM user WHERE username = ?");
    $checkStatement->execute([$username]);
    $existingUser = $checkStatement->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo json_encode(['status' => 'error', 'message' => 'Username sudah terdaftar']);
    } else {
        try {
            // Hash password menggunakan password_hash
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Simpan data ke database
            $insertStatement = $database_connection->prepare("INSERT INTO user (username, password) VALUES (?, ?)");
            $insertStatement->execute([$username, $hashedPassword]);

            // Log registrasi berhasil
            error_log('Registrasi berhasil: ' . $username);

            echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil']);
        } catch (PDOException $e) {
            // Log kesalahan saat menyimpan ke database
            error_log('Error saat menyimpan ke database: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database']);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Metode permintaan tidak valid']);
}
?>
