<?php 
require_once('Connect.php');

  class Cpu{
    
    public $cpu_number;
    
    public function __construct($cpu_number){
          $this->cpu_number = $cpu_number;
        }

    public function CallSelectedNumber(){
          // 過去の最新データから勝利の期待値が高い数字を生成する
          $select = new SelectData();
          $cpu_past_scores = $select->selectCpuResult();

          $all_numbers = '1234567890';
          for($i = count($cpu_past_scores) - 1; $i >= 0 ; $i-- ){
            //1Hit 2Blowのデータがある場合
            if(($cpu_past_scores[$i]['hit'] == 1 && $cpu_past_scores[$i]['blow'] == 2)){
              $output_number = $cpu_past_scores[$i]['cpu_score'][0] . $cpu_past_scores[$i]['cpu_score'][2] . $cpu_past_scores[$i]['cpu_score'][1];
              return $output_number;
            }
                //0Hit 3Blowのデータがある場合
                elseif($cpu_past_scores[$i]['hit'] == 0 && $cpu_past_scores[$i]['blow'] == 3){
                $output_number = $cpu_past_scores[$i]['cpu_score'][1] . $cpu_past_scores[$i]['cpu_score'][2] . $cpu_past_scores[$i]['cpu_score'][0];
                return $output_number;
              } 

            //0Hit 0Blowのデータがある場合
            if($cpu_past_scores[$i]['hit'] == 0 && $cpu_past_scores[$i]['blow'] == 0){
              
              $base_number = str_split('1234567890');
              $except_number = str_split($cpu_past_scores[$i]['cpu_score'][0] . $cpu_past_scores[$i]['cpu_score'][1] . $cpu_past_scores[$i]['cpu_score'][2]);
              $remainder  = implode(array_diff($base_number, $except_number));
              
            }
            else{
              $remainder = '1234567890';
            }
            
            //2Hit 0Blowのデータがある場合
            if($cpu_past_scores[$i]['hit'] == 2 && $cpu_past_scores[$i]['blow'] == 0){
              //2桁分の数字を同じ桁で残し、残りの1桁分の数字はランダムに生成する
              $new_base_number = str_split($remainder);
              $new_except_number = str_split($cpu_past_scores[$i]['cpu_score'][0]. $cpu_past_scores[$i]['cpu_score'][1]. $cpu_past_scores[$i]['cpu_score'][2]);
              $new_remainder = str_shuffle(implode(array_diff($new_base_number, $new_except_number)));
              $output_number = $cpu_past_scores[$i]['cpu_score'][0] . $cpu_past_scores[$i]['cpu_score'][1] .substr($new_remainder,0,1);
              return $output_number;
            }
                //1Hit 1Blowのデータがある場合
                elseif($cpu_past_scores[$i]['hit'] == 1 && $cpu_past_scores[$i]['blow'] == 1){
                  $new_base_number = str_split($remainder);
                  $new_except_number = str_split($cpu_past_scores[$i]['cpu_score'][0]. $cpu_past_scores[$i]['cpu_score'][1]. $cpu_past_scores[$i]['cpu_score'][2]);
                  $new_remainder = str_shuffle(implode(array_diff($new_base_number, $new_except_number)));
                  $output_number = $cpu_past_scores[$i]['cpu_score'][0] .substr($new_remainder,0,1). $cpu_past_scores[$i]['cpu_score'][1];
                  return $output_number;
                }
                //0Hit 2Blowのデータがある場合
                elseif($cpu_past_scores[$i]['hit'] == 0 && $cpu_past_scores[$i]['blow'] == 2){
                  $new_base_number = str_split($remainder);
                  $new_except_number = str_split($cpu_past_scores[$i]['cpu_score'][0]. $cpu_past_scores[$i]['cpu_score'][1]. $cpu_past_scores[$i]['cpu_score'][2]);
                  $new_remainder = str_shuffle(implode(array_diff($new_base_number, $new_except_number)));
                  $output_number = $cpu_past_scores[$i]['cpu_score'][1] . $cpu_past_scores[$i]['cpu_score'][0] .substr($new_remainder,0,1);
                  return $output_number;
                }
                //1Hit 0Blowのデータがある場合
                elseif($cpu_past_scores[$i]['hit'] == 1 && $cpu_past_scores[$i]['blow'] == 0){
                  $new_base_number = str_split($remainder);
                  $new_except_number = str_split($cpu_past_scores[$i]['cpu_score'][0]. $cpu_past_scores[$i]['cpu_score'][1]. $cpu_past_scores[$i]['cpu_score'][2]);
                  $new_remainder = str_shuffle(implode(array_diff($new_base_number, $new_except_number)));
                  $output_number = $cpu_past_scores[$i]['cpu_score'][0] . substr($new_remainder,0,1) .substr($new_remainder,1,1);
                  return $output_number;
                }
                //0Hit 1Blowのデータがある場合
                elseif($cpu_past_scores[$i]['hit'] == 0 && $cpu_past_scores[$i]['blow'] == 1){
                  $new_base_number = str_split($remainder);
                  $new_except_number = str_split($cpu_past_scores[$i]['cpu_score'][0]. $cpu_past_scores[$i]['cpu_score'][1]. $cpu_past_scores[$i]['cpu_score'][2]);
                  $new_remainder = str_shuffle(implode(array_diff($new_base_number, $new_except_number)));
                  $output_number = substr($new_remainder,0,1) . $cpu_past_scores[$i]['cpu_score'][0] . substr($new_remainder,1,1);
                  return $output_number;
                  
                }
                else{
                  $output_number = substr(str_shuffle($remainder),0,3);;
                  return $output_number;
                }
          }
    }
  }

?>
