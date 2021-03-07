<?php
require_once('Connect.php');

//文字列として入力された3桁の数字を配列で返す
function CreateUniqueNumber($input_string){
  $array = str_split($input_string);
  $unique_numbers = array_unique($array);
  return $unique_numbers;
}

//ランダムな3桁の数字を配列で返す処理
function generateNumber(){
  $rand = substr(str_shuffle('1234567890'),0,3);
  return $rand;
}
?>