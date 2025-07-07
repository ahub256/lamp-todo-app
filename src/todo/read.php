<?php

require_once "../config/database.php";
require_once "../auth/auth.php";

requireLogin();

$conn = connectDB();

$user_id = getCurrentUserId();
$sql = "SELECT * FROM todos WHERE user_id = ? ORDER BY created_at DESC";

$todos = [];
if ($stmt = $conn->prepare($sql)) {
  $stmt->bind_param("i", $user_id);

  if ($stmt->execute()) {
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
    $todos[] = $row;
    }
  } else {
    echo "エラーが発生しました。後でもう一度お試しください";
  }

  $stmt->close();
}

$conn->close();
?>

<!DOCTYPE thml>
<html lang="ja">
  <head>
    <meta charset="UTF-8">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>TODOリスト - TODOアプリ</title>
    <link rel="stylesheet" href="/css/style.css">
  </head>
  <body>
<?php include_once "../includes/header.php"; ?>
<?php include_once "../includes/navbar.php"; ?>

  <div class="container">
      <h2>TODOリスト</h2>
      <p>
          <a href="/todo/create.php" class="btn btn-success">新しいTODOを作成</a>
      </p>

      <?php if (empty($todos)): ?>
          <div class="alert alert-info">
              TODOがありません。新しいタスクを作成してください。
          </div>
      <?php else: ?>
          <table class="table">
              <thead>
                  <tr>
                      <th>タイトル</tr>
                      <th>ステータス</tr>
                      <th>作成日時</tr>
                      <th>アクション</tr>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($todos as $todo): ?>
                  <tr>
                      <td><?php echo htmlspecialchars($todo["title"]); ?></td>
                      <td>
                          <span class="badge <?php
                              echo $todo["status"] === "完了" ? "badge-success" :
                                  ($todo["status"] ===  "進行中" ? "badge-warning" : "badge-secondary" )
                          ?>">
                              <?php echo htmlspecialchars($todo["status"]); ?>
                          </span>
                      </td>
                      <td>
                          <a href="/todo/update.php?id=<?php echo $todo["id"]; ?>" class="btn btn-sm btn-primary">編集</a>
                          <a href="/todo/delete.php?id=<?php echo $todo["id"]; ?>" class="btn btn-sm btn-danger" onclick="return confirm('本当に削除しますか？');">削除</a>
                      </td>
                  </tr>
                  <?php endforeach; ?>
              </tbody>
          </table>
      <?php endif; ?>
  </div>

  <?php include_once "../includes/footer.php"; ?>
</body>
</html>



