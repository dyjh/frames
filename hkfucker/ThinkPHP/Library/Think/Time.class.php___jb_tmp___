<?php
namespace Think;

class Tool{

  public function time($data){
    $time=date("Y-m-d",$data);
    $y=substr($time, 0, 4);
    $m=substr($time,6, 2);
    $d=substr($time,9, 2);
    $ed=$d+1;
    $times['start']=mktime(0,0,0,$m,$d,$y);
    $times['end']=mktime(0,0,0,$m,$ed,$y);
    return $times;
  }

  public function first($tel){
    $first=substr($tel, 0, 3);
    return $first;
  }
}
?>
