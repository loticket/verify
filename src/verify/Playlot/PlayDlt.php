<?php
namespace Loticket\Verify\Playlot;
use Loticket\LotteryApp;

/**
 * 大乐透验证类
 */
class PlayDlt implements PlayInterface {

    
    private $app;
    
  
    public function __construct(LotteryApp $app){
       
        $this->app = $app;
    }

    //验证格式是否正确
    public function verification(): bool{

     return false;
    }

    //获取计算出来的注数 
    public function getTicketNum(): int {
      return 0;
    }

    //获取拆票后的数组
    public function getSpliteTicket(): array {
       return [];
    } 

}


?>