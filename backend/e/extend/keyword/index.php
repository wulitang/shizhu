<?php
if($_POST){
  $title=$_POST[title];
  $arr=@dz_segment($title);
  if($arr){
    for($i=0;$i<count($arr);$i++){
      $key.=$key?",".$arr[$i]:$arr[$i];
    }
    echo $key;
  }
}


function dz_segment($title = '', $content = '', $encode = 'utf-8'){
    if($title == ''){
        return false;
    }
    $title = @rawurlencode(strip_tags($title));
    $content = @strip_tags($content);
    if(strlen($content)>2400){ //在线分词服务有长度限制
        $content =  mb_substr($content, 0, 800, $encode);
    }
    $content = rawurlencode($content);
    $url = 'http://keyword.discuz.com/related_kw.html?title='.$title.'&content='.$content.'&ics='.$encode.'&ocs='.$encode;
    $xml_array=simplexml_load_file($url);  
    $result = $xml_array->keyword->result;
    $data = array();
    foreach ($result->item as $key => $value) {
            array_push($data, (string)$value->kw);
    }
    if(count($data) > 0){
        return $data;
    }else{
        return false;
    }
 }
?>