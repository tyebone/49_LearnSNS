<?php
session_start();

//49_LearnSNSのセッションが空の場合、signup.phpに

//セッションを利用するための必須ルール
//PHPファイルの先頭に書くこと
$errors = [];

// check.phpから戻ってきた場合の処理
if (isset($_GET['action']) && $_GET['action'] == 'rewrite'){
// $_POSTに擬似的に値を代入する
// バリデーションを働かせるため


  $_POST['input_name'] = $_SESSION['49_LearnSNS']['name'];
  $_POST['input_email'] = $_SESSION['49_LearnSNS']['email'];
  $_POST['input_password'] = $_SESSION['49_LearnSNS']['password'];

  // $errorsが空の場合、check.phpへ再遷移してしまう
  $errors['rewrite'] = true;

}


#1.エラーだった場合に何のエラーかを保持する$errorsを定義
#2.送信されたデータと空文字を比較
#3.一致する場合は$errorsにnameをキーにblankという値を保持
#4.エラーがある場合エラーメッセージを表示

#1. errorsの定義

  $name = '';
  $email = '';


#POSTかどうか
    #2.空文字かどうか
if (!empty($_POST)){
    //post$nameにinput
    $name = $_POST['input_name'];
    $email = $_POST['input_email'];
    $password = $_POST['input_password'];
    //input属性がinput_passwordであるから'input_password'
    
    if ($name == ''){
    //''でからかどうか
    //ユーザー名が空である、という情報を保持
        $errors['name'] = 'blank';
    }if ($email == '') {
        $errors['email'] = 'blank';
    }
    $count = strlen($password);
 
    if ($password == ''){
        $errors['password'] = 'blank';
    //パスワードの文字数を数える
    //hogehogeと入力した場合$countには８が入る
    


    }elseif ($count < 4 || 16 < $count) {   
       //||演算子を使って４文字未満または１６文字より多い場合エラー
        $errors['password'] ='length';    
    }



    //$FILES[キー]['name']; ファイル名
    //$FILES[キー]['tmp_name']; ファイルデータそのもの
    $file_name = '';
    if(!isset($_GET['action'])){
       $file_name = $_FILES['input_img_name']['name'];
    }
    
    
    if (!empty($file_name)) {
        //ファイルの処理
        //拡張子チェックの流れ


        //1. 画像ファイルの拡張子を所得
        //2. 大文字は小文字に変換
        //3. jpg png gifと比較する
        //4. いずれかにも当てはまらない場合エラー

        // substr（文字列,何文字目から所得か指定）
$file_type = substr($file_name, -3);


        //strtolower(小文字にしたい文字列)
$file_type = strtolower($file_type);

if($file_type != 'jpg' && $file_type !='png' && $file_type != 'gif'){
    $errors['img_name'] = 'type';
    }

    } else {
        $errors['img_name'] ='blank';
    }


//エラーがなかった場合
    if(empty($errors)) {
//ファイルアップデートの処理
        //1.フォルダの権限設定
        //2.一意のファイル名生成
        //3.アップロード

        //一意のファイル名生成
        //現在の日時を所得　年月日時分秒まで所得
        $date_str = date('Ymdhis'); //YmdHisは所得フォーマット
        $submit_file_name = $date_str . $file_name;

        //画像アップロード
        //move_uploaded_file(ファイル,アップロード先)
        move_uploaded_file($_FILES['input_img_name']['tmp_name'],'../user_profile_img/' . $submit_file_name);

        //$_SESSION
        //セッションは各サーバの簡易的な保管庫
        //連想配列方式で値を保持する
        $_SESSION['49_LearnSNS']['name'] = $_POST['input_name'];
        $_SESSION['49_LearnSNS']['email'] = $_POST['input_email'];
        $_SESSION['49_LearnSNS']['password'] = $_POST['input_password'];
        $_SESSION['49_LearnSNS']['img_name'] = $submit_file_name;


        //check.phpへの遷移(移動)
        //header('Location: 移動先');
        header('Location: check.php');
        exit();
            }
}


?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="utf-8">
    <title>Learn SNS</title>
    <link rel="stylesheet" type="text/css" href="../assets/css/bootstrap.css">

    <link rel="stylesheet" type="text/css" href="../assets/font-awesome/css/font-awesome.css">

    <link rel="stylesheet" type="text/css" href="../assets/css/style.css">
</head>

<body style="margin-top: 60px">
    <div class="container">
        <div class="row">
            <div class="col-xs-8 col-xs-offset-2 thumbnail">
                <h2 class="text-center content_header">アカウント作成</h2>
                <!--
                    まずformタグのmethodとaction
                    singnup.phpでバリエーションをするのでsign.phpに置き換える

                    ファイルをアップロードする際の必須ルール
                    1. POST送信であること
                    2.enctyoe属性にmltipart/form-dataが設定されていること
                    

                -->
    <form method="POST" action="signup.php" enctype="multipart/form-data">

                    <div class="form-group">

            <label for="name">ユーザー名</label>
                    <!--
                        inputタグのname属性が$_POST
                    -->


    <input type="text" name="input_name" class="form-control" id="name" placeholder="山田 太郎" value="<?php echo htmlspecialchars($name); ?>">

                        <!--
                            valueの後のphpの前に空間を作らない
                            isset(連想配列[キー])連想配列のそのキーが設定されているかどうか
                        -->

    <?php if (isset($errors['name']) && $errors ['name'] == 'blank'):?>
    <p class="text-danger">ユーザー名を入力してください</p>
    <?php endif; ?>


</div>

    <div class="form-group">
        <label for="email">メールアドレス</label>
    <input type="email" name="input_email" class="form-control" id="email" placeholder="example@gmail.com" value="<?php echo htmlspecialchars($email); ?>">

<?php if(isset($errors['email']) && $errors ['email'] =='blank'):?>

<p class = "text-danger">メールアドレスを入力してください</p>
        <?php endif; ?>    

    </div>
        <div class="form-group">




            <label for="password">パスワード</label>

    <input type="password" name="input_password" class="form-control" id="password" placeholder="4 ~ 16文字のパスワード">
        <?php if (isset($errors['password']) && $errors['password'] == 'blank'):?>
            <p class="text-danger">パスワードを入力してください</p>
        <?php endif; ?>
        <?php if (isset($errors['password']) && $errors['password'] == 'length'):?>
            <p class="text-danger">パスワードは4~16文字で入力して下さい</p>
<?php endif; ?>

        <?php if(!empty($errors) && isset($errors['rewrite'])): ?>
        <p class="text-danger">パスワードを再入力してください</p>
    
    <?php endif; ?>
        </div>
    <div class="form-group">

            <label for="img_name">プロフィール画像</label>

                        <input type="file" name="input_img_name" id="img_name" accept="image/*">





<?php if(isset($errors['img_name']) && $errors['img_name'] == 'blank'):?>
    <p class="text-danger">画像を選択してください</p>
<?php endif; ?>

<?php if (isset($errors['img_name']) && $errors['img_name'] == 'type'):?>
        <p class="text-danger">拡張子がjpg,png,gifの画像を選択してください</p>
<?php endif; ?>




                    </div>
                    <input type="submit" class="btn btn-default" value="確認">
                    <span style="float: right; padding-top: 6px;">ログインは
                        <a href="../signin.php">こちら</a>
                    </span>
                </form>
            </div>
        </div>
    </div>
</body>
<script src="../assets/js/jquery-3.1.1.js"></script>
<script src="../assets/js/jquery-migrate-1.4.1.js"></script>
<script src="../assets/js/bootstrap.js"></script>
</html>