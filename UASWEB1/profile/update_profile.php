<?php
// Mengimpor koneksi database
require_once "con.php";

// Mendapatkan data dari permintaan POST
$username = $_POST['username'];
$name = $_POST['name'];
$newPassword = $_POST['newPassword'];
$oldPassword = $_POST['oldPassword'];
$sessionToken = $_POST['session_token'];

// Periksa apakah session_token sesuai
$stmt = $database_connection->prepare("SELECT * FROM user WHERE session_token = ?");
$stmt->execute([$sessionToken]);
$user = $stmt->fetch();
$response = null;

if ($user) {
    // Token sesuai, lanjutkan dengan verifikasi password lama
    if ((sha1($oldPassword) === $user['password'])) {
        // Password lama cocok, lakukan pembaruan data
        $stmt = $database_connection->prepare("UPDATE user SET username = ?, name = ?, password = ? WHERE session_token = ?");
        $stmt->execute([$username, $name, sha1($newPassword), $sessionToken]);

        if ($stmt->rowCount() > 0) {
            // Pembaruan berhasil
            $response = array(
                "status" => "success",
                "message" => "Profil berhasil diperbarui."
            );
           
        } else {
            // Pembaruan gagal
            $response = array(
                "status" => "error",
                "message" => "Gagal memperbarui profil. Silakan coba lagi."
            );
         
        }
    } else {
        // Password lama tidak cocok
        $response = array(
            "status" => "error",
            "message" => "Password lama tidak sesuai."
        );
       
    }
} else {
    // Token tidak sesuai
    $response = array(
        "status" => "error",
        "message" => "Token sesi tidak valid. Silakan login kembali."
    );
    
}
echo json_encode($response);
?>
