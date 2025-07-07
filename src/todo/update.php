<?php

require_once "../config/database.php";
require_once "../auth/auth.php";

requireLogin();

if (empty(trim($_GET["id"]))) {
  header("location: /todo/read.php");
  exit;
}

$id = trim($_GET["id"]);
$title = $description = $status = "";
$title_err = $description_err = "";

$conn = connectDB();

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty(trim($_POST["title"]))) {
    $title_err = "タイトルを入力してください";
  } else {
    $title = trim($_POST["title"]);
  }

  $description = trim($_POST["description"]);

  $status = trim($_POST["status"]);

  if (empty($title_err)) {
    $check_sql = "SELECT user_id FROM todos WHERE id = ?";
    if ($check_stmt = $conn->prepare($check_sql)) {
      $check_stmt->bind_param("i", $id);
      if ($check_stmt->execute()) {
        $result = $check_stmt->get_result();
        if ($result->num_rows == 1) {
          $row = $result->fetch_assoc();
          if ($row["user_id"] != getCurrentUserId()) {
            header("location: /todo/read.php");
            exit;
          }
        } else {
          header("location: /todo/read.php");
          exit;
        }
      }
      $check_stmt->close();
    }

    $sql = "UPDATE todos SET title = ?, description = ?, status = ?  WHERE id = ? AND user_id = ?";

    if ($stmt = $conn->prepare($sql)) {
      $stmt->bind_param("sssii", $param_title, $param_description, $param_status, $param_id, $param_user_id);

      $param_title = $title;
      $param_description = $description;
      $param_status = $status;
      $param_id = $id;
      $param_user_id = getCurrentUserId();

      if ($stmt->execute()) {
        header("location: /todo/read.php");
        exit;
      } else {
        echo "エラーが発生しました。後でもう一度お試しください";
      }

      $stmt->close();
    }
  }
} else {
  $sql = "SELECT * FROM todos WHERE id = ? AND user_id = ?";

  if ($stmt = $conn->prepare($sql)) {
    $stmt->bind_param("ii", $param_id, $param_user_id);

    $param_id = $id;
    $param_user_id = getCurrentUserId();

    if ($stmt->execute()) {
      $result = $stmt->get_result();

      if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();

        $title = $row["title"];
        $description = $row["description"];
        $status = $row["status"];
      } else {
        header("location: /todo/read.php");
        exit;
      }
    } else {
      echo "エラーが発生しました。後でもう一度お試しください";
    }

    $stmt->close();
  }
}

$conn->close();
?>


<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>TODOを編集 - TODOアプリ</title>
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
<?php include_once "../includes/header.php"; ?>
<?php include_once "../includes/navbar.php"; ?>

      <div class="container">
        <h2>TODOを編集</h2>
        <p>タスクの情報を編集してください</p>
        
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . "?id=" . $id; ?>" method="post">
            <div class="form-group">
                <label>タイトル</label>
                <input type="text" name="title" class="form-control <?php echo (!empty($title_err)) ? 'is-invalid' : ''; ?>" value="<?php echo htmlspecialchars($title); ?>">
                <span class="invalid-feedback"><?php echo $title_err; ?></span>
            </div>
            <div class="form-group">
                <label>説明（オプション）</label>
                <textarea name="description" class="form-cotrol"><?php echo htmlspecialchars($description); ?></textarea>
            </div>
            <div class="form-group">
                <label>ステータス</label>
                <select name="status" class="form-control">
                    <option value="未完了" <?php echo ($status == "未完了") ? "selected" : ""; ?>>未完了</option>
                    <option value="進行中" <?php echo ($status == "進行中") ? "selected" : ""; ?>>進行中</option>
                    <option value="完了" <?php echo ($status == "完了") ? "selected" : ""; ?>>完了</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="更新">
                <a href="/todo/read.php" class="btn btn-secondary">キャンセル</a>
            </div>
        </form>
      </div>

      <?php include_once "../includes/footer.php"; ?>
  </body>
</html>

