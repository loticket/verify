<?php
namespace Loticket\Verify\Playlot;
use Loticket\Verify\Exception\PlayException;

//排列三
class PlayPls extends BasePlay implements PlayInterface {

   protected int $ticketNum = 0;

   

    /**
    *@action 验证号码格式是否正确
    *@param 
    *@return bool
    **/
    public function playCheck(): bool {
      if(empty($this->ticket['lotnum']) || is_null($this->ticket['lotnum']) || !$this->ticket['lotnum']){
         return false;
      }

      switch ($this->ticket['lottype']) {
      	case 1:
      		return $this->zhixuanSingle();
      		break;
      	case 2:
      		return $this->zhixuanComplex();
      		break;
      	case 3:
      		return $this->zhixuanSum();
      		break;
      	case 4:
      		return $this->z3Single();
      		break;
      	case 5:
      		return $this->z6Single();
      		break;
      	case 6:
      		return $this->z3Sums();
      		break;
      	case 7:
      		return $this->z6Sums();
      		break;
      	case 8:
      		return $this->z6z3Sum();
      		break;
      	case 9:
      		return $this->z3DanTuo();
      		break;
      	case 10:
      		return $this->z6DanTuo();
      		break;
      	default:
      		return false;
      		break;
      }
     return false;

    }


//直选单式
//格式为 1,2,3
 protected function  zhixuanSingle(): bool {
    if(substr_count($this->ticket['lotnum'], ';') != 2){
     	return false;
    }

   $ballsArr = explode(';', $this->ticket['lotnum']);
   
   if(!$this->ballIsInset($ballsArr,$this->redBall)){
      return false;
   }
   
   return true;
 }

 //直选复试
 //格式为：0,1,2;0,1,2;0,1,2
 protected function  zhixuanComplex(): bool {
    $ballArr = explode(';', $this->ticket['lotnum']);
    $betZhu = 1;
    foreach ($ballArr as $key => $value) {
     $flagArr = $this->checkBalls($value,$this->normal['red'],$this->redBall);
     $betZhu *= count($flagArr);
     if(count($flagArr) == 0){
       break;
     }
    }

    if($betZhu == 0){
      return false;
    }
    $this->betZhu = $betZhu;
    unset($betZhu);
   return true;

 }

 //直选和值
 //格式为：05
 protected function  zhixuanSum(): bool {
   $ballsArr = $this->checkBalls($this->ticket['lotnum'],$this->normal['zhisum'],$this->zhisum);
   if(count($ballsArr) == 0){
   	  return false;
   }

   //计算注数
   foreach ($ballsArr as $key => $value) {
   	 $tempKey = intval($value);
   	 $this->ticketNum += $this->zxSum[$tempKey];
   }

   return true;
 }


 //组三单式
 //格式为：2,2,3
 protected function z3Single(): bool {
   return $this->z3Complex();
 }


 //组三复试
 //格式为：1,2,3
 protected function  z3Complex(): bool {
   $ballsArr = $this->checkBalls($this->ticket['lotnum'],$this->normal['zu3fu'],$this->redBall);
   $ballNum = count($ballsArr);
   if($ballNum == 0){
   	  return false;
   }

   //计算注数
   $this->ticketNum = $this->Combination($ballNum,2);

   return true;
 }


 //组六单式 必须为3个号码 并且三个号码都不相同
 protected function  z6Single(): bool {
    return $this->z6Complex();
 }


 ////组六复试 -- 号码都不相同
 protected function  z6Complex(): bool {
   $ballsArr = $this->checkBalls($this->ticket['lotnum'],$this->normal['zu6fu'],$this->redBall);
   $ballNum = count($ballsArr);
   if($ballNum == 0){
   	  return false;
   }
   //计算注数
   $this->ticketNum = $this->Combination($ballNum,3);
   
   return true;

 }

 //组三和值

 protected function z3Sums(){
   $ballsArr = $this->checkBalls($this->ticket['lotnum'],$this->normal['zusum'],$this->z3SumBall);
   $ballNum = count($ballsArr);
   if($ballNum == 0){
   	  return false;
   }

   $betnum = 0;

   foreach ($ballsArr as $k => $val) {
   	 	$betnum += $this->z3Sum[$val];
   }

   $this->ticketNum = $betnum;
   
   return true;
 }

