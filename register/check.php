<?php
session_start();

//require(ファイル名)
//指定されたファイルの中身がまるまる移植される
require('../dbconnect.php');

//セッションが空の場合,signup.phpに強制的に遷移
if (!isset($_SESSION['49_LearnSNS'])){
    //singup.phpへの遷移処理
    header('Location: signup.php');
    //exit()以降の処理はすべて行わない
    exit();
}


 $name = $_SESSION['49_LearnSNS']['name'];
 $email = $_SESSION['49_LearnSNS']['email'];
 $password = $_SESSION['49_LearnSNS']['password'];
//パスワードは極力表示しない
 $img_name = $_SESSION['49_LearnSNS']['img_name'];
 //セミコロン;
 //echo出力 $SESSIONスーパーグローバル変数 49_LearnSNS保管庫 nameキー


 //post送信された時
 if (!empty($_POST)) {

     //echo 'POST送信されました';
     //上はテスト(echo)

    //ユーザー登録処理
    $sql = 'INSERT INTO `users` (`name`,`email`,`password`,`img_name`,`created`)VALUES (?, ?, ?, ?,NOW())';
    //password_hash
    //文字列を単純に保管するのは危険
    //ハッシュ化という文字列の暗号化を行う

    $data =[$name, $email, password_hash ($password, PASSWORD_DEFAULT), $img_name];
    $stmt = $dbh->prepare($sql);
    $stmt->execute($data);

//不要になったセッション情報を破棄する
    unset($_SESSION['49_LearnSNS']);

//thanks.phpへ遷移
    header('Location: thanks.php');
        exit();

}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>Learn SNS</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">
</head>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">アカウント情報確認</h2>
                <div class="row">
                    <div class="col-xs-4">



        <img src="../user_profile_img/<?php echo htmlspecialchars($img_name); ?> " class="img-responsive img-thumbnail">



                    </div>
                    <div class="col-xs-8">
                        <div>
                            <span>ユーザー名</span>
                                <p class = "lead"><?php echo htmlspecialchars($name);?></p>
                        </div>
                        <div>
                            <span>メールアドレス</span>
                            <p class="lead"><?php echo htmlspecialchars($email);?></p>


                                        <?php //パスワードは表示させない ?>


                        </div>
                        <div>
                            <span>パスワード</span>

                            <p class="lead">●●●●●●●●</p>

                        </div>
                        <form method="POST" action="check.php">
                            <a href="signup.php?action=rewrite" class="btn btn-default">&laquo;&nbsp;戻る</a>
                            <!--
                                type="hidden"ブラウザ上には何も表示されない
                                ユーザーが入力/選択する必要はないが処理する上で必要なものを設定する
                             -->
                            <input type="hidden" name="action" value="submit">
                            <input type="submit" class="btn btn-primary" value="ユーザー登録">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/jquery-3.1.1.js"></script>
    <script src="../assets/js/jquery-migrate-1.4.1.js"></script>
    <script src="../assets/js/bootstrap.js"></script>
</body>
</html>