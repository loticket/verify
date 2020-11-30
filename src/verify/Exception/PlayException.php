<?php
namespace Loticket\Verify\Exception;

use Exception;

/**
* 异常处理类
*/
class PlayException extends Exception
{
	
    /**
     * PlayException constructor.
     * @access public
     * @param  string    $message
     * @param  int       $code
     */
    public function __construct(string $message, int $code = 10500)
    {
        $this->message = $message;
        $this->code    = $code;
    }

    /**
     * ExecptionInfo
     * @access public  
     * @param  string    $message
     * @param  array     $config
     * @param  string    $sql
     * @param  int       $code
     */


    public function  ExecptionInfo(): array{
      return ['message'=>$this->message,'code'=>$this->code];
    }
}


?>