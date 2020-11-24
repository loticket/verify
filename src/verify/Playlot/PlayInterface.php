<?php
namespace Loticket\Verify\Playlot;

interface PlayInterface {
	function verification(): bool ;//验证格式是否正确
    function getTicketNum(): int  ;//获取计算出来的注数
    function getSpliteTicket(): array ;//获取拆票后的数组
}


?>