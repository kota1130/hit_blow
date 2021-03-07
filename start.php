<?php
  require_once('Connect.php');
  require('function.php');
  require('User.php');

  //入力された値を変数として格納
  $input_number = $_POST['my_number'];
  
  if(!empty($_POST)){
    //バリデーション
    if(ctype_digit($input_number) && $input_number < 1000 ){
      $unique_my_numbers = CreateUniqueNumber($input_number);
      if(count($unique_my_numbers) === 3){
        $connect = new Connect();
        $insert  = new InsertData();

        //ユーザーが最初に設定した3桁の数字をDBへ保存する
        $insert->insertUserNumber($input_number);
        
        //ランダムに生成した値を変数に格納する
        $cpu_set_numbers = generateNumber();

        //CPUが最初に設定した3桁の数字をDBへ保存する
        $insert->insertCpuNumber($cpu_set_numbers);

        //前回のゲームスコアを削除する(自分)
        $stmt = $connect->pdo()->prepare('DELETE FROM select_numbers');
        $stmt->execute();
        
        ////前回のゲームスコアを削除する(CPU)
        $stmt = $connect->pdo()->prepare('DELETE FROM selected_numbers');
        $stmt->execute();

        ////前回のゲームスコアを削除する(CPU)
        $stmt = $connect->pdo()->prepare('DELETE FROM counts');
        $stmt->execute();

        header('Location: main.php');
        exit();
      }
      else{
        $error = 'blank';
      }
    }else{
      $error = 'blank';
    }
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Hit & Blow</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
  <div class="container">
    <div class="row">
      <div class="mx-auto" style="width: 480px;">
        <h1>Hit&Blow</h1>
        <p>あなた自身の3桁の数字を半角で入力してください</p>
        <p>※各桁で異なる数字を入力する必要があります。</p>
        <div class="form-group">
          <form method="POST" action="">
            <input class="form-control col-md-4" name = "my_number" type="text" placeholder="3桁の数字を入力"><br>
            <?php if($error === 'blank') :?>
              <p style="color:red">※3桁の異なる数字を入力して下さい。</p>
            <?php endif;?>
            <button class="btn btn-primary" type="submit" >ゲームスタート</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</body>
</html>