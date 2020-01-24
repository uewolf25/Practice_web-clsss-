<?php
class Calculation{
  private $word_list;
  private $word_all_count;

  public function __construct($list, $count){
    $this->word_list = $list;
    $this->word_all_count = $count;
  }

  /**
   * calc_tf_value tf値の計算
   */
  public function calc_tf_value(){
    $tf_word_list;
    foreach($this->word_list as $key => $value){
      $tf_value = $value / $this->word_all_count;
      echo $key . " -> " .$tf_value . "<br>";
      $tf_word_list["$key"] = $tf_value;
    }
  }
}
?>