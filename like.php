<?php
//データをつなぐために
require('dbconnect.php');

// app.jsからAjaxでPOST送信された値の所得
$feed_id = $_POST['feed_id'];
$user_id = $_POST['user_id'];

if(isset($_POST['is_unlike'])){
	$sql = 'DELETE FROM `likes`
		WHERE`feed_id` = ? AND `user_id` = ?';
}else{
	$sql = 'INSERT INTO `likes`(`feed_id`,`user_id`)VALUES(?,?)';
}
//DELETEとINSERTの両方にて動作が両方起きる

$data = [$feed_id, $user_id];
$stmt = $dbh->prepare($sql);
$res = $stmt->execute($data);

// 一番最後の出力がレスポンスとして返される
echo json_encode($res);