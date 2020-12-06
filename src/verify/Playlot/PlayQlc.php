<?php
namespace Loticket\Verify\Playlot;
use Loticket\Verify\Exception\PlayException;

//七乐彩
class PlayQlc extends BasePlay implements PlayInterface {

    protected $redBallBet = [];

    protected $ticketNum = 0;
    
    
    /**
    *@action 验证号码格式是否正确
    *@param 
    *@return bool
    **/
    public function playCheck(): bool {
       if($this->ticket['lottype'] == 1 || $this->ticket['lottype'] == 2){
         return $this->normalPlayCheck();
       }else{
         return $this->dantuoPlayCheck();
       }
       return false;
    }


   /**
    *胆拖子玩法玩法格式验证 01,02|03,04,05,06,07-01
    *前驱胆拖  胆【1,5】 拖码 【2, 32】 
    *后驱胆拖  无胆拖
    **/
    protected function dantuoPlayCheck(): bool {
     
      if(empty($this->ticket['lotnum']) || is_null($this->ticket['lotnum']) || !$this->ticket['lotnum']){
         return false;
      }

      $redball = $this->ticket['lotnum'];
      $rDanArr = []; //红球胆码
      $rTuoArr = []; //红球拖码 

      if(strpos($redball, '|') === false){
      	 return false;
      }

      
      list($rdan,$rtuo) = explode('|',$redball);
    
      $rDanArr = explode(',', $rdan);
      $rTuoArr = explode(',', $rtuo);
      

      //检查个数
      $rDanArrNum = count($rDanArr); //红球胆码个数
      $rTuoArrNum = count($rTuoArr); //红球拖码个数

      if ($rDanArrNum > $this->dantuo['reddan'][1] || $rDanArrNum < $this->dantuo['reddan'][0]) {
            return false;
      } 


      if ($rTuoArrNum > $this->dantuo['redtuo'][1] || $rTuoArrNum < $this->dantuo['redtuo'][0]) {
            return false;
      } 


      if(($rTuoArrNum + $rDanArrNum) <= $this->ballrange[0]){
            return false;
      }



      //检查胆码和托码 
      $redBallAll = array_intersect($rTuoArr,$rDanArr);

      if(count($redBallAll) != 0){
         return false;
      }

     
      $this->redBallBet = [...$rDanArr,...$rTuoArr];

      //检查红球
      $flag = $this->ballIsInset($this->redBallBet,$this->redBall);
      if(!$flag){
          return false;
      }

      //计算注数
      $this->ticketNum = $this->Combination($rTuoArrNum,($this->ballrange[0]-$rDanArrNum));
      return true;
    }

    /**
    *@action 单式或者复式格式验证  06,13,20,26,27,28,30
    *@前驱 【7, 30】
    *@return bool
    */
    protected function normalPlayCheck(): bool {
      if(empty($this->ticket['lotnum']) || is_null($this->ticket['lotnum']) || !$this->ticket['lotnum']){
         return false;
      }

      //检查红球
      $this->redBallBet = $this->checkBalls($this->ticket['lotnum'],$this->normal['red'],$this->redBall);

      $redNum = count($this->redBallBet);

      if($redNum == 0){
          return false;
      }

      //计算注数
    
      $this->ticketNum = $this->Combination($redNum,$this->ballrange[0]);

      return true;
    }

}




?>
