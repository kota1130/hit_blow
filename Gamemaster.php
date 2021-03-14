<?php 

  class GameMaster{
    
    public $my_score;
    public $cpu_score;
    
    public function __construct($my_score,$cpu_score){
          $this->$my_score = $my_score;
          $this->$cpu_score = $cpu_score;
        }

    //Hit数を取得する処理
    public function getHitCount($input_array, $enemy_array){
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
    public function getBlowCount($input_array, $enemy_array){
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

        //勝敗の判定処理
    public function isWinner($my_hit_count, $cpu_hit_count){
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
  }
?>