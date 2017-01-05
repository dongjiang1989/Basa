<?php
declare(encoding='UTF-8');
interface EventInterface {
    /**
    * 读事件
    * @author dongjiang.dongj
    */
    const EV_READ = 1;
    
    /**
    * 写事件
    * @author dongjiang.dongj
    */
    const EV_WRITE = 2;
    
    /**
    * 信号事件
    * @author dongjiang.dongj
    */
    const EV_SIGNAL = 4;
    
    /**
    * 连续的定时事件
    * @author dongjiang.dongj
    */
    const EV_TIMER = 8;
    
    /**
    * 定时一次
    * @author dongjiang.dongj
    */
    const EV_TIMER_ONCE = 16;
    
    /**
    * 添加事件回调 
    * @param resource $fd
    * @param int $flag
    * @param callable $func
    * @return bool
    * @author dongjiang.dongj
    */
    public function add($fd, $flag, $func, $args = null);
    
    /**
    * 删除事件回调
    * @param resource $fd
    * @param int $flag
    * @return bool
    * @author dongjiang.dongj
    */
    public function del($fd, $flag);
    
    /**
    * 清除所有定时器
    * @return void
    * @author dongjiang.dongj
    */
    public function clearAllTimer();
    
    /**
    * 事件循环
    * @return void
    * @author dongjiang.dongj
    */
    public function loop();
}
