<?php

$fp = fopen('data.csv', 'a+b');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    fputcsv($fp, [$_POST['name'], $_POST['text']]);
    rewind($fp);
}
while ($row = fgetcsv($fp)) {
    $rows[] = $row;
}
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
                <li><?= $row[1] ?> (<?= $row[0] ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>投稿はまだありません</p>
    <?php endif; ?>
</section>

