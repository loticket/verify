# verify

体彩，福彩-格式验证，注数查询，拆票

---
###### 双色球投注格式详解
> 子玩法 1：普通投注 
> 投注方式 1:单式  2；复式  3；胆拖

大乐透 | 子玩法 | 投注方式 | 投注代码 | 注数 | 金额
---|---|---|---|---|---
单式 | 1 | 1 | 02,05,16,17,19,21-16     |  1 | 2
复式 | 1 | 2 | 06,09,10,16,24,29,30-12,13  | 14 | 28
胆拖 | 1 | 3 | 01,18,25,31\|13,14,20,21-02 | 6 | 12
---
###### 大乐透投注格式详解
>子玩法 1：普通投注   2:追加投注

大乐透 | 子玩法 | 投注方式 | 投注代码 | 注数 | 金额
---|---|---|---|---|---
单式 | 1 | 1 | 04,07,14,24,33-01,02     |  1 | 2
复式 | 1 | 2 | 11,24,25,26,35-04,07,08  |  3 | 6
胆拖 | 1 | 3 | 01,08,15,23,29,30-02\|01,08,09 |45| 90



> 调用代码实例

```
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
```
------
