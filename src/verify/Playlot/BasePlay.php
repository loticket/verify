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

	protected $app;

    protected $ticket; //票内容

    protected $config;
	
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


       return true;
    }


    /*按照设定的最大的倍数拆票
    *parame void
    *return array  
    */
    public function getSpliteTicket(): array {
       $nowMultiple = intval($this->ticket['multiple']);

       if ($nowMultiple < $this->maxmultiple[0]) {
          return $this->ticket;
       }

       $times = ceil($nowMultiple / $this->maxmultiple[0]);
       $newTicket = [];
       $temp = $this->ticket;
       for($i = 1;$i<=$times;$i++){
          if($i*$this->maxmultiple[0] > $nowMultiple){
            $temp['multiple'] =  $nowMultiple - ($i-1)*$this->maxmultiple[0];
          }else{
            $temp['multiple'] = $this->maxmultiple;
          }

          array_push($newTicket,$temp);

       }
       return $newTicket;
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
        if(!$this->ballIsInset($ballArrs,$ballAll)) {
          return [];
        }
        $ballNum = count($ballArrs);
        if ($ballNum < $ballRange[0] || $ballNum > $ballRange[1]) {
           return [];
        }
         
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