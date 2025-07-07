<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
}

function requireLogin() {
  if(!isLoggedIn()) {
    header("location: /auth/login.php");
    exit;
  }
}

function getCurrentUserId() {
  if (isLoggedIn()) {
    return $_SESSION["id"];
  }
  return null;
}

function getCurrentUsername() {
  if (isLoggedIn()) {
    return $_SESSION["username"];
  }
  return null;
}
