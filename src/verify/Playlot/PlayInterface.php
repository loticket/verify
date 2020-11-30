<?php
namespace Loticket\Verify\Playlot;

interface PlayInterface {
	function verification(): bool ;//验证格式是否正确
    function getTicketNum(): int  ;//获取计算出来的注数
    function checkPlaytype(): bool ; //验证子玩法
    function playCheck(): bool ; //验证号码格式
    function getSpliteTicket(): array ;//获取拆票后的数组
}


?>