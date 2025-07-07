<?php

require_once "../config/database.php";
require_once "../auth/auth.php";

requireLogin();

if (empty(trim($_GET["id"]))) {
  header("location: /todo/read.php");
  exit;
}

//$conn = trim($_GET["id"]);
$id = trim($_GET["id"]);
$conn = connectDB();

$sql = "DELETE FROM todos WHERE id = ? AND user_id = ?";

if($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("ii", $param_id, $param_user_id);

  $param_id = $id;
  $param_user_id = getCurrentUserId();

  $stmt->execute();
  $stmt->close();
}

$conn->close();

header("location: /todo/read.php");
exit;
