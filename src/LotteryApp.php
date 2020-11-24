<?php
namespace Lottery\Verify;

use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;

/********************************
*彩票验证
**********************************/

/**
*  
*/
class LotteryApp
{
	
	
    protected $instence;
    
    /**
     * 配置文件数组
     * @var array
     */
    protected $config = [];

    /**
     * 查询缓存对象
     * @var CacheInterface
     */
    protected $cache;

    /**
     * 查询日志对象
     * @var LoggerInterface
     */
    protected $log;


    /**
     * 初始化配置参数
     * @access public
     * @param array $config 连接配置
     * @return void
     */
    public function setConfig(array $config): void
    {
        $this->config = $config;
    }

    /**
     * 设置缓存对象
     * @access public
     * @param CacheInterface $cache 缓存对象
     * @return void
     */
    public function setCache(CacheInterface $cache): void
    {
        $this->cache = $cache;
    }

    /**
     * 设置日志对象
     * @access public
     * @param LoggerInterface $log 日志对象
     * @return void
     */
    public function setLog(LoggerInterface $log): void
    {
        $this->log = $log;
    }


    /**
     * 记录SQL日志
     * @access protected
     * @param string $log  SQL日志信息
     * @param string $type 日志类型
     * @return void
     */
    public function log(string $log, string $type = 'sql'): void
    {
       
        return $this->log->log($type, $log);
       
    }

    /**
     * 获取配置参数
     * @access public
     * @param string $name    配置参数
     * @param mixed  $default 默认值
     * @return mixed
     */
    public function getConfig(string $name = '', array $default = []): array
    {
        if ('' === $name) {
            return $this->config;
        }

        if(isset($this->config[$name]) && is_array($this->config[$name])){
          return $this->config[$name];
        }else if(isset($this->config[$name]) && is_array($this->config[$name])){
          return [$this->config[$name]];
        }

        return $default;
    }


    /**
     * 创建对象
     * @param $name
     * @return ConnectionInterface
     */
    protected function createInstance(string $name): ConnectionInterface
    {
        $config = $this->getConfig($name);

        $type = !empty($config['type']) ? $config['type'] : 'dlt';

        if (false !== strpos($type, '\\')) {
            $class = $type;
        } else {
            $class = '\\Lottery\\Verify\\Playlot\\' . ucfirst($type);
        }

        $instence = new $class($config);
        $instence->setLottery($this);

        if ($this->cache) {
            $instence->setCache($this->cache);
        }

        if ($this->log) {
            $instence->setLog($this->log);
        }

        return $instence;
    }
     



}


?>