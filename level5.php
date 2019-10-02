<?php

// エラーを強制表示する
error_reporting(E_ALL & ~ E_DEPRECATED & ~ E_USER_DEPRECATED & ~ E_NOTICE);
ini_set('display_errors', "On");

// XSS対策のため， $var を h($var) にする
function h($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

session_start();

$name = (string)filter_input(INPUT_POST, 'name');
$text = (string)filter_input(INPUT_POST, 'text');
$token = (string)filter_input(INPUT_POST, 'token');

$fp = fopen('data.json', 'a+b');
// ファイル内容全てを読み取り，JSON形式としてデコードする
// 空文字列をデコードしたときにはNULLになるので，配列にキャストして空の配列にする
$rows = (array)json_decode(stream_get_contents($fp), true);
//var_export($rows); // デバッグ用
if ($_SERVER['REQUEST_METHOD'] === 'POST' && sha1(session_id()) === $token) {
    // もし投稿内容があれば，読み取った配列変数に要素を追加する
    $rows[] = ['name' => $name, 'text' => $text];
    // ファイルの中身をいったん全消去する (もしaモード以外でオープンしている場合rewindも必要)
    ftruncate($fp, 0);
    rewind($fp);
    // ファイルにJSON形式として配列全体を上書きする (オプションは読みやすくするためのもの)
    fwrite($fp, json_encode($rows, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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
        <input type="hidden" name="token" value="<?= h(sha1(session_id())) ?>">
    </form>
</section>
<section>
    <h2>投稿一覧</h2>
    <?php if (!empty($rows)): ?>
        <ul>
            <?php foreach ($rows as $row): ?>
                <li><?= h($row['text']) ?> (<?= h($row['name']) ?>)</li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>投稿はまだありません</p>
    <?php endif; ?>
</section>
