<?php
/**
 * @author FinalDevil
 * @copyright 2014
 */


$link1 = 'https://picasaweb.google.com/lh/photo/8ZZnCemRJfb4QjJsJtwQXNOydrU-8nQfVWbvDyT43k8?feat=directlink';
$link2 = 'https://picasaweb.google.com/103219276718020854069/Op?authkey=Gv1sRgCPih7_WYnbGKtAE#6038015163887814978';


class Picasa {
 private $link;
 private $type;
 private $obj_array;

 /**
  *
  * @param string $link
  */
 public function __construct($link) {
  $this->link = $link;
  $this->type = $this->check_link();
  $this->obj_array = $this->get_json($this->get_xml_link());
 }

 /**
  *
  * @return number
  */
 public function check_link(){
  if (preg_match('/directlink/', $this->link)){
   return 1;
  }else {
   return 2;
  }
 }


 /**
  *
  * @return boolean|mixed
  */
 public function view_source(){
  $timeout = 15;
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $this->link);
  curl_setopt($ch, CURLOPT_HTTPGET,true);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
  curl_setopt($ch, CURLOPT_FAILONERROR, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
  curl_setopt($ch, CURLOPT_ENCODING , 'gzip, deflate');
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  $result = curl_exec($ch);
  if(curl_errno($ch)){
   return false;
  }else{
   return $result;
  }
 }

 /**
  *
  * @return Ambigous <string, mixed>
  */
 public function get_xml_link(){
  $source = $this->view_source($this->link);
  if ( !$source){
   echo 'Link die';
   exit();
  }
  $xml_link = '';
  switch ($this->type){
   case 1:
    $xml_link = explode('"application/atom+xml","href":"', $source)[1];
    $xml_link = explode('"}', $xml_link)[0];
    break;
   case 2:
    $start = strpos($source, 'https://picasaweb.google.com/data/feed/base/user/');
    $end = strpos($source, '?alt=');
    $xml_link = substr($source, $start, $end - $start);
    $photoid = trim(explode('#', $this->link)[1], ' ');
    $xml_link .= '/photoid/' . $photoid . '?alt=jsonm&authkey=';
    $xml_link .= explode('#', explode('authkey=', $this->link)[1])[0];
    $xml_link = str_replace('base', 'tiny', $xml_link);
    break;
  }
  return $xml_link;
 }

 /**
  *
  * @param string $xml_link
  * @return stdClass
  */
 public function get_json($xml_link){
  $sourceJson = file_get_contents($xml_link);
     $decodeJson = json_decode($sourceJson);
     return $decodeJson->feed->media->content;
 }


 /**
  * @return string
  * It return 720p.mp4 if has, otherwise return 480p.mp4
  */
 public function get_720p_mp4(){
  for ($i = count($this->obj_array) - 1; $i >= 0; $i--){
   if ( $this->obj_array[$i]->type == 'video/mpeg4'){
    return $this->obj_array[$i]->url;
   }
  }
 }

 /**
  * @return string
  * It return 480p.mp4
  */
 public function get_480p_mp4(){
  for ($i = 0; $i < count($this->obj_array); $i++){
   if ( $this->obj_array[$i]->type == 'video/mpeg4'){
    return $this->obj_array[$i]->url;
   }
  }
 }

 public static function test(){
  echo 'test picasa';die();
 }
}


/*
 * Cách dùng rất đơn giản, chỉ cần new 1 đối tượng picasa, truyền vào
 * tham số là link picasa ở 1 trong hai dạng, nếu link chết thì code
 * sẽ tự thoát, mình không có xử lý phần link chết.
 * Gọi hai hàm tương ứng để lấy link pm4.
 * Chú ý: Nếu chỉ có chất lượng 480 mà gọi hàm 720 thì nó vẫn ra 480
 * Dùng dạng feat=directlink dễ bị chết hơn dạng thứ 2.
 */


// $picasa = new Picasa($link2);
// echo $picasa->get_480p_mp4() . '</br>';
// echo $picasa->get_720p_mp4() . '</br>';
?>