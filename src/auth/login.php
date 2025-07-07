<?php

require_once "../config/database.php";
require_once "auth.php";

if (isLoggedIn()) {
  header("location: /index.php");
  exit;
}


$username = $password ="";
$username_err = $password_err = $login_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["username"]))) {
    $username_err = "ユーザー名を入力してください";
  } else {
    $username = trim($_POST["username"]);
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "パスワードを入力してください";
  } else {
    $password = trim($_POST["password"]);
  }

  if (empty($username_err) && empty($password_err)) {
    $conn = connectDB();
    $sql = "SELECT id, username,password FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("s", $param_username);
      $param_username = $username;

      if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
          $stmt->bind_result($id, $username, $hashed_password);
          if ($stmt->fetch()) {
            if (password_verify($password, $hashed_password)) {


              $_SESSION["loggedin"] = true;
              $_SESSION["id"] = $id;
              $_SESSION["username"] = $username;


              header("location: /index.php");

            } else {
              $login_err = "ユーザー名またはパスワードが正しくありません";
            }
          }
        } else {
          $login_err = "ユーザー名またはパスワードが正しくありません";
        }
      } else {
        echo "エラーが発生しました。後でもう一度お試しください";
      }

      $stmt->close();
    }

    $conn->close();
  }
}
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>ログイン-TODOアプリ</title>
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
<?php include_once "../includes/header.php"; ?>

<div class="container">
<h2>ログイン</h2>
<p>アカウントにログインするには、認証情報を入力してください</p>

<?php
if (!empty($login_err)) {
  echo '<div class="alert alert-danger">' . $login_err . '</div>';

}
?>

<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
<div class="form-group">
    <label>ユーザー名</label>
    <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
    <span class="invalid-feedback"><?php echo $username_err; ?></span>
</div>
<div class="form-group">
    <label>パスワード</label>
    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>">
    <span class="invalid-feedback"><?php echo $password_err; ?></span>
</div>
<div class="form-group">
    <input type="submit" class="btn-primary" value="ログイン">
</div>
<p>アカウントをお持ちでないですか？ <a href="register.php">今すぐ登録</a>してください</p>
</form>
    </div>

<?php include_once "../includes/footer.php"; ?>
  </body>
</html>


