<?php

require_once dirname(__DIR__) . "/auth/auth.php";
?>

<nav class="navbar">
    <div class="container">
        <ul class="navbar-nav">
            <li class="nav-item"><a href="/index.php" class="nav-link">ホーム</a></li>
            <li class="nav-item"><a href="/todo/read.php" class="nav-link">TODOリスト</a></li>
            <?php if (isLoggedIn()): ?>
                <li class="nav-item user-info">
                    <span>ようこそ<?php echo htmlspecialchars(getCurrentUsername()); ?>さん</span>
                </li>
                <li class="nav-item"><a href="/auth/logout.php" class="nav-link">ログアウト</a></li>
            <?php else: ?>
                <li class="nav-item"><a href="/auth/login.php" class="nav-link">ログイン</a></li>
                <li class="nav-item"><a href="/auth/register.php" class="nav-link">登録</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
