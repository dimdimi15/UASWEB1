<?php
header("Access-Control-Allow-Origin: *");
header("Cache-Control: no-cache, no-store, max-age=0, must-revalidate");
header("X-Content-Type-Options: nosniff");
include 'con.php';

$judul = $_POST['judul'];
$deskripsi = $_POST['deskripsi'];

// Check if 'url_image' key exists in the $_FILES array
if(isset($_FILES['url_image']) && $_FILES['url_image']['error'] == UPLOAD_ERR_OK) {
    $gambar = $_FILES['url_image']['name'];
    $timestamp = time();

    $uploadDir = 'upload/';
    $uploadedFileName = $uploadDir . '/' . $timestamp . basename($_FILES['url_image']['name']);
    $imageFileType = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));

    try {
        if ($imageFileType != "jpg" && $imageFileType != "jpeg" && $imageFileType != "png" && $imageFileType != "gif") {
            echo json_encode(['status' => 'error', 'message' => 'Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.']);
            die("Hanya file gambar JPG, JPEG, PNG, dan GIF yang diizinkan.");
        }
        
        if (move_uploaded_file($_FILES['url_image']['tmp_name'], $uploadedFileName)) {
            $sql = "INSERT INTO news_catalog (id, title, `desc`, img) VALUES (?, ?, ?, ?)";
            $statement = $database_connection->prepare($sql);
            $statement->execute([null, $judul, $deskripsi, $timestamp . $gambar]);

            if ($statement->rowCount() > 0) {
                echo json_encode(['status' => 'success', 'message' => 'Item berhasil diupload dan data berhasil ditambahkan']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Error menambah data']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Gagal mengupload gambar']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Database error' . $e->getMessage()]);
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'General error' . $e->getMessage()]);
    }
} else {
    // If 'url_image' key is not set or no file is uploaded, insert data without image using a default value
    $defaultImage = 'default_image.jpg'; // Replace with the actual default image filename
    $sql = "INSERT INTO news_catalog (id, title, `desc`, img) VALUES (?, ?, ?, ?)";
    $statement = $database_connection->prepare($sql);
    $statement->execute([null, $judul, $deskripsi, $defaultImage]);

    if ($statement->rowCount() > 0) {
        echo json_encode(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Error menambah data']);
    }
}
