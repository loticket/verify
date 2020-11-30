<?php
namespace Loticket\Verify\Playlot;
use Loticket\Verify\Exception\PlayException;

class PlaySsq extends BasePlay implements PlayInterface {


    protected $redBallBet = [];


    protected $blueBallBet = [];

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
    *胆拖子玩法玩法格式验证 01,02|03,04,05,06-01|02,03
    *前驱胆拖  胆【0-4】 拖码 【2-33】 
    *后驱胆拖  胆【0-1】 拖码 【2-10】
    **/
    protected function dantuoPlayCheck(): bool {
     
      if(empty($this->ticket['lotnum']) || is_null($this->ticket['lotnum']) || !$this->ticket['lotnum']){
         return false;
      }

      if(strpos($this->ticket['lotnum'], "-") === false){
         return false;
      }

      list($redball,$blueball) = explode("-", $this->ticket['lotnum']);


      //判断是否有胆码
      if(substr_count($this->ticket['lotnum'],'|',0) == 0){
         return false;
      }

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

      $this->blueBallBet = explode(',', $blueball);

      $blueNums = count($this->blueBallBet);

      if($blueNums > $this->normal['blue'][1] || $blueNums < $this->normal['blue'][0]){
         return false;
      }

      //检查红球
      $flag = $this->ballIsInset($this->redBallBet,$this->redBall);
      if(!$flag){
          return false;
      }

      //检查篮球
      
      $flag = $this->ballIsInset($this->blueBallBet,$this->blueBall);
      if(!$flag){
          return false;
      }

      //计算注数
      $this->ticketNum = $this->Combination($rTuoArrNum,($this->ballrange[0]-$rDanArrNum)) * $blueNums;
      return true;
    }

    /**
    *@action 单式或者复式格式验证  01,02,04,08,10,45-01,02
    *@前驱 【5, 35】 后区 [2, 12]
    *@return 红球和篮球的数组
    */
    protected function normalPlayCheck(): bool {
      if(empty($this->ticket['lotnum']) || is_null($this->ticket['lotnum']) || !$this->ticket['lotnum']){
         return false;
      }

      if(strpos($this->ticket['lotnum'], "-") === false){
         return false;
      }

      list($redball,$blueball) = explode("-", $this->ticket['lotnum']);

      //检查红球

      $this->redBallBet = $this->checkBalls($redball,$this->normal['red'],$this->redBall);

      $redNum = count($this->redBallBet);

      if($redNum == 0){
          return false;
      }

      //检查篮球
      
      $this->blueBallBet = $this->checkBalls($blueball,$this->normal['blue'],$this->blueBall);

      $blueNum = count($this->blueBallBet);

      if($blueNum == 0){
         return false;
      }

      //计算注数
    
      $this->ticketNum = $this->Combination($redNum,$this->ballrange[0]) * $this->Combination($blueNum,$this->ballrange[1]);

      return true;
    }




}


?>