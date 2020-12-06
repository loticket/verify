<?php
declare (strict_types = 1);
/********************************
*action 格式验证的基类
**********************************/

namespace Loticket\Verify\Playlot;
use Loticket\LotteryApp;
use Loticket\Verify\Exception\PlayException;
use Loticket\Verify\Utils\Combination;

abstract class BasePlay
{   

	protected LotteryApp $app;

  protected array $ticket; //票内容

  protected array $config;

  protected array $ticketArr = [];
	
	public function __construct(LotteryApp $app,array $config){
       
        $this->app = $app;
        $this->config = $config;
    }

     /**
     * 票信息
     * @access public
     * @param  array $ticket 票内容数组  playtype 子玩法 lottype 玩法类型 lotnum 玩法内容 money 金额 multiple 倍数 betnum 总注数
     * @return void
     */
     public function setTicket(array $ticket): void {
        if(!array_key_exists('playtype', $ticket)){
              throw new PlayException("参数子玩法不能为空");
              return;
        }

        if(!array_key_exists('lottype', $ticket)){
              throw new PlayException("参数玩法类型不能为空");
              return;
        }

        if(!array_key_exists('lotnum', $ticket)){
              throw new PlayException("玩法内容不能为空");
              return;
        }

        if(!array_key_exists('money', $ticket)){
              throw new PlayException("金额不能为空");
              return;
        }

        if(!array_key_exists('multiple', $ticket)){
              throw new PlayException("倍数不能为空");
              return;
        }

        if(!array_key_exists('betnum', $ticket)){
              throw new PlayException("总注数不能为空");
              return;
        }
         
        $this->ticket = $ticket;
    }


  
    /*获取计算出来的注数
    *
    *parame void
    *return int  
    */
    public function getTicketNum(): int {

      return $this->ticketNum;
      
    }


     //验证格式是否正确
    public function verification(): bool{
    

      //验证子玩法
       if(!$this->checkPlaytype()){ 
         throw new PlayException("子玩法验证不正确",1001);
         return false;
       }

       //验证号码格式

       if (!$this->playCheck()) { 
          throw new PlayException("号码验证不正确",1001);
          return false;
       }

       //验证注数和钱数

       if ($this->ticketNum != $this->ticket['betnum']) {
          throw new PlayException("注数计算错误",1001);
          return false;
       }



       $money = $this->ticket['playtype'] == 2 ? ($this->ticketNum*3*$this->ticket['multiple']) : ($this->ticketNum*2*$this->ticket['multiple']);

       
       if($money != $this->ticket['money']){
           throw new PlayException("钱数计算错误",1001);
           return false;
       }

       if (($this->ticket['money'] / $this->ticket['multiple']) > 20000) {
           throw new PlayException("单票金额不能超过2万",1001);
           return false;
       }




       return true;
    }


    /*按照设定的最大的倍数拆票
    *parame void
    *return array  
    */
    public function getSpliteTicket(): array {
       $nowMultiple = intval($this->ticket['multiple']);

       if ($nowMultiple < $this->maxmultiple[0] && count($this->ticketArr) == 0) {
          return [$this->ticket];
       }else if($nowMultiple < $this->maxmultiple[0] && count($this->ticketArr) != 0){
           return $this->ticketArr;
       }

       $ticket = count($this->ticketArr) == 0 ? [$this->ticket] : $this->ticketArr;
       $times = $this->spliteMultiple($this->ticket['multiple'],$this->ticket['money']);
       $newTicket = [];

      foreach ($ticket as $key => $val) {
         $temp = $val;
         for($i = 0;$i<count($times);$i++){
            $temp['multiple'] = $times[$i];
            $temp['money'] = $times[$i] * intval($val['money']/$val['multiple']);
            array_push($newTicket,$temp);
          }

        }
       return $newTicket;
    } 

   /*按照最大钱数或者倍数拆倍数
    *parame int ticketMultiple 倍数
    *parame int ticketMoney    总金额
    *return array  
    */

   public function spliteMultiple(int $ticketMultiple,int $ticketMoney): array {
       $maxTicketmultiple = 99;
       $maxMoney = 20000;

       if ($ticketMultiple < $maxTicketmultiple && $ticketMoney < $maxMoney ) {
          return [1];
       }


       //如果这张票超过两万 就按照倍数拆票
       //按照倍数钞票
       $singleMoney  = intval($ticketMoney / $ticketMultiple); //单注票的价格

       if($singleMoney > ($maxMoney/2)) {
          return array_fill(0, $ticketMultiple, 1);
       }



       //根据钱数求出最大倍数
       $maxMultiple  = intval($maxMoney / $singleMoney);


       $singleMultiple = $maxMultiple;

       $spliteArray = [];

       if($maxMultiple > $maxTicketmultiple) {
           $singleMultiple = $maxTicketmultiple;
       }

       while ($ticketMultiple > 0) {
           $tempMul = 0;
          if($ticketMultiple > $singleMultiple) {
            $tempMul = $singleMultiple;
          } else {
            $tempMul = $ticketMultiple;
          }
           
          array_push($spliteArray, $tempMul);
          $ticketMultiple -= $singleMultiple;

       }

      return $spliteArray;

   }




    /*检查子玩法
    *parame int $lottype 子玩法
    *return bool 
    */
    public function checkPlaytype(): bool{      
     if(!in_array($this->ticket['lottype'], $this->lottype)){
         return false;
      }

      if(!in_array($this->ticket['playtype'], $this->playtype)){
         return false;
      }

      if($this->ticket['lottype'] == 1 && $this->ticket['betnum'] != 1){
      	 return false;
      }

      if($this->ticket['lottype'] != 1 && $this->ticket['betnum'] == 1){
         return false;
      }

      return true;
    }


    /*检查球的是否在定义的中间-求两个数组的并集
    *parame array $ball    需要验证的数字数组
    *parame array $ballcon 定义的中数字总和
    *return bool 
    */
    public function ballIsInset(array $ball,array $ballcon): bool {
      if(count(array_intersect($ball,$ballcon)) == count($ball)){
           return true;
       }
      return false;
    }


    /*检查投注的球的格式以及数量是否正确
    *parame array $ballArr   投注的数字
    *parame array $ballRange 投注数字范围
    *parame array $ballAll   总共的数字
    *return array 
    */
    public function checkBalls(string $ballArr,array $ballRange,array $ballAll): array {
        $ballArrs = explode(',', $ballArr);

        $ballNum = count($ballArrs);

        if ($ballNum == 1) {
            return $ballArrs;
        }

        if ($ballNum < $ballRange[0] || $ballNum > $ballRange[1]) {
           return [];
        }

        if(!$this->ballIsInset($ballArrs,$ballAll)) {
          return [];
        }
        

        

        sort($ballArrs);

        return $ballArrs;
    }


    /*根据阶层计算数量
    *parame int $n 总数量
    *parame int $m 需要取出来的数量
    *return int 
    */
    public function Combination(int $n,int $m): int {
    	if($m == 0 || $m == $n) {
    	   return 1;
    	}

        if($m > $n){
       	   return 0;
        }
        
        return $this->app->combination->combinat($n,$m);
    }

 
    public function __get(string $name): ?array {
      if(isset($this->config[$name])){
    	  return $this->config[$name];
       }

       return [];

    }


}

?>