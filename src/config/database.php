<?php

define('DB_SERVER', 'db');
define('DB_USERNAME', 'todo_user');
define('DB_PASSWORD', 'todo_password');
define('DB_NAME', 'todo_db');

function connectDB() {
    $conn = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

  if ($conn->connect_error) {
    die("Connection failed:" . $conn->connect_error);
  }

  $conn->set_charset("utf8mb4");

  return $conn;
}
