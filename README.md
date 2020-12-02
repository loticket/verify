# 福利彩票，体育彩票，数字彩，格式定义

> 可用于根据固定的格式验证用户投注信息是否正确，及其根据倍数拆票，用于大小型彩票系统，方便快捷，缩短开发周期

---
#####  双色球投注格式详解（ssq）
> 子玩法 1：普通投注 
> 投注方式 1:单式  2；复式  3；胆拖

双色球 | 子玩法 | 投注方式 | 投注代码 | 注数 | 金额
---|---|---|---|---|---
单式 | 1 | 1 | 02,05,16,17,19,21-16     |  1 | 2
复式 | 1 | 2 | 06,09,10,16,24,29,30-12,13  | 14 | 28
胆拖 | 1 | 3 | 01,18,25,31\|13,14,20,21-02 | 6 | 12

---
#####  七乐彩投注格式详解（qlc）
> 子玩法 1：普通投注 
> 投注方式 1:单式  2；复式  3；胆拖

七乐彩 | 子玩法 | 投注方式 | 投注代码 | 注数 | 金额
---|---|---|---|---|---
单式 | 1 | 1 | 06,13,20,26,27,28,30    |  1 | 2
复式 | 1 | 2 | 04,11,18,19,20,25,27,30  | 8 | 16
胆拖 | 1 | 3 | 09,14,24\|04,08,16,23,26 | 5 | 10
---
#####  大乐透投注格式详解(dlt)
>子玩法 1：普通投注   2:追加投注

大乐透 | 子玩法 | 投注方式 | 投注代码 | 注数 | 金额
---|---|---|---|---|---
单式 | 1 | 1 | 04,07,14,24,33-01,02     |  1 | 2
复式 | 1 | 2 | 11,24,25,26,35-04,07,08  |  3 | 6
胆拖 | 1 | 3 | 01,08,15,23,29,30-02\|01,08,09 |45| 90


---
#####  七星彩投注格式详解(qxc)
>子玩法 1：普通投注
>投注方式  1:单式   2:复式

七星彩 | 子玩法 | 投注方式 | 投注代码 | 注数 | 金额
---|---|---|---|---|---
单式 | 1 | 1 | 2;9;5;0;6;4-10     |  1 | 2
复式 | 1 | 2 | 4;3;3,5;3,7;4;6-4,6,14  |  12 | 24


##### 排列三投注格式详解

>子玩法 

排列三 | 子玩法 | 投注方式 | 投注代码 | 注数 | 金额
---|---|---|---|---|---
直选 | 1 | 1(单式) | 1;1;1     |  1 | 2
直选 | 1 | 2(复式) | 0,1,2;0,1,2;0,1,2  |  27 | 54
直选 | 1 | 3(和值) | 0,1,10,11,20,21 | 200 | 400
组选 | 2 | 4(组3复式) | 0,1,10,11,20,21 | 12 | 24
组选 | 2 | 5(组6复式) | 0,1,2,3,4,5 | 20 | 40
组选 | 2 | 6(组3和值) | 4,5 | 6 | 12
组选 | 2 | 7(组6和值) | 4,5 | 2 | 4
组选 | 2 | 8(组3组6和值) | 3,4,13,14 | 36 | 72
组选 | 2 | 9(组3定胆) | 3\|0,1,2 | 6 | 12
组选 | 2 | 10(组6定胆) | 6\|3,4,5 | 3 | 6


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
