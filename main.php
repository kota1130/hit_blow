<?php
  require('database.php');
  require('function.php');

  //自分が設定した3桁の数字を取得
  $sql = "SELECT number FROM my_numbers WHERE id = (SELECT MAX(id) FROM my_numbers) ";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $set_my_number = $stmt->fetch(PDO::FETCH_ASSOC);

  //CPUがランダムに生成した3桁の数字を取得
  $sql= "SELECT number FROM cpu_numbers WHERE id = (SELECT MAX(id) FROM cpu_numbers) ";
  $stmt = $db->prepare($sql);
  $stmt->execute();
  $set_cpu_number = $stmt->fetch(PDO::FETCH_ASSOC);

  //CPUの数字(テスト時のみ表示)
  // echo "<h1>CPUの数字</h1>";
  // echo "<h1>".$set_cpu_number['number'] ."</h1>";
  
  //c.数字を当てる(自分：数字を入力する)
  $input_number = $_POST['select_number'];

  if(!empty($_POST)){
    //バリデーション
    if(ctype_digit($input_number) && $input_number < 1000 ){
      $unique_my_numbers = CreateUniqueNumber($input_number);
      if(count($unique_my_numbers) === 3){
        //自分が入力した3桁の数値をデータベースへ保存する
        $stmt = $db->prepare('INSERT INTO select_numbers SET score=?');
        $stmt->execute(array($input_number));

        //CPUが数字を当てる(CPUが入力した3桁の数値をデータベースへ保存する)
        $input_cpu_number = generateNumber();
        $stmt = $db->prepare('INSERT INTO selected_numbers SET score=?');
        $stmt->execute(array($input_cpu_number));


        $sql= "SELECT number FROM my_numbers WHERE id = (SELECT MAX(id) FROM my_numbers) ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $my_number = $stmt->fetch(PDO::FETCH_ASSOC);

        $sql= "SELECT score FROM selected_numbers WHERE id = (SELECT MAX(id) FROM selected_numbers) ";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $cpu_score_number = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $cpu_hit = getHitCount($cpu_score_number['score'],$my_number['number']);
        $cpu_blow = getBlowCount($cpu_score_number['score'],$my_number['number']);
        
        $stmt = $db->prepare('INSERT INTO counts SET hit=?, blow=?');
        $stmt->execute(array($cpu_hit,$cpu_blow));
        
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

  //自分が数字を当てるために入力した値を取得する
  $my_score_numbers = $db->query('SELECT score FROM select_numbers');

  //CPUが数字を当てるために入力した値を取得する
  $cpu_score_numbers = $db->query('SELECT score FROM selected_numbers');

  

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
    <div class="mt-3">
      <h1>Hit&Blow</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <?php echo "<h2>あなたの数字：" .htmlspecialchars($set_my_number['number'],ENT_QUOTES). "</h2>"; ?>
      </div>
      <div class="col-md-6">
        <p>自分</p>
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Score</th>
              <th scope="col">Hit</th>
              <th scope="col">Blow</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($my_score_numbers as $my_score_number): ?>
              <tr>
                <th scope="row"><?php echo htmlspecialchars($my_score_number['score'],ENT_QUOTES);?></th>
                  <?php $my_hit = getHitCount($my_score_number['score'],$set_cpu_number['number'])?>
                <td><?php echo $my_hit; ?></td>
                  <?php $my_blow = getBlowCount($my_score_number['score'],$set_cpu_number['number'])?>
                <td><?php echo $my_blow; ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <div class="col-md-6">
        <p>CPU</p>
        <table class="table">
          <thead class="thead-dark">
            <tr>
              <th scope="col">Score</th>
              <th scope="col">Hit</th>
              <th scope="col">Blow</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach($cpu_score_numbers as $cpu_score_number): ?>
              <tr>
                <th scope="row"><?php echo $cpu_score_number['score'];?></th>
                  <?php $cpu_hit = getHitCount($cpu_score_number['score'],$set_my_number['number'])?>
                <td><?php echo $cpu_hit; ?></td>
                  <?php $cpu_blow = getBlowCount($cpu_score_number['score'],$set_my_number['number'])?>
                <td><?php echo $cpu_blow; ?></td>
              </tr>
            <?php endforeach; ?>
            <?php $result = isWinner($my_hit,$cpu_hit); ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php if(isset($result)) :?>
      <?php echo "<h1>". $result. "</h1>"; ?>
      <button class="btn btn-primary" onclick="location.href='start.php'">もう一度ゲームで遊ぶ</button>
    <?php else:?>
      <div class="form-group" style="padding-top:64px;">
          <form method="POST" action="">
            <input class="form-control col-md-2" name = "select_number" type="text" placeholder="3桁の数字を入力"><br>
            <?php if($error === 'blank') :?>
              <p style="color:red">※3桁の異なる数字を入力して下さい。</p>
            <?php endif;?>
            <button class="btn btn-info" type="submit" >決定</button>
          </form>
      </div>
    <?php endif; ?>
    
      <div class="">
        <a href="start.php">スタート画面へ戻る</a>
      </div>
  </div>
</body>
</html>