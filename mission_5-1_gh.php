<title>mission_5-1</title>
Posting Room<br>
<br>
</head>
<body>
    <!-- Database setting -->
<?php
//4-1 create database
$dsn = 'mysql:dbname=database';
    $user = 'user name';
    $password = 'password';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    
//4-2 create data table
    $sql="CREATE TABLE IF NOT EXISTS mission500"
    //もしまだこのテーブルが存在しないなら↑
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    //id:自動で登録されているナンバリング
    ."name char(32),"
    //name:名前を入れる。文字列(半角英数で32文字)
    ."comment TEXT,"
    //comment:コメントを入れる。文字列，長めの文章もOK
    ."date TEXT,"
    //投稿時間
    ."pass1 TEXT"
    .");";
    $stmt = $pdo->query($sql);
    
    $edit_name="";
    $edit_comment="";
    $edit_num="";
    
    //入力フォーム
    if(empty($_POST["edit_do"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass1"])){
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y/m/d H:i:s");
        $pass=$_POST["pass1"];
    
        //INSERT文：データ(レコード)を挿入 レコード：データ1件のこと！
        $sql = $pdo -> prepare("INSERT INTO mission500(name, comment, date, pass1) VALUES(:name, :comment, :date, :pass1)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass1', $pass, PDO::PARAM_STR);
        $sql -> execute();

        
    }
// delete function 
    //削除フォーム
    if(!empty($_POST["num_delete"]) && !empty($_POST["pass2"])){
        $delete=$_POST["num_delete"];
        $del_pass=$_POST["pass2"];

        //SELECT文：データレコードを取得
        $sql = 'SELECT * FROM mission500';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $row['id'].' ';
            $row['name'].' ';
            $row['comment'].' ';
            $row['date'].' ';
            $row['pass1']."<br>";
            
            
            //削除機能場合分け
            //データベースから取り出したidとpassを，フォームの値と比較
            //①id不一致，pass不一致
            if($row['id'] != $delete && $row['pass1'] != $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
                
            //②id一致，pass不一致
            }elseif($row['id'] == $delete && $row['pass1'] != $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
                
            //③id不一致，pass一致
            }elseif($row['id'] != $delete && $row['pass1'] == $del_pass){
                $row['id'].' ';
                $row['name'].' ';
                $row['comment'].' ';
                $row['date']. "<br>";
                "<hr>";
            }
                
            //idとpassが一致したら削除！
            if($row['id'] == $delete && $row['pass1'] == $del_pass){
            //DELETE文：データレコードを削除
            $id = $delete;
            $sql = 'delete from mission500 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }
        }
    }

//editing function --> bring posted contents back into the form
    if(!empty($_POST["num_edit"]) && !empty($_POST["pass3"])){
        $edit_num=$_POST["num_edit"];
        $edit_pass=$_POST["pass3"];    
    
        //SELECT文：データレコードを取得
        $sql = 'SELECT * FROM mission500';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            $row['id'].' ';
            $row['name'].' ';
            $row['comment'].' ';
            $row['date'].' ';
            $row['pass1']."<br>";

            //投稿番号と編集対象番号を比較
            //パスワードが一致した時のみ入力フォームに投稿内容を表示
            if($row['id'] == $edit_num && $row['pass1'] == $edit_pass){
                $edit_num=$row['id'];
                $edit_name=$row['name'];
                $edit_comment=$row['comment'];
                $edit_pass=$row['pass1'];
            }
        }
    }
                //上書きする編集実行機能        
                if(!empty($_POST["edit_do"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass1"])){
                    $id=$_POST["edit_do"];
                    $name=($_POST["name"]);
                    $comment=($_POST["comment"]);
                    $date=date("Y/m/d H:i:s");
                    $pass=($_POST["pass1"]);
                    
                        //UPDATE文：データレコードの編集
                        $sql = 'UPDATE mission500 SET name=:name,comment=:comment,date=:date WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt->execute();
                        
                        //SELECT文で取得
                        $sql = 'SELECT * FROM mission500';
                        $stmt = $pdo->query($sql);
                        $results = $stmt->fetchAll();
                        foreach($results as $row){
                            //$rowの中にはテーブルのカラム名が入る
                            $row['id'].' ';
                            $row['name'].' ';
                            $row['comment'].' ';
                            $row['date']. "<br>";
                            "<hr>";
                        }
                }


?>
<form action="" method="post">
Let's post comment with your name!<br>
<!-- name form  -->
<input type="text" name="name" placeholder="Name" value="<?php echo $edit_name; ?>"><br>
<!-- comment form  -->
<input type="text" name="comment" placeholder="comment" value="<?php echo $edit_comment; ?>"><br>
<!-- posting password form -->
<input type="text" name="pass1" placeholder="password">
<!-- hidden edit number form  -->
<input type="hidden" name="edit_do" value="<?php echo $edit_num;?>">
<!-- posting button  -->
<input type="submit" name="submit" value="submit"><br><br>
Delete the comment<br>
<!-- delete number form  -->
<input type="number" name="num_delete" placeholder="No." value=""><br>
<!-- deleting password  -->
<input type="text" name="pass2" placeholder="password">
<!-- delete implementation button -->
<input type="submit" name="delete" value="delete"><br><br>
Edit<br>
<!-- edit number form  -->
<input type="number" name="num_edit" placeholder="edit number"><br>
<!-- edit password  -->
<input type="text" name="pass3" placeholder="password">
<!-- edit implementation button  -->
<input type="submit" name="edit" value="edit">
</form><hr>

<?php
// reflection on the webpage func
//SELECT文：データレコードを取得して表示！
        $sql = 'SELECT * FROM mission500';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date']. "<br>";
            echo "<hr>";
        }
?>

<hr>
</body>
</html>
