<?php

include 'con.php';

if (isset($_POST["id"])) {
    $id = $_POST["id"];
    try {
        $statement = $database_connection->prepare("DELETE FROM news_catalog WHERE news_catalog.id=?");
        $statement->execute([$id]);
        echo json_encode(['status' => 'success', 'message' => 'Berhasil menghapus data']);
    } catch (PDOException $cek_koneksi) {
        echo json_encode(['status' => 'error', 'message' => 'Gagal menghapus data']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error mendapatkan id']);
}
