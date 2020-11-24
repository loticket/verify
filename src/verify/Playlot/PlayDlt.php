<?php
namespace Verify\PlayDlt;
use Verify\LotteryApp;

class PlayDlt implements PlayInterface {

    
    private array $ticketInfo; //票面信息
    

  
    public function __construct(array $option){
       
       $this->ticketInfo = $option;
    }

     /**
     * 设置缓存对象
     * @access public
     * @param string $value 表达式
     * @return Raw
     */
    public function setLog() : void {

    }


    //验证格式是否正确
    public function verification(): bool{


    }

    //获取计算出来的注数 
    public function getTicketNum(): int {


    }

    //获取拆票后的数组
    public function getSpliteTicket(): array {

    } 

}


?>