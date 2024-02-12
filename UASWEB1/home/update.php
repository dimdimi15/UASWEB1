<?php
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("X-Content-Type-Options: nosniff");
require('con.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $itemId = $_POST['idnews'];
    $item_judul = $_POST['judul'];
    $item_deskripsi = $_POST['deskripsi'];
    $prevImg = $_POST['prevImg'];
    $timestamp = time();

    if (isset($_FILES['url_image'])) {
        $uploadDir = 'upload/';
        $uploadFile = $uploadDir . $timestamp . basename($_FILES['url_image']['name']);
        $item_gambar = $_FILES['url_image']['name'];

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            echo json_encode(['status' => 'error', 'message' => 'Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.']);
            die("Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.");
        }

        if (move_uploaded_file($_FILES['url_image']['tmp_name'], $uploadFile)) {
            try {
                $prevImagePath = $uploadDir . $prevImg;
                if (file_exists($prevImagePath)) {
                    unlink($prevImagePath);
                }
                $sql = 'UPDATE news_catalog SET img = ?, title = ?, `desc` = ? WHERE id = ?';
                $connect = $database_connection->prepare($sql);
                $connect->execute([$timestamp . $item_gambar, $item_judul, $item_deskripsi, $itemId]);
                if ($connect->rowCount() > 0) {
                    echo json_encode(['status' => 'success', 'message' => 'Berhasil update data']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error update data']);
                }
                exit();
            } catch (PDOException $e) {
                echo json_encode(['status' => 'error', 'message' => 'Error database' . $e->getMessage()]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error ketika upload gambar']);
        }
    } else {
        try {
            $sql = 'UPDATE news_catalog SET title = ?, `desc` = ? WHERE id = ?';
            $connect = $database_connection->prepare($sql);
            $connect->execute([$item_judul, $item_deskripsi, $itemId]);
            if ($connect->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Berhasil update data']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error update data']);
            }
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => 'Error database' . $e->getMessage()]);
        }
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Error server bukan post' . $e->getMessage()]);
}

function updateItemWithImage($itemId, $item_gambar, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;
}

function updateItemWithoutImage($itemId, $item_judul, $item_deskripsi, $item_harga)
{
    global $db_connection;
}
