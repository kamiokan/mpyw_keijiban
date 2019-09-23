<?php

// POSTとして送信されてきたときのみ実行
// （通常アクセスはGET,フォーム送信はPOST）
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fp = fopen('data.csv', 'ab');
    fputcsv($fp, [$_POST['name'], $_POST['text']]);
    fclose($fp);
}

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
    <p>投稿はまだありません</p>
</section>

