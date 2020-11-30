# verify

体彩，福彩-格式验证，注数查询，拆票


大乐透格式验证：
<?php
use Loticket\LotteryApp;
use Loticket\Verify\Exception\PlayException;

$apps = new LotteryApp();

$lottery = LotteryApp::create()->instance('dlt',false);

try {
    
    $lottery->setTicket(["playtype"=>2,"lottype"=>2,"lotnum"=>"11,24,25,26,35-04,07,08","money"=>9,"multiple"=>1,"betnum"=>3]);
   
    $code = $lottery->verification(); //验证提交信息是否成功  为 true 则验证成功

    /////
    
    $ticket = $lottery->getSpliteTicket();  //获取拆票信息 按照99的倍数拆票

    /////
 
 } catch (PlayException $e){
    $ex = $e->ExecptionInfo();
} 
?>
