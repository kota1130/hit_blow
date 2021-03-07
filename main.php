<?php
  require('Connect.php');
  require_once('function.php');
  require('User.php');
  require('Cpu.php');
  require('Gamemaster.php');

  //データベース関連の処理を行うクラスのインスタンスを作成
  $select = new SelectData();
  $insert = new InsertData();

  //Hit&Blow並びに勝敗判定の処理を行うクラスのインスタンスを作成
  $gamemaster = new GameMaster($my_score_numbers,$cpu_score_numbers);

  //自分が設定した3桁の数字を取得
  $user = new User($select->selectUserData());
  $set_my_number = $user->my_number;
  
  //CPUがランダムに生成した3桁の数字を取得
  $cpu = new Cpu($select->selectCpuData());
  $set_cpu_number = $cpu->cpu_number;
  
  //自分が入力した値を変数として格納する
  $input_number = $_POST['select_number'];

  //自分自身のスコアを取得
  $my_score_numbers = $select->selectMyScore();

  //CPUのスコアを取得
  $cpu_score_numbers = $select->selectCpuScore();

  if(!empty($_POST)){
    //バリデーション
    if(ctype_digit($input_number) && $input_number < 1000 ){
      $unique_my_numbers = CreateUniqueNumber($input_number);
      if(count($unique_my_numbers) === 3){
        //自分が入力した3桁の数値をデータベースへ保存する
        $user->CallNumber($input_number);

        //CPUが数字を当てる(CPUが入力した3桁の数値をデータベースへ保存する)
        $input_cpu_number = generateNumber();
        $cpu->CallSelectedNumber($input_cpu_number);

        //CPUが相手の数字を当てるために入力した最新の数字(スコア)を取得
        $cpu_last_score = $select->selectCpuLastScore();

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
    <div class="mt-3">
      <h1>Hit&Blow</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <?php echo "<h2>あなたの数字：" .$set_my_number['number']. "</h2>"; ?>
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
                  <?php $my_hit = $gamemaster->getHitCount($my_score_number['score'],$set_cpu_number['number'])?>
                <td><?php echo $my_hit; ?></td>
                  <?php $my_blow = $gamemaster->getBlowCount($my_score_number['score'],$set_cpu_number['number'])?>
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
                  <?php $cpu_hit = $gamemaster->getHitCount($cpu_score_number['score'],$set_my_number['number'])?>
                <td><?php echo $cpu_hit; ?></td>
                  <?php $cpu_blow = $gamemaster->getBlowCount($cpu_score_number['score'],$set_my_number['number'])?>
                <td><?php echo $cpu_blow; ?></td>
              </tr>
            <?php endforeach; ?>
            <?php $insert->insertCpuResult($cpu_hit,$cpu_blow); ?>
            <?php $result = $gamemaster->isWinner($my_hit,$cpu_hit); ?>
          </tbody>
        </table>
      </div>
    </div>
    <?php if(isset($result)) :?>
      <?php echo "<h1>". $result. "</h1>"; ?>
      <button class="btn btn-primary" onclick="location.href='start.php'" style="margin: 16px 0 16px 0">もう一度ゲームで遊ぶ</button>
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