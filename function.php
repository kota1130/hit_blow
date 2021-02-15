<?php
require('database.php');

//文字列として入力された3桁の数字を配列で返す
function CreateUniqueNumber($input_string){
  $array = str_split($input_string);
  $unique_numbers = array_unique($array);
  return $unique_numbers;
}

//Hit数を取得する処理
function getHitCount($input_array, $enemy_array){
  $hit_count = 0;
  for($i = 0; $i < 3; $i++){
    for($j = 0; $j < 3; $j++){
      if($i === $j && $input_array[$i] === $enemy_array[$j]){
        $hit_count++;
        }
    }
  }  
  return $hit_count;
}

//Blow数を取得する処理
function getBlowCount($input_array, $enemy_array){
  $blow_count = 0;
  for($i = 0; $i < 3; $i++){
    for($j = 0; $j < 3; $j++){
      if($i !== $j && $input_array[$i] === $enemy_array[$j]){
        $blow_count++;
        }
    }
  }
  return $blow_count;
}

//ランダムな3桁の数字を配列で返す処理
function generateNumber(){
  $rand = substr(str_shuffle('1234567890'),0,3);
  return $rand;
}

//勝敗の判定処理
function isWinner($my_hit_count, $cpu_hit_count){
  if($my_hit_count === 3 && $cpu_hit_count === 3 ){
    $draw = '引き分けです';
    return $draw ;
  }
  if($my_hit_count ===3 && $cpu_hit_count !== 3){
    $win = 'あなたの勝ちです';
    return $win ;
  }
  if($my_hit_count !==3 && $cpu_hit_count === 3){
    $lose = 'あなたの負けです';
    return $lose ;
  }
}
?>