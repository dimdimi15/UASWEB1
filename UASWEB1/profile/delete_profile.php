<?php
include 'con.php';

// Ambil ID pengguna dari permintaan POST
$session_token = $_POST['session_token'];

// Buat query untuk menghapus pengguna dari database
$sql = "DELETE FROM user WHERE session_token = ?";
$stmt = $database_connection->prepare($sql);
$stmt->execute([$session_token]);

// Periksa apakah ada baris yang terpengaruh oleh eksekusi query
if ($stmt->rowCount() > 0) {
    echo json_encode(['status' => 'success', 'message' => 'Berhasil menghapus data']);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data atau data tidak ditemukan']);
}
?>
