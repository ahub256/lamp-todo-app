<?php

require_once "../config/database.php";
require_once "auth.php";

if (isLoggedIn()) {
  header("location: /index.php");
  exit;
}

$username = $email = $password = $confirm_password = "";
$username_err = $email_err = $pasword_err = $confirm_password_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = connectDB();

  if(empty(trim($_POST["username"]))) {
    $username_err = "ユーザー名を入力してください";
  } else {
    $sql = "SELECT id FROM users WHERE username = ?";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("s",$param_username);
      $param_username = trim($_POST["username"]);

      if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
          $username_err = "このユーザー名は既に使用されてます。";
        } else {
          $username = trim($_POST["username"]);
        }
      } else {
        echo "エラーが発生しました。後でもう一度お試しください。";
      }
      $stmt->close();
    }
  }

  if (empty(trim($_POST["email"]))) {
    $email_err = "メールアドレスを入力してください。";
  } else {
    $sql = "SELECT id FROM users WHERE email = ?";
    
    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("s",$param_email);
      $param_email = trim($_POST["email"]);

      if ($stmt->execute()) {
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
          $email_err = "このメールアドレスは既に使用されています。";
        } else {
          $email = trim($_POST["email"]);
        } 
      } else {
        echo "エラーが発生しました。後でもう一度お試しください。";
      }
      $stmt->close();
    } 
  }

  if (empty(trim($_POST["password"]))) {
    $password_err = "パスワードを入力してください。";
  } elseif (strlen(trim($_POST["password"])) < 6) {
    $password_err = "パスワードは６文字以上である必要があります。";
  } else {
    $password = trim($_POST["password"]);
  }

  if (empty(trim($_POST["confirm_password"]))) {
    $confirm_password_err = "パスワードを確認してください。";
  } else {
    $confirm_password = trim($_POST["confirm_password"]);
    if (empty($password_err) && ($password != $confirm_password)) {
      $confirm_password_err = "パスワードが一致しません";
    }
  }

  if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";

    if($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("sss", $param_username, $param_email, $param_password);

      $param_username = $username;
      $param_email = $email;
      $param_password = password_hash($password, PASSWORD_DEFAULT);

      if ($stmt->execute()) {
        header("location: login.php");
      } else {
        echo "エラーが発生しました。後でもう一度お試しください。";
      }
      $stmt->close();
    }
  }

  $conn->close();
}

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
      <meta charset="UTF-8">
      <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
      <title>ユーザー登録 - TODOアプリ</title>
      <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
      <?php include_once "../includes/header.php"; ?>

      <div class="container">
          <h2>ユーザー登録</h2>
          <p>アカウントを作成するには、フォームにに入力してください</p>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
              <div class="form-group">
                  <label>ユーザー名</label>
                  <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                  <span class="invalid-feedback"><?php echo $username_err; ?></span>
              </div>
              <div class="form-group">
                  <label>メールアドレス</label>
                  <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>"value="<?php echo $email; ?>">
                  <span class="invalid-feedback"><?php echo $email_err; ?></span>
              </div>
              <div class="form-group">
                  <label>パスワード</label>
                  <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                  <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
              </div>
              <div class="form-group">
                  <label>パスワード確認</label>
                  <input type="pssword" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                  <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="登録">
              </div>
              <p>すでにアカウントをお持ちですか？ <a href="login.php">ログイン</a>してください</p>
          </form>
      </div>

      <?php include_once "../includes/footer.php"; ?>
  </body>
</html>

  





              
           
                                                      


