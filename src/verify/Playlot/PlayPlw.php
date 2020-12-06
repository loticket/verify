<?php
namespace Loticket\Verify\Playlot;
use Loticket\Verify\Exception\PlayException;

//排列五
class PlayPlw extends BasePlay implements PlayInterface {

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
    *@action 单式 0;3;0;0;3 复式 4;3;3,5;3,7;4
    *@前驱 【1, 9】
    *@return bool
    */
    protected function normalPlayCheck(): bool {
      if(empty($this->ticket['lotnum']) || is_null($this->ticket['lotnum']) || !$this->ticket['lotnum']){
         return false;
      }

      if(substr_count($this->ticket['lotnum'], ';',1) != 4){
          return false;
      }

   
      $redNum = $this->checkBefore($this->ticket['lotnum']);
  
      if($redNum == 0){
         return false;
      }
      
      $this->ticketNum = $redNum;

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

}




?>
