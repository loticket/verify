<?php
namespace Loticket;

use Psr\SimpleCache\CacheInterface;
use Psr\Log\LoggerInterface;
use Loticket\Verify\Playlot\PlayInterface;

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
       
        $this->log->log($type, $log);
       
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
     * @access public
     * @return LotteryApp
     */
    public static function create(): LotteryApp
    {
        return new static();
    }


    /**
     * 创建对象实例
     * @access protected
     * @param string|null $name  连接标识
     * @param bool        $force 强制重新连接
     * @return PlayInterface
     */
    public function instance(string $name = null, bool $force = false): PlayInterface
    {
        if (empty($name)) {
            $name = 'dlt';
        }

        if ($force || !isset($this->instance[$name])) {
            $this->instance[$name] = $this->createInstance($name);
        }

        return $this->instance[$name];
    }


    /**
     * 创建对象
     * @param $name
     * @return PlayInterface
     */
    protected function createInstance(string $name): PlayInterface
    {
        $config = $this->getConfig($name);

        $type = !empty($config['type']) ? $config['type'] : 'dlt';

        if (false !== strpos($type, '\\')) {
            $class = $type;
        } else {
            $class = '\\Loticket\\Verify\\Playlot\\Play' . ucfirst($type);
        }

        $instence = new $class($this);

        return $instence;
    }
     



}


?>