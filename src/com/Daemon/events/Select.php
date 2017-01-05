<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/EventInterface.php");
class Select implements EventInterface
{
    /**
    * ���е��¼�
    */
    public $_allEvents = array();
    
    /**
    * �����ź��¼�
    */
    public $_signalEvents = array();
    
    /**
    * ������Щ�������Ķ��¼�
    */
    protected $_readFds = array();
    
    /**
    * ������Щ��������д�¼�
    */
    protected $_writeFds = array();
    
    /**
    * ���������������
    * {['data':timer_id, 'priority':run_timestamp], ..}
    * @var SplPriorityQueue
    */
    protected $_scheduler = null;
    
    /**
    * ��ʱ����
    * [[func, args, flag, timer_interval], ..]
    */
    protected $_task = array();
    
    /**
    * ��ʱ��id
    */
    protected $_timerId = 1;
    
    /**
    * select��ʱʱ�䣬��λ��΢��
    */
    protected $_selectTimeout = 100000000;
    
    /**
    * ���캯��
    * @return void
    * @author dongjiang.dongj
    */
    public function __construct()
    {
        // PHP >= 5.1.0
        // ����һ���ܵ�������������������������У��������ѯ
        $this->channel = stream_socket_pair(STREAM_PF_UNIX, STREAM_SOCK_STREAM, STREAM_IPPROTO_IP);
        if($this->channel)
        {
            stream_set_blocking($this->channel[0], 0);
            $this->_readFds[0] = $this->channel[0];
        }
        // ��ʼ�����ȶ���(����)
        $this->_scheduler = new \SplPriorityQueue();
        $this->_scheduler->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
    }    

    /**
    * ����¼���������, ʵ��EventInterface�е��鷽��
    * @param resource $fd
    * @param int $flag
    * @param callable $func
    * @return bool
    * @author dongjiang.dongj
    */
    public function add($fd, $flag, $func, $args = null)
    {
        switch ($flag)
        {
            case self::EV_READ:
                $fd_key = (int)$fd;
                $this->_allEvents[$fd_key][$flag] = array($func, $fd);
                $this->_readFds[$fd_key] = $fd;
                break;
            case self::EV_WRITE:
                $fd_key = (int)$fd;
                $this->_allEvents[$fd_key][$flag] = array($func, $fd);
                $this->_writeFds[$fd_key] = $fd;
                break;
            case self::EV_SIGNAL:
                $fd_key = (int)$fd;
                $this->_signalEvents[$fd_key][$flag] = array($func, $fd);
                pcntl_signal($fd, array($this, 'signalHandler'));
                break;
            case self::EV_TIMER:
            case self::EV_TIMER_ONCE:
                // $fd Ϊ ��ʱ��ʱ��������λΪ�룬֧��С�����ܾ�ȷ��0.001��
                $run_time = microtime(true)+$fd;
                $this->_scheduler->insert($this->_timerId, -$run_time);
                $this->_task[$this->_timerId] = array($func, $args, $flag, $fd);
                $this->tick();
                return $this->_timerId++;
        }
        
        return true;
    }
    
    /**
    * �źŴ����� signalHandler
    *   �����Ѿ�ע�ᵽ�ź��¼��е�callback����
    * @param int $signal
    * @author dongjiang.dongj
    */
    public function signalHandler($signal)
    {
        call_user_func_array($this->_signalEvents[$signal][self::EV_SIGNAL][0], array($signal));
    }
    
