<?php

require_once "../config/database.php";
require_once "../auth/auth.php";

requireLogin();

$title = $description = "";
$title_err = $description_err = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $conn = connectDB();

  if (empty(trim($_POST["title"]))) {
    $title_err = "タイトルを入力してください";
  } else {
    $title = trim($_POST["title"]);
  }

  $description = trim($_POST["description"]);

  if (empty($title_err)) {
    $sql = "INSERT INTO todos (user_id, title, description) VALUES (?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("iss", $param_user_id, $param_title,$param_description);

      $param_user_id = getCurrentUserId();
      $param_title = $title;
      $param_description = $description;

      if ($stmt->execute()) {
        header("location: /todo/read.php");
      } else {
        echo "エラーが発生しました。後でもう一度お試しください";
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
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>新しいTODOを作成 - TODOアプリ</title>
      <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
      <?php include_once "../includes/header.php"; ?>
      <?php include_once "../includes/navbar.php"; ?>

      <div class="container">
          <h2>新しいTODOを作成</h2>
          <p>以下のフォームに入力して、新しいTODOタスクを作成してください</p>

          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
              <div class="form-group">
                  <label>タイトル</label>
                  <input type="text" name="title" class="form-control <?php echo (!empty($style_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $title; ?>">
                  <span class="invalid-feedback"><?php echo $title_err; ?></span>
              </div>
              <div class="form-group">
                  <label>説明（オプション）</label>
                  <textarea name="description" class="form-control"><?php echo $description; ?></textarea>
              </div>
              <div class="form-group">
                  <input type="submit" class="btn btn-primary" value="作成">
                  <a href="/todo/read.php" class="btn btn-secondary">キャンセル</a>
              </div>
          </form>
      </div>

    <?php include_once "../includes/footer.php"; ?>
  </body>
  </html>

