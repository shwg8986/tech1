<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8">
  <title>mission5-1</title>
</head>

<body>

<?php

// DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS tbtest"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name_d char(32),"
	. "comment_d TEXT,"
	. "date_d TEXT,"
	. "pass_d char(32)"
	.");";
$stmt = $pdo->query($sql);

// $filename = 'mission5-1.txt';
$name = $_POST['name'];
$comment = $_POST['comment'];
$pass = $_POST['pass'];
$delete = $_POST["delete"];
$delpass = $_POST["delpass"];
$edit=$_POST["edit"];
$editpass = $_POST["editpass"];
$editnum=$_POST["editnum"];
$now_date = date("Y年m月d日 H時i分s秒");
    
// フォームから値が送信されてきているかの確認
if(!empty($name) and !empty($comment) and !empty($pass) and empty($editnum)){
    $sql = $pdo -> prepare("INSERT INTO tbtest (name_d, comment_d, date_d, pass_d) VALUES (:name_d, :comment_d, :date_d, :pass_d)");
	$sql -> bindParam(':name_d', $name_d, PDO::PARAM_STR);
	$sql -> bindParam(':comment_d', $comment_d, PDO::PARAM_STR);
	$sql -> bindParam(':date_d', $date_d, PDO::PARAM_STR);
	$sql -> bindParam(':pass_d', $pass_d, PDO::PARAM_STR);
	$name_d = $name;
	$comment_d = $comment;
	$date_d = $now_date;
	$pass_d = $pass;
	$sql -> execute();
}

if(!empty($delete) and !empty($delpass)){ //and empty($_POST['name']) and empty($_POST['text'])
    $id = $delete;
	$sql = 'delete from tbtest where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

}

if(!empty($edit) and !empty($editpass)){
    $id = $edit; //変更する投稿番号
    $sql = 'SELECT * FROM tbtest WHERE id=:id ';
    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
    $stmt->execute();                             // ←SQLを実行する。
    $results = $stmt->fetchAll(); 
    foreach ($results as $row){
    	//$rowの中にはテーブルのカラム名が入る
    	$e_name = $row['name_d'];
    	$e_comment = $row['comment_d'];
    }
}

if(!empty($name) and !empty($comment) and !empty($pass) and !empty($editnum)){
    $id = $editnum; //変更する投稿番号
    $name_d = $name;
    $comment_d = $comment; 
    $date_d = $now_date;
	$pass_d = $pass;
    $sql = 'UPDATE tbtest SET name_d=:name_d,comment_d=:comment_d,date_d=:date_d,pass_d=:pass_d WHERE id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->bindParam(':name_d', $name_d, PDO::PARAM_STR);
    $stmt->bindParam(':comment_d', $comment_d, PDO::PARAM_STR);
    $stmt->bindParam(':date_d', $date_d, PDO::PARAM_STR);
    $stmt->bindParam(':pass_d', $pass_d, PDO::PARAM_STR);
    $stmt->execute();

}


?>
    
<form action="" method="post">
    
    <!--新規投稿か編集かでくべつするための分岐-->
    <?php if(!empty($edit) and !empty($editpass)){?>
        <p>名前とコメントを編集し、新たにパスワードを設定してください!!!</p>
    <?php } ?>
    
    <input type="text" name="name" value="<?php echo $e_name;?>" placeholder="名前を<?php if(!empty($edit) and !empty($editpass)){ 
        echo "編集";
    }else{ 
        echo "入力";
    }?>してください"><br>
 
    <input type="text" name="comment" value="<?php echo $e_comment;?>" placeholder="コメントを<?php if(!empty($edit) and !empty($editpass)){ 
        echo "編集";
    }else{ 
        echo "入力";
    }?>してください"><br>

    <input type="password" name="pass" placeholder="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "新たにパスワードを設定してください";
    }else{ 
        echo "パスワードを設定してください";
    }?>">
    
    <input type="hidden" name="editnum" value="<?php echo $edit; ?>"><br>
    
    <input type="submit" value="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "編集";
    }else{ 
        echo "新規投稿";
    }?>"><br><br>
    
    
    <!--編集番号がポストされた場合以下の削除フォームと編集フォームは不要になるのでその場合type属性をhiddenにして隠すための処理-->

    <input type="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "hidden";
    }else{ 
        echo "text";
    }?>" name="delete" placeholder="削除したい番号を入力してください"><br>
    
    <input type="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "hidden";
    }else{ 
        echo "password";
    }?>" name="delpass" placeholder = "パスワードを入力してください"><br>
    
    <input type="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "hidden";
    }else{ 
        echo "submit";
    }
    ?>" value="削除"><br><br>
    
    
    
    
    <input type="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "hidden";
    }else{ 
        echo "text";
    }?>" name="edit" placeholder="編集したい番号を入力してください"><br>
    
    <input type="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "hidden";
    }else{ 
        echo "password";
    }?>" name="editpass" placeholder = "パスワードを入力してください"><br>
    
    
    <input type="<?php if(!empty($edit) and !empty($editpass)){ 
        echo "hidden";
    }else{ 
        echo "submit";
    }
    ?>" value="入力"><br><br>
    
</form>
    
<?php
// $array = file($filename);
// for ($i = 0; $i < count($array); $i++) { //ループ処理
//   $data2 = explode("<>", $array[$i]); 
//   echo $data2[0] .".　<投稿者名：". $data2[1] ."さん>" ."　<コメント：".$data2[2] .">　<投稿日時：". $data2[3] .">". "<br>"; //投稿内容の取得
// }

$sql = 'SELECT * FROM tbtest';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		    echo $row['id'].',';
		    echo $row['name_d'].',';
		    echo $row['comment_d'].',';
		    echo $row['date_d'].'<br>';
	        echo "<hr>";
	}
?>

</body>
</html>