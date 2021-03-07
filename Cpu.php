<?php 
require_once('Connect.php');

  class Cpu{
    
    public $cpu_number;
    
    public function __construct($cpu_number){
          $this->cpu_number = $cpu_number;
        }

    public function CallSelectedNumber($input_number){
          //CPUが相手の数字を当てるために生成した3桁の数値をデータベースへ保存する
          $insert = new InsertData();
          $insert->insertCpuScore($input_number);
    }
  }

?>