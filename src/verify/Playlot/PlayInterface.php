<?php
namespace Lottery\Verify\Playlot;
use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

interface PlayInterface {
    
    function setLog(LoggerInterface $log) : void //设置日志对象
    function setCache(CacheInterface $cache): void //设置缓存对象
	function verification(): bool //验证格式是否正确
    function getTicketNum(): int  //获取计算出来的注数
    function getSpliteTicket(): array //获取拆票后的数组

}


?>