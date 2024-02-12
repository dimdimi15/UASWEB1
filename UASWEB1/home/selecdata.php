<?php
header("Access-Control-Allow-Origin: *");
include 'con.php';

$id = isset($_POST["idnews"]) ? $_POST["idnews"] : null;

try {
    $statement = $database_connection->prepare("SELECT * FROM `news_catalog` WHERE `id` = ?");
    $statement->execute([$id]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    // Check if data exists
    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(["error" => "Data not found"]);
    }
} catch (PDOException $cek_koneksi) {
    die('Error: ' . $cek_koneksi->getMessage());
}
?>
