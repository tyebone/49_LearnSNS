<?php

session_start();
//一時的な保管庫

require('dbconnect.php'); //DBに接続する

//errorがあった時にこの配列に指定の文字列を入れる（バリデーションを使うため）
// 配列を初期化している
 $errors = [];

// $_POSTの中身が空じゃなかったらif文を実行する
 if (!empty($_POST)){
    $email = $_POST['input_email'];
    $password = $_POST['input_password'];
    if ($email != '' && $password != ''){
        //正常系
        //両方入力
        //データベースとの照合処理（送る為の準備）

        $sql = 'SELECT * FROM `users` WHERE `email` = ?';

//1.入力されたメールアドレスと一致する登録データを1件DBから所得

        $data = [$email];
        $stmt = $dbh->prepare($sql);
        $stmt->execute($data);
        // $recordはDBの１レコードにあたいする
        // 形式は連想配列
        // キーはカラムに依存する
        $record = $stmt->fetch(PDO::FETCH_ASSOC);

    //SELECT文の実行結果がある場合は連想配列
    //結果がない場合はfalseが入る
        if ($record == false) {
        $errors['signin'] = 'failed';
        }

        //2.パスワード照合
        // password_verify(文字列,ハッシュ化された文字列)
        // 指定した2つの文字列が合致する場合true
        if(password_verify($password, $record['password'])){
        //認証成功

        //3,セッションにIDを格納
            $_SESSION['49_LearnSNS']['id'] = $record['id'];
            //TODO:$_SESSION

        //4,タイムライン画面に遷移
            header('Location: timeline.php');
            exit();

        }else {
        //認証失敗
            $errors['signin'] = 'failed';
        }
    }else{
        $errors['signin'] = 'blank';
    }
}
  ?>

<?php include('layouts/header.php');//includeはrequireと同じで指定場所に指定したものを送る ?>
<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">サインイン</h2>
                <form method="POST" action="signin.php" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="email">メールアドレス</label>
                        <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com">
                            <?php if(isset($errors['signin']) && $errors['signin'] == 'blank'): ?>
                            <p class = "text-danger">メールアドレスとパスワードを正しく入力してください</p>
                        <?php endif;?>
                        <?php if(isset($errors['signin']) && $errors['signin'] == 'failed'): ?>
                            <p class="text-danger">サインインに失敗しました</p>
                        <?php endif; ?>    
                    </div>
                    <div class="form-group">
                        <label for="password">パスワード</label>
                        <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
                    </div>
                    <input type="submit" class="btn btn-info" value="サインイン">
                    <span style="float: right; padding-top: 6px;">
                        <a href="index.php">戻る</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<?php include('layouts/header.php'); ?>
</html>
