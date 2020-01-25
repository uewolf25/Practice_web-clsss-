<!-- 
  @author : 
  学籍番号：
  できたところ：
  作成最終日：
  入力URL：
  実行にあたって：はじめにテーブルを用意しておかないとエラーが出ます
  ```
  CREATE TABLE list(
    id integer primary key autoincrement,
    url text,
    contents text
  );
  ```
 -->
<?php
require('./ConnectDB.php');
require('./ConnectNetwork.php');
require('./Calculation.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="stylesheet" href="../css/index.css" type="text/css">
  <title>最終課題</title>
</head>
<body>
  <div class="input-form-class">
    <form action="<?php echo $_SERVER['PHP_SELF']?>" method="post">
    <p>
      <input type="text" name="query" placeholder="URLを入力!!" />
      <input type="submit" value="送信" />
    </p>
    </form>
  </div>

  <?php
  // 入力URL
  $q = $_POST['query'];

  if( isset($q) ){
    $connectDb = new ConnectDB('list');
    // 既存のデータの削除
    $connectDb->delete_sql();

    $socket = new ConnectNetwork($connectDb);
    
    list($head, $data) = $socket->httpRequest($q);
    $data = strip_tags($data);

    $size = 0;
    $size = 512;
    $end = mb_strlen($data);
    // echo $size;
    $words;
    do{
      $data2 = mb_substr($data, $start-$end, $size);
      $words = $socket->yahoo_mecab($data2, $q);
      $start += $size;
      foreach($words as $item){
        $word["$item"] = $item;
        // echo $item . "<br>";
      }
    } while( ($start-$size) <= $end );

    foreach($word as $value){
      $keyword = array_search($value, $word);
      if($keyword === false){
        $list_word["$value"] = 1;
      }
    }

    //　全ての単語数
    $word_all_count = 0;
    foreach($word as $keyword => $value){
      $q = "\"" . $keyword . "\"";
      $count = $connectDb->count_sql($q)->fetchColumn();
      // 単語のカウント
      // echo "単語出現回数：　" . $count . "<br>";
      $list_word["$keyword"] = $count;
      $word_all_count += $count;
    }

    // 昇順にソート
    arsort($list_word);

    $calc = new Calculation($list_word, $word_all_count);
    $tf_value = $calc->calc_tf_value();
    $idf_value = $calc->calc_idf_value( $connectDb->get_count_document() );
    $tfidf_list = $calc->calc_tf_idf($tf_value, $idf_value);

    // $index_count = 0;
    // foreach($tfidf_list as $key => $value){
    //   // echo $key . " -> " .$value . "<br>";
    //   $index_count++;
    //   if($index_count == 10) break;
    // }
  }
  ?>

  <!-- 結果の出力 -->
  <table>
    <caption>単語の重み付け(小数第９位以下は四捨五入)</caption>
      <tr>
        <th>ランキング</th>
        <th>単語</th>
        <th>単語の出現回数</th>
        <th>tf値</th>
        <th>idf値</th>
        <th>tf-idf値</th>
      </tr>
      <? 
      $index_count = 0;
      foreach($tfidf_list as $key => $tfidf_value){ 
        $index_count++;
        if($index_count > 10) break;
        ?>
      <tr>
        <td>
          <?=$index_count ?>
        </td>
        <td>
          <?=$key ?>
        </td>
        <td>
          <?=$list_word[$key] ?>
        </td>
        <td>
          <?=$tf_value[$key] ?>
        </td>
        <td>
          <?=$idf_value ?>
        </td>
        <td>
          <?=$tfidf_value ?>
        </td>
      </tr>

      <?php } ?>
  </table>

  <div class="botton-color-class">
    <form action="">
      <p>
        <input type="submit" value="もっと見る" name="more" />
      </p>
    </form>
  </div>

  <?php
  // もっとみたいボタンを押された時
  $query = $_POST["more"];
  if( isset($query) ){ ?>
    <!-- 結果の出力 -->
  <table>
    <caption>単語の重み付け(小数第９位以下は四捨五入)</caption>
      <tr>
        <th>ランキング</th>
        <th>単語</th>
        <th>単語の出現回数</th>
        <th>tf値</th>
        <th>idf値</th>
        <th>tf-idf値</th>
      </tr>
      <? foreach($tfidf_list as $key => $tfidf_value){ ?>
      <tr>
        <td>
          <?=$index_count ?>
        </td>
        <td>
          <?=$key ?>
        </td>
        <td>
          <?=$list_word[$key] ?>
        </td>
        <td>
          <?=$tf_value[$key] ?>
        </td>
        <td>
          <?=$idf_value ?>
        </td>
        <td>
          <?=$tfidf_value ?>
        </td>
      </tr>

      <?php } ?>
  </table>

  <?php } ?>
</body>
</html>