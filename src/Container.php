<?php
declare (strict_types = 1);

namespace Loticket;

use Psr\Container\ContainerInterface;
use Exception;

/**
 * 容器管理类 支持PSR-11 
 * ContainerInterface 是一个容器的接口 里面包含 get has 方法 是一种标准
 * ArrayAccess  数组访问接口  可以 $a = new a()  $a['a'] = 'abc' 则可以通过重写 offsetExists(是否存在) offsetGet(获取) offsetSet(设置)  offsetUnset(销毁)
 *
 */
class Container implements ContainerInterface
{

    /**
     * 容器中的对象实例
     * @var array
     */
    protected $instances = [];


    /**
     * 容器绑定标识
     * @var array
     */
    protected $bind = [];


   
    
    /**
     * 获取容器中的对象实例
     * @access public
     * @param string $abstract 类名或者标识
     * @return object
     */
    public function get($abstract)
    {
        if ($this->has($abstract)) {
            return $this->make($abstract);
        }

        throw new Exception('class not exists: ' . $abstract, $abstract);
    }


    /**
     * 判断容器中是否存在类及标识
     * @access public
     * @param string $name 类名或者标识
     * @return bool
     */
    public function has($name): bool
    {
        return $this->bound($name);
    }



    /**
     * 根据别名获取真实类名
     * @param  string $abstract
     * @return string
     */
    public function getAlias(string $abstract): string
    {
        if (isset($this->bind[$abstract])) {
            return $this->bind[$abstract];
        }

        return $abstract;
    }


    /**
     * 判断容器中是否存在类及标识
     * @access public
     * @param string $abstract 类名或者标识
     * @return bool
     */
    public function bound(string $abstract): bool
    {
        return isset($this->bind[$abstract]) || isset($this->instances[$abstract]);
    }


    /**
     * 创建类的实例 已经存在则直接获取
     * @access public
     * @param string $abstract    类名或者标识
     * @param array  $vars        变量
     * @param bool   $newInstance 是否每次创建新的实例
     * @return mixed
     */
    public function make(string $abstract, array $vars = [], bool $newInstance = false)
    {
        
       

        $abstract = $this->getAlias($abstract); //获取bind数组中对应的数组值，也就是根据别名获取带有命名空间的类全名，例如:Cache::class 


        if (isset($this->instances[$abstract]) && !$newInstance) { //如果获取的类已经初始化了，则直接返回实例对象，保证一个类只实例化一次
            return $this->instances[$abstract];
        }

        $object = new $abstract();

        if (!$newInstance) {
            $this->instances[$abstract] = $object; //把容器管理的对象，实例化以后存储到数组中，防止下次访问再次生成行的对象
        }

        return $object;
    }


     

    /**
     * 依赖对象注入获取
     * @param $name
     * @return Dependent objects
     */ 

    public function __get($name){
       return $this->get($name);
    }
}