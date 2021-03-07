<?php 
require_once('Connect.php');
require_once('function.php');

  class User{
    
    public $my_number;
    
    public function __construct($my_number){
          $this->my_number = $my_number;
        }

    public function CallNumber($input_number){
      if(ctype_digit($input_number) && $input_number < 1000 ){
        $unique_my_numbers = CreateUniqueNumber($input_number);
        if(count($unique_my_numbers) === 3){
          //自分が入力した3桁の数値をデータベースへ保存する
          $insert = new InsertData();
          $insert->insertUserScore($input_number);
        }
      }
    }
  }

?>