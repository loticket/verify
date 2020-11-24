<?php
namespace Lottery\Verify\Utils;

/**
* 排列组合类工具类
*/
class Combination
{   
  

//计算排列的数量
 public function arrange(int $n ,int $m): int {
    return $this->factorial($n)/$this->factorial($n-$m);
 }

 //计算组合的数量
 public function combinat(int $n ,int $m): int {
    return $this->arrange($n,$m)/$this->factorial($m);
 }

 //计算阶乘
  private function factorial(int $n): int {
    return array_product(range(1, $n));
  }

  //



}


?>