 //组六和值
protected function z6Sums(){
   $ballsArr = $this->checkBalls($this->ticket['lotnum'],$this->normal['zu6sum'],$this->z6SumBall);
   $ballNum = count($ballsArr);
   if($ballNum == 0){
   	  return false;
   }

   $betnum = 0;

   foreach ($ballsArr as $k => $val) {
   	 	$betnum += $this->z6Sum[$val];
   }

   $this->ticketNum = $betnum;
   
   return true;

 }

 //组三组六和值
protected function  z6z3Sum(): bool {
   $ballsArr = $this->checkBalls($this->ticket['lotnum'],$this->normal['zusum'],$this->z3SumBall);
   $ballNum = count($ballsArr);
   if($ballNum == 0){
   	  return false;
   }
   
   $zuSanArr = [];
   $zuSanZhu = 0;
   $zuLiuArr = [];
   $zuLiuZhu = 0;
   foreach ($ballsArr as $k => $val) {
   	 if($this->z3Sum[$val] > 0){
   	 	$zuSanZhu += $this->z3Sum[$val];
   	 	array_push($zuSanArr, $val);  
   	 }

   	 if($this->z6Sum[$val] > 0){
   	 	$zuLiuZhu += $this->z6Sum[$val];
   	 	array_push($zuLiuArr, $val);  
   	 }

  }

  $this->ticketNum = $zuSanZhu + $zuLiuZhu;

  //组3和值
  $temp = [
    'playtype'=>1,
    'lottype'=>7,
    'lotnum'=>implode(',',$zuSanArr),
    'money'=>$this->ticket['multiple'] * $zuSanZhu*2,
    'multiple'=>$this->ticket['multiple'],
    'betnum'=>$zuSanZhu,
  ];

  array_push($this->ticketArr,$temp);

  //组6和值
  $temp['lottype'] = 8;
  $temp['lotnum'] = implode(',',$zuLiuArr);
  $temp['money'] = $this->ticket['multiple'] * $zuLiuZhu*2;
  $temp['multiple']=$this->ticket['multiple'];
  $temp['betnum']=$zuSanZhu;
  array_push($this->ticketArr,$temp);
  return true;
}

 //组6胆拖
 //2,3|6,7,8,9,10
 protected function z6DanTuo(): bool {
    $redball = $this->ticket['lotnum'];
    $rDanArr = []; //红球胆码
    $rTuoArr = []; //红球拖码 

    if(strpos($redball, '|') === false){
      return false;
    }

    list($rdan,$rtuo) = explode('|',$redball);
     
    $ballsDanArr = $this->checkBalls($rdan,$this->dantuo['zu6dan'],$this->redBall);
    $ballDanNum = count($ballsDanArr);
    if($ballDanNum == 0){
   	  return false;
    }
   
    $ballsTuoArr = $this->checkBalls($rtuo,$this->dantuo['zu6tuo'],$this->redBall);
    $ballTuoNum = count($ballsTuoArr);
    if($ballTuoNum == 0){
   	  return false;
    }

    if(($ballDanNum + $ballTuoNum) < 5){
      return false
    }


    $redBallAll = array_intersect($ballsDanArr,$ballsTuoArr);

    if(count($redBallAll) != 0){
         return false;
    }
    
    $this->ticketNum = $this->Combination($ballsTuoArr,(3-$ballDanNum));

    unset($redBallAll);
    unset($ballsDanArr);
    unset($ballsTuoArr);
    unset($rdan);
    unset($rtuo);

    

    return true;

 }




//组3胆拖
//2|6,7,8,9,10
 protected function z3DanTuo(): bool {
    $redball = $this->ticket['lotnum'];
    $rDanArr = []; //红球胆码
    $rTuoArr = []; //红球拖码 

    if(strpos($redball, '|') === false){
      return false;
    }

    list($rdan,$rtuo) = explode('|',$redball);
     
    $ballsDanArr = $this->checkBalls($rdan,$this->dantuo['zu3dan'],$this->redBall);
    if(count($ballsDanArr) == 0){
   	  return false;
    }
   
    $ballsTuoArr = $this->checkBalls($rtuo,$this->dantuo['zu3tuo'],$this->redBall);
    $ballTuoNum = count($ballsTuoArr);
    if($ballTuoNum == 0){
   	  return false;
    }

    $redBallAll = array_intersect($ballsDanArr,$ballsTuoArr);

    if(count($redBallAll) != 0){
         return false;
    }

    unset($redBallAll);
    unset($ballsDanArr);
    unset($ballsTuoArr);
    unset($rdan);
    unset($rtuo);

    $this->ticketNum = $ballTuoNum * 2;

    return true;
 }




}