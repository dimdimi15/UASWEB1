<?php
header("Access-Control-Allow-Origin: *");
include 'con.php';

$username = $_POST["user"];
$password = $_POST["pwd"];
$name = $_POST["name"];



if (isset($username) && isset($password) && isset($name)) {
    $checkStatement = $database_connection->prepare("SELECT id FROM user WHERE username = ?");
    $checkStatement->execute([$username]);
    $existingUser = $checkStatement->fetch(PDO::FETCH_ASSOC);

    if ($existingUser) {
        echo json_encode(['status' => 'error', 'message' => 'Username sudah terdaftar']);
    } else {
        try {
            // Hash password menggunakan password_hash
            $hashedPassword = sha1($password);

            // Simpan data ke database
            $insertStatement = $database_connection->prepare("INSERT INTO user (`username`, `password`, `name`) VALUES (?, ?)");
            $insertStatement->execute([$username, $hashedPassword, $name]);

            // Log registrasi berhasil
            error_log('Registrasi berhasil: ' . $username);

            echo json_encode(['status' => 'success', 'message' => 'Registrasi berhasil']);
        } catch (PDOException $e) {
            // Log kesalahan saat menyimpan ke database
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['status' => 'error', 'message' => 'Gagal menyimpan data ke database' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Data pendaftaran tidak valid']);
}
