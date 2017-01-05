<?php
declare(encoding='UTF-8');
/**
* ��ʱ��
*  �磺
*      $time = new Timer();
*      $this->init();
*      $this->add($time_interval, callback, array($arg1, $arg2..));
* @author dongjiang.dongj<dongjiang.dongj@alibaba-inc.com>
*/
class Timer 
{
    /**
    * ����ALARM�źŵ�����
    * [
    *   run_time => [[$func, $args, $persistent, time_interval],[$func, $args, $persistent, time_interval],..]],
    *   run_time => [[$func, $args, $persistent, time_interval],[$func, $args, $persistent, time_interval],..]],
    * ]
    */
    protected $_tasks = array();
    
    /**
    * event
    */
    protected $_event = null;
    
    
    /**
    * ��ʼ��
    * @return void
    * @author dongjiang.dongj@
    */
    public function init($event = null)
    {
        if($event)
        {
            $this->_event = $event;
        }
        else 
        {
            pcntl_signal(SIGALRM, array($this, 'signalHandle'), false);
        }
    }
    
    /**
    * �źŴ�������ֻ����ALARM�¼�
    * @return void
    * @author dongjiang.dongj@
    */
    public function signalHandle()
    {
        if(!$this->_event)
        {
            pcntl_alarm(1);
            $this->tick();
        }
    }
    
    
    /**
    *  ���һ����ʱ��
    * @param int $time_interval
    * @param callback $func
    * @param mix $args
    * @return void
    * @author dongjiang.dongj@
    */
    public function add($time_interval, $func, $args = array(), $persistent = true)
    {
        if($time_interval <= 0)
        {
            echo new Exception("bad time_interval");
            return false;
        }
        
        if($this->_event)
        {
            return $this->_event->add($time_interval, $persistent ? EventInterface::EV_TIMER : EventInterface::EV_TIMER_ONCE , $func, $args);
        }
        
        if(!is_callable($func))
        {
            echo new Exception("not callable");
            return false;
        }
        
        if(empty($this->_tasks))
        {
            pcntl_alarm(1);
        }
        
        $time_now = time();
        $run_time = $time_now + $time_interval;
        if(!isset($this->_tasks[$run_time]))
        {
            $this->_tasks[$run_time] = array();
        }
        $this->_tasks[$run_time][] = array($func, $args, $persistent, $time_interval);
        return true;
    }
    
    
    /**
    * ���Դ�����ʱ�ص�
    * @return void
    * @author dongjiang.dongj@
    */
    public function tick()
    {
        if(empty($this->_tasks))
        {
            pcntl_alarm(0);
            return;
        }
        
        $time_now = time();
        foreach ($this->_tasks as $run_time=>$task_data)
        {
            if($time_now >= $run_time)
            {
                foreach($task_data as $index=>$one_task)
                {
                    $task_func = $one_task[0];
                    $task_args = $one_task[1];
                    $persistent = $one_task[2];
                    $time_interval = $one_task[3];
                    try 
                    {
                        call_user_func_array($task_func, $task_args);
                    }
                    catch(\Exception $e)
                    {
                        echo $e;
                    }
                    if($persistent)
                    {
                        $this->add($time_interval, $task_func, $task_args);
                    }
                }
                unset($this->_tasks[$run_time]);
            }
        }
    }
    
    /**
    * ɾ����ʱ��
    * @param $timer_id
    * @return void
    * @author dongjiang.dongj@
    */
    public function del($timer_id)
    {
        if($this->_event)
        {
            return $this->_event->del($timer_id, EventInterface::EV_TIMER);
        }
    }
    
    /**
    * ɾ�����ж�ʱ
    * @return void
    * @author dongjiang.dongj@
    */
    public function delAll()
    {
        $this->_tasks = array();
        pcntl_alarm(0);
        if($this->_event)
        {
            $this->_event->clearAllTimer();
        }
    }
}
