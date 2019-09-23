<?php

// XSS対策のため， $var を h($var) にする
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

$name = (string)filter_input(INPUT_POST, 'name');
$text = (string)filter_input(INPUT_POST, 'text');

$fp = fopen('data.csv', 'a+b');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    flock($fp, LOCK_EX); // 排他ロックを行う
    fputcsv($fp, [$name, $text]);
    rewind($fp);
}
flock($fp, LOCK_SH); // 共有ロックを行う、あるいは排他ロックから共有ロックに切り替える
while ($row = fgetcsv($fp)) {
    $rows[] = $row;
}
flock($fp, LOCK_UN); // ロック解除
fclose($fp);

?>
<!DOCTYPE html>
<meta charset="UTF-8">
<title>掲示板</title>
<section>
    <h2>新規投稿</h2>
    <form action="" method="post">
        名前：<input type="text" name="name" value=""><br>
        本文：<input type="text" name="text" value=""><br>
        <button type="submit">投稿</button>
    </form>
</section>
<section>
    <h2>投稿一覧</h2>
    <?php if (!empty($rows)): ?>
        <ul>
            <?php foreach ($rows as $row): ?>
                <li><?= h($row[1]) ?> (<?= h($row[0]) ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>投稿はまだありません</p>
    <?php endif; ?>
</section>
