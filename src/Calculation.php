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
   * @return $tf_word_list 単語：tf値のdictionary
   */
  public function calc_tf_value(){
    $tf_word_list;
    foreach($this->word_list as $key => $value){
      //　tf値の計算
      $tf_value = $value / $this->word_all_count;
      // echo $key . " -> " .$tf_value . "<br>";
      $tf_word_list["$key"] = round($tf_value, 8);
    }
    return $tf_word_list;
  }

  /**
   * calc_idf_value idf値の計算
   * @param $all_document 全ドキュメントの数
   * @return $idf_value idf値
   */
  public function calc_idf_value($all_document){
    $df_value = 1 / $all_document;
    // スムージングを行い、idfの計算
    $idf_value = log(1+1 / $df_value+1);
    // echo $idf_value . "<br>";
    return $idf_value;
  }

  /**
   * calc_tf_idf tf-idfの計算
   * @param $tf　tf値(辞書型)
   * @param $idf idf値
   * @return $tf_idf_list tf-idf計算後の昇順に格納した辞書
   */
  public function calc_tf_idf($tf_list, $idf){
    $tf_idf_list;
    foreach($tf_list as $key => $value){
      // tf-idf値の算出
      $tf_idf = $idf * $value;
      $tf_idf_list["$key"] = round($tf_idf, 8);
      // echo $key . " -> " .$tf_idf . "<br>";
    }
    return $tf_idf_list;
  }
}
?>