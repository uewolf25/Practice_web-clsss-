<?php
require('./Key.php');

class ConnectNetwork{
  // ConnectDBクラスの実体
  private $dbInstance;
  // 品詞
  private $word_class;
  // 自分のAPIキー
  private $my_key;

  /**
   * コンストラクタ
   */
  public function __construct($instance){
    $this->dbInstance = $instance;
    $this->word_class = "名詞";
    $this->my_key = new Key();
  }
  /**
   * httpRequest　サーバに接続し、ネットワークからデータの読み込みを行う。
   * @param $url クエリ(検索するURL)
   * @return $DATA　レスポンスから分離した配列
   */
  public function httpRequest($url){
    $purl = parse_url($url);
    $psheme = $purl["scheme"];
    $phost = $purl["host"];

    if (!isset($purl["port"])) {
        $pport = 80;
    } else {
        $pport = $purl["port"];
    }

    if (!isset($purl["path"])) {
        $ppath = "/";
    } else {
        $ppath = $purl["path"];
    }

    echo "<div class=\".network-class\">";
    echo "protocol：\t" . $psheme . "<br>";
    echo "host：\t" . $phost . "<br>";
    echo "port：\t" . $pport . "<br>";
    echo "path：\t" . $ppath . "<br> <hr>";
    echo "</div>";

    $hostname = $phost;
    $fp = fsockopen($hostname, $pport, $errno, $errstr);
    socket_set_timeout($fp, 10);

    $request = "GET " . $ppath . " HTTP/1.0\r\n\r\n";
    fputs($fp, $request);

    $response = "";
    while (!feof($fp)) {
        $response .= fgets($fp, 4096);
    }

    fclose($fp);

    $DATA = explode("\r\n\r\n", $response, 2);
    return $DATA;
  }

  /**
   * yahoo_mecab
   * @param $data　解析する文
   * @param $url　スクレイピングするURL
   */
  public function yahoo_mecab($data, $url){
    $q_url = $url;
    $apikey = $this->my_key->getName();

    $query = urlencode($data);
    $res = "surface,reading,pos,feature";
  
    $url = "http://jlp.yahooapis.jp/MAService/V1/parse?appid=" . $apikey . "&response=" . $res . "&sentence=" . $query ;
    $rss = file_get_contents($url); //リクエスト送信&レスポンス取得
    $xml = simplexml_load_string($rss); //取得したXMLを解析
  
    foreach($xml->ma_result->word_list->word as $item){
      $part_of_speech = $item->feature;
      if(mb_ereg($this->word_class, $part_of_speech) == 1){
        $contents = $item->surface;
        $this->dbInstance->insert_sql($q_url, $contents);
        $word_list[] = "$contents";
      }
    }
    return $word_list;
  }
}


?>