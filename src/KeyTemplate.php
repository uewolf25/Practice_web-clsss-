<!-- Please create Instance in 'ConnectNetwork.php' 
    ex) $api_key = new Key();
        $api_key.getName();
-->
<?php
class Key{
  private $api_key = ' <Please set your API ID> ';

  public function getName(){
    return $this->api_key;
  }
}
?>
