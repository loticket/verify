<?php
namespace Loticket\Verify\Playlot;
use Loticket\Verify\Exception\PlayException;

//七星彩
class PlayQxc extends BasePlay implements PlayInterface {

    protected $ticketNum = 0;
    
    
    /**
    *@action 验证号码格式是否正确
    *@param 
    *@return bool
    **/
    public function playCheck(): bool {
      return $this->normalPlayCheck();
    }

    /**
    *@action 单式 0,3,0,0,3,9,2-10 复式 4;3;3,5;3,7;4;6-4,6,14
    *@前驱 【7, 30】
    *@return bool
    */
    protected function normalPlayCheck(): bool {
      if(empty($this->ticket['lotnum']) || is_null($this->ticket['lotnum']) || !$this->ticket['lotnum']){
         return false;
      }

      if(strpos($this->ticket['lotnum'], "-") === false){
         return false;
      }


      if(substr_count($this->ticket['lotnum'], ';',1) != 5){
          return false;
      }

      list($redball,$blueball) = explode('-', $this->ticket['lotnum']);

      $redNum = $this->checkBefore($redball);
  
      if($redNum == 0){
         return false;
      }


      $blueNum = $this->checkAfter($blueball);

      if($blueNum == 0){
         return false;
      }

      $this->ticketNum = $redNum * $blueNum;

      return true;
    }


    /**
     *@action 验证前驱格式的号码数组并排序
     *@parame string $ball      投注号码字符串
     *@return int               号码的个数
     **/
     
    protected function checkBefore(string $ball): int {
        $ballArr = explode(';', $ball);
        $betZhu = 1;
        foreach ($ballArr as $key => $value) {
         $flagArr = $this->checkBalls($value,$this->normal['red'],$this->redBall);
         $betZhu *= count($flagArr);
         if(count($flagArr) == 0){
           break;
         }
        }
        unset($ballArr);
       return $betZhu;
    } 


     /**
     *@action 验证格式的号码数组并排序
     *@parame string $ball      投注号码字符串
     *@return int               号码的个数
     **/
     
    protected function checkAfter(string $ball): int {
      $flagArr = $this->checkBalls($ball,$this->normal['blue'],$this->blueBall);
      return count($flagArr);
    } 


}




?>
