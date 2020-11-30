<?php
namespace Loticket;
return [
  'dlt'=>[
     'redBall'=>["01", "02","03", "04", "05", "06", "07", "08", "09","10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21","22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33", "34", "35"], //前驱号码定义
     'blueBall' => ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12"], //后区号码定义
     'lottype'  => [1,2,3], //1 单式  2 复式  3；胆拖
     'playtype' => [1,2], //1 普通  2；追加
     'normal' => ['red'=>[5,35],'blue'=>[2,12]],
     'dantuo' => ['reddan'=>[0,4],'redtuo'=>[2,33],'bluedan'=>[0,1],'bluetuo'=>[2,10]],
     'ballrange' => [5,2],
     'maxmultiple' => [99],
  ],
  'ssq'=>[
     'redBall'=>["01", "02","03", "04", "05", "06", "07", "08", "09","10", "11", "12", "13", "14", "15", "16", "17", "18", "19", "20", "21","22", "23", "24", "25", "26", "27", "28", "29", "30", "31", "32", "33"], //前驱号码定义
     'blueBall' => ["01", "02", "03", "04", "05", "06", "07", "08", "09", "10", "11", "12","13","14","15","16"], //后区号码定义
     'lottype'  => [1,2,3], //1 单式  2 复式  3；胆拖
     'playtype' => [1], //1 普通  2；追加
     'normal' => ['red'=>[6,33],'blue'=>[1,16]],
     'dantuo' => ['reddan'=>[1,5],'redtuo'=>[2, 32],'bluedan'=>[0,0],'bluetuo'=>[1,16]],
     'ballrange' => [6,1],
     'maxmultiple' => [99],
  ],

];


?>