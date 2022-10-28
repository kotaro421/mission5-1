<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5</title>
</head>
<body>
<form action="" method="post">
        <input type="text" name="name" placeholder="名前" value= "<?php if(!empty($e_name)) {echo $e_name;} ?>">
        <br>
        <input type="text" name="comment" placeholder="コメント" value= "<?php if(!empty($e_comment)) {echo $e_comment;} ?>">
        <input type="text" name="password" placeholder="パスワードを作成" value= "<?php if(!empty($e_pass)) {echo $e_pass;} ?>">
        <input type ="hidden" name="editNo" placeholder="編集する番号表示" value="<?php if(!empty($e_num)) {echo $e_num;} ?>">
        <input type="submit" name="submit">
        <br>
        <input type="text" name="delete" placeholder="削除したい投稿番号">
        <input type="text" name="de_pass" placeholder="設定したパスワード">
        <input type="submit" name="submit" value="削除">
        <br>
        <input type="text" name="edit" placeholder="編集したい投稿番号">
        <input type="text" name="e_pass" placeholder="設定したパスワード">
        <input type="submit" name="submit" value="編集">
    </form>

    <?php
    // DB接続設定
    $dsn = 'mysql:dbname=;host=localhost';
    $user = 'ユーザー名';
    $password = 'パスワード';
    
    //DB接続
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
    //テーブルを作成
    $sql = "CREATE TABLE IF NOT EXISTS boards"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"
    . "name char(32),"
    . "comment TEXT,"
    . "date char(32),"
    . "password TEXT"
    .");";
    $stmt = $pdo->query($sql); 
    
    //変数に値を代入
    $name = $_POST["name"];
    $comment = $_POST["comment"];
    $date = date("Y/m/d H:i:s");
    $delete = $_POST["delete"];
    $edit = $_POST["edit"]; 
    $e_No = $_POST["e_No"]; 
    $password = $_POST["password"];
    $de_pass = $_POST["de_pass"];
    $e_pass = $_POST["e_pass"];
    
    //新規投稿機能
    if (!empty ($name) && !empty ($comment) && empty ($e_No) && !empty($password)){
        $sql = $pdo -> prepare("INSERT INTO boards (name, comment, date, password) VALUES (:name, :comment, :date, :password)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':password', $password, PDO::PARAM_STR);
        $sql -> execute (); 
    }
    
    //データの抽出
    $sql = 'SELECT * FROM boards';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    
    //削除機能（DELETE文）
    foreach($results as $row){
        if ($row['id'] == $delete && $row['password'] == $de_pass){
            $sql = 'delete from boards where id=:id'; 
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
            $stmt->execute();
        }
    }
    
    //編集フォームに表示
    foreach($results as $row){
        if (!empty($edit) && $row['id'] == $edit && $row['password'] == $e_pass) { 
                $e_tnum = $row['id']; 
                $e_name = $row['name']; 
                $e_comment = $row['comment'];
                $e_pass = $row['password'];
        }
    }
    
    //編集実行（UPDATE文）
    if (!empty($name) && !empty($comment) && !empty($e_No)) {
        $id = $e_No; //変更する投稿番号
        $sql = 'UPDATE boards SET name=:name,comment=:comment,date=:date,password=:password WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->bindParam(':password',$password, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
    
    //表示機能（SELECT文）
    $sql = 'SELECT * FROM boards';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['date'].'<br>';
        echo "<hr>";
    }
    ?>
    
</body>
</html>