    /**
    *  ɾ��ĳ����������ĳ���¼��ļ���
    * @param resource $fd
    * @param int $flag
    * @return bool
    * @author dongjiang.dongj
    */
    public function del($fd ,$flag)
    {
        $fd_key = (int)$fd;
        switch ($flag)
        {
            case self::EV_READ:
                unset($this->_allEvents[$fd_key][$flag], $this->_readFds[$fd_key]);
                if(empty($this->_allEvents[$fd_key]))
                {
                    unset($this->_allEvents[$fd_key]);
                }
                return true;
            case self::EV_WRITE:
                unset($this->_allEvents[$fd_key][$flag], $this->_writeFds[$fd_key]);
                if(empty($this->_allEvents[$fd_key]))
                {
                    unset($this->_allEvents[$fd_key]);
                }
                return true;
            case self::EV_SIGNAL:
                unset($this->_signalEvents[$fd_key]);
                pcntl_signal($fd, SIG_IGN);
                break;
            case self::EV_TIMER:
            case self::EV_TIMER_ONCE;
                // $fd_keyΪҪɾ���Ķ�ʱ��id����timerId
                unset($this->_task[$fd_key]);
                return true;
        }
        return false;;
    }
    
    /**
    * ����Ƿ��п�ִ�еĶ�ʱ�����еĻ�ִ��
    * @author dongjiang.dongj
    * @return void
    */
    protected function tick()
    {
        while(!$this->_scheduler->isEmpty())
        {
            $scheduler_data = $this->_scheduler->top();
            $timer_id = $scheduler_data['data'];
            $next_run_time = -$scheduler_data['priority'];
            $time_now = microtime(true);
            if($time_now >= $next_run_time)
            {
                $this->_scheduler->extract();
                
                // ������񲻴��ڣ����Ƕ�Ӧ�Ķ�ʱ���Ѿ�ɾ��
                if(!isset($this->_task[$timer_id]))
                {
                    continue;
                }
                
                // ��������[func, args, flag, timer_interval]
                $task_data = $this->_task[$timer_id];
                // ����ǳ����Ķ�ʱ�����ٰ�����ӵ���ʱ����
                if($task_data[2] == self::EV_TIMER)
                {
                    $next_run_time = $time_now+$task_data[3];
                    $this->_scheduler->insert($timer_id, -$next_run_time);
                }
                // ����ִ������
                try
                {
                    call_user_func_array($task_data[0], $task_data[1]);
                }
                catch(Exception $e)
                {
                    echo $e;
                }
                continue;
            }
            else
            {
                // �趨��ʱʱ��
                $this->_selectTimeout = ($next_run_time - $time_now)*1000000;
                return;
            }
        }
        $this->_selectTimeout = 100000000;
    }
    
    /**
    * ɾ�����ж�ʱ��
    * @author dongjiang.dongj@
    * @return void
    */
    public function clearAllTimer()
    {
        $this->_scheduler = new \SplPriorityQueue();
        $this->_scheduler->setExtractFlags(\SplPriorityQueue::EXTR_BOTH);
        $this->_task = array();
    }
    
    /**
    * ��ѭ��, ʵ����ӿ�
    * @author dongjiang.dongj@
    */
    public function loop()
    {
        $e = null;
        while (True)
        {
            // ������źţ�����ִ���źŴ�����
            pcntl_signal_dispatch();
            
            $read = $this->_readFds;
            $write = $this->_writeFds;
            // �ȴ��ɶ����߿�д�¼�
            @stream_select($read, $write, $e, 0, $this->_selectTimeout);
            
            // ��Щ�������ɶ���ִ�ж�Ӧ�������Ķ��ص�����
            if($read)
            {
                foreach($read as $fd)
                {
                    $fd_key = (int) $fd;
                    if(isset($this->_allEvents[$fd_key][self::EV_READ]))
                    {
                        call_user_func_array($this->_allEvents[$fd_key][self::EV_READ][0], array($this->_allEvents[$fd_key][self::EV_READ][1]));
                    }
                }
            }
            
            // ��Щ��������д��ִ�ж�Ӧ��������д�ص�����
            if($write)
            {
                foreach($write as $fd)
                {
                    $fd_key = (int) $fd;
                    if(isset($this->_allEvents[$fd_key][self::EV_WRITE]))
                    {
                        call_user_func_array($this->_allEvents[$fd_key][self::EV_WRITE][0], array($this->_allEvents[$fd_key][self::EV_WRITE][1]));
                    }
                }
            }
            
            // ����ִ�ж�ʱ����
            if(!$this->_scheduler->isEmpty())
            {
                $this->tick();
            }
        }
    }
}
