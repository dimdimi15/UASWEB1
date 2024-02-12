<?php

header("Access-Control-Allow-Origin: *");
include 'con.php';

$session_token = isset($_POST['session_token']) ? $_POST['session_token'] : null;
if (isset($session_token)) {
    $statement = $database_connection->prepare("SELECT name FROM user WHERE session_token = ?");
    $statement->execute([$session_token]);
    $user = $statement->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        echo json_encode(['status' => 'success', 'hasil' => $user]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'invalid session']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'invalid session']);
}

?>
