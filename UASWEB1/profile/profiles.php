<?php
header("Access-Control-Allow-Origin: *");

session_start();
include 'con.php';

$token = $_POST['session_token'];

$query = "SELECT * FROM user WHERE session_token = ?";
$statement = $database_connection->prepare($query);
$statement->execute([$token]);
$user = $statement->fetch(PDO::FETCH_ASSOC);


if ($user) {
    echo json_encode(['status' => 'success', 'user_profile' => $user]);
} else {
    echo json_encode(['status' => 'error', 'message' => 'Gagal mengambil data profil']);
}
?>
