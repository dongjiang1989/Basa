<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/ConnectionInterface.php");
require_once(dirname(__FILE__)."/../Daemon.php");
/**
* Tcp������ 
* @author dongjiang.dongj<dongjiang.dongj@alibaba-inc.com>
*/
class TcpConnection extends ConnectionInterface
{
    /**
    * �����ݿɶ�ʱ����socket��������ȡ�����ֽ�����
    */
    const READ_BUFFER_SIZE = 8192;
    
    /**
    * ����״̬ ������
    */
    const STATUS_CONNECTING = 1;
    
    /**
    * ����״̬ �Ѿ���������
    */
    const STATUS_ESTABLISH = 2;
    
    /**
    * ����״̬ ���ӹر��У���ʶ������close���������Ƿ��ͻ���������Ȼ������
    * �ȴ����ͻ����������ݷ�����ϣ�д�뵽socketд����������ִ�йر�
    */
    const STATUS_CLOSING = 4;
    
    /**
    * ����״̬ �Ѿ��ر�
    */
    const STATUS_CLOSED = 8;
    
    /**
    * ���Զ˷�������ʱ�����������$onMessage�ص�����ִ��
    * callback function
    */
    public $onMessage = null;
    
    /**
    * �����ӹر�ʱ�����������$onClose�ص�����ִ��
    * @var callback
    */
    public $onClose = null;
    
    /**
    * �����ִ����ǣ����������$onError�ص�����ִ��
    * callback function
    */
    public $onError = null;
    
    /**
    * �����ͻ�������ʱ�����������$onBufferFull�ص�����ִ��
    * callback function
    */
    public $onBufferFull = null;
    
    /**
    * �����ͻ����������ʱ�����������$onBufferDrain�ص�����ִ��
    * callback function
    */
    public $onBufferDrain = null;
    
    /**
    * ʹ�õ�Ӧ�ò�Э�飬��Э���������
    * ֵ������ HttpProtocol
    */
    public $protocol = '';
    
    /**
    * �����ĸ�worker
    * @var Worker
    */
    public $worker = null;
    
    /**
    * ���ӵ�id��һ����������
    */
    public $id = 0;
    
    /**
    * ���ͻ�������С�������ͻ�������ʱ���᳢�Դ���onBufferFull�ص�����������õĻ���
    * ���û����onBufferFull�ص������ڷ��ͻ�����������������͵����ݽ���������
    * ֱ�����ͻ������пյ�λ��
    * ע�� ��ֵ���Զ�̬����
    * ���� TcpConnection::$maxSendBufferSize=1024000;
    */
    public static $maxSendBufferSize = 1048576;
    
    /**
    * �ܽ��ܵ�������ݰ���Ϊ�˷�ֹ���⹥���������ݰ��Ĵ�С���ڴ�ֵʱִ�жϿ�
    * ע�� ��ֵ���Զ�̬����
    * ���� TcpConnection::$maxPackageSize=1024000;
    */
    public static $maxPackageSize = 10485760;
    
    /**
    * id ��¼��
    */
    protected static $_idRecorder = 1;
    
    /**
    * ʵ�ʵ�socket��Դ
    * resource
    */
    protected $_socket = null;
    /**
    * ���ͻ�����
    * string
    */
    protected $_sendBuffer = '';
    
    /**
    * ���ջ�����
    * string
    */
    protected $_recvBuffer = '';
    
    /**
    * ��ǰ���ڴ�������ݰ��İ�������ֵ��Э���intput�����ķ���ֵ��
    */
    protected $_currentPackageLength = 0;
    
    /**
    * ��ǰ������״̬
    */
    protected $_status = self::STATUS_ESTABLISH;
    
    /**
    * �Զ�ip
    * string
    */
    protected $_remoteIp = '';
    
    /**
    * �Զ˶˿�
    */
    protected $_remotePort = 0;
    
    /**
    * �Զ˵ĵ�ַ ip+port
    * ֵ������ 10.125.51.188:3224
    */
    protected $_remoteAddress = '';
    
    /**
    * �Ƿ���ֹͣ��������
    */
    protected $_isPaused = false;

    /**
    * Daemon event
    */    
    protected $_event = null;

    /**
    * ���캯��
    * @param resource $socket
    * @param EventInterface $event
    * @author dongjiang.dongj@
    */
    public function __construct($socket, $event)
    {
        $this->id = self::$_idRecorder++;
        $this->_socket = $socket;
        $this->_event = &$event;
        stream_set_blocking($this->_socket, 0);
        $event->add($this->_socket, EventInterface::EV_READ, array($this, 'baseRead'));
    }
    
    /**
    * �������ݸ��Զ�
    * @param string $send_buffer
    * @param bool $raw
    * @return void|boolean
    * @author dongjiang.dongj@
    */
    public function send($send_buffer, $raw = false)
    {
        // �����ǰ״̬�������У�������ݷ��뷢�ͻ�����
        if($this->_status === self::STATUS_CONNECTING)
        {
            $this->_sendBuffer .= $send_buffer;
            return null;
        }
        // �����ǰ�����ǹرգ��򷵻�false
        elseif($this->_status == self::STATUS_CLOSED || $this->_status == self::STATUS_CLOSING)
        {
            return false;
        }
        
        // ���û��������ԭʼ���ݷ��ͣ�����������Э������Э�����
        if(false === $raw && $this->protocol)
        {
            $parser = $this->protocol;
            $send_buffer = $parser::encode($send_buffer, $this);
        }
        // ������ͻ�����Ϊ�գ�����ֱ�ӷ���
        if($this->_sendBuffer === '')
        {
            // ֱ�ӷ���
            $len = @fwrite($this->_socket, $send_buffer);
            // �������ݶ��������
            if($len === strlen($send_buffer))
            {
                return true;
            }
            // ֻ�в������ݷ��ͳɹ�
            if($len > 0)
            {
                // δ���ͳɹ����ַ��뷢�ͻ�����
                $this->_sendBuffer = substr($send_buffer, $len);
            }
            else
            {
                // ������ӶϿ�
                if(feof($this->_socket))
                {
                    // statusͳ�Ʒ���ʧ�ܴ���
                    self::$statistics['send_fail']++;
                    // ���������ʧ�ܻص�����ִ��
                    if($this->onError)
                    {
                        try
                        {
                            call_user_func($this->onError, $this, DaemonType::WORKERMAN_SEND_FAIL, 'client closed');
                        }
                        catch(Exception $e)
                        {
                            echo $e;
                        }
                    }
                    // ��������
                    $this->destroy();
                    return false;
                }
                // ����δ�Ͽ�������ʧ�ܣ�����������ݷ��뷢�ͻ�����
                $this->_sendBuffer = $send_buffer;
            }
            // �����Զ˿�д�¼�
            Daemon::$globalEvent->add($this->_socket, EventInterface::EV_WRITE, array($this, 'baseWrite'));
            // ��鷢�ͻ������Ƿ�������������˳��Դ���onBufferFull�ص�
            $this->checkBufferIsFull();
            return null;
        }
        else
        {
            // �������Ѿ����Ϊ������ȻȻ�����ݷ��ͣ��������ݰ�
            if(self::$maxSendBufferSize <= strlen($this->_sendBuffer))
            {
                // Ϊstatus����ͳ�Ʒ���ʧ�ܴ���
                self::$statistics['send_fail']++;
                // ���������ʧ�ܻص�����ִ��
                if($this->onError)
                {
                    try
                    {
                        call_user_func($this->onError, $this, DaemonType::WORKERMAN_SEND_FAIL, 'send buffer full and drop package');
                    }
                    catch(Exception $e)
                    {
                        echo $e;
                    }
                }
                return false;
            }
            // �����ݷ���Ż�����
            $this->_sendBuffer .= $send_buffer;
            // ��鷢�ͻ������Ƿ�������������˳��Դ���onBufferFull�ص�
            $this->checkBufferIsFull();
        }
    }
    
    /**
    * ��öԶ�ip
    * @return string
    * @author dongjiang.dongj@
    */
    public function getRemoteIp()
    {
        if(!$this->_remoteIp)
        {
            $this->_remoteAddress = stream_socket_get_name($this->_socket, true);
            if($this->_remoteAddress)
            {
                list($this->_remoteIp, $this->_remotePort) = explode(':', $this->_remoteAddress, 2);
                $this->_remotePort = (int)$this->_remotePort;
            }
        }
        return $this->_remoteIp;
    }
    
    /**
    * ��öԶ˶˿�
    * @return int
    * @author dongjiang.dongj@
    */
    public function getRemotePort()
    {
        if(!$this->_remotePort)
        {
            $this->_remoteAddress = stream_socket_get_name($this->_socket, true);
            if($this->_remoteAddress)
            {
                list($this->_remoteIp, $this->_remotePort) = explode(':', $this->_remoteAddress, 2);
                $this->_remotePort = (int)$this->_remotePort;
            }
        }
        return $this->_remotePort;
    }
    
    /**
    * ��ͣ�������ݣ�һ�����ڿ����ϴ�����
    * @return void
    *@author dongjiang.dongj@
    */
    public function pauseRecv()
    {
        $this->_event->del($this->_socket, EventInterface::EV_READ);
        $this->_isPaused = true;
    }
    
    /**
    * �ָ��������ݣ�һ���û������ϴ�����
    * @return void
    * @author dongjiang.dongj@
    */
    public function resumeRecv()
    {
        if($this->_isPaused == true)
        {
            $this->_event->add($this->_socket, EventInterface::EV_READ, array($this, 'baseRead'), array($this->_socket));
            $this->_isPaused = false;
            $this->baseRead($this->_socket);
        }
    }
    /**
    * ��socket�ɶ�ʱ�Ļص�
    * @param resource $socket
    * @return void
    * @author dongjiang.dongj@
    */
    public function baseRead($socket)
    {
       //while($buffer = fread($socket, self::READ_BUFFER_SIZE))
       while(true)
       {
          $buffer = fread($socket, self::READ_BUFFER_SIZE);
          if($buffer === '' || $buffer === false) {
              break;
          }
          $this->_recvBuffer .= $buffer;
       }
       
       if($this->_recvBuffer)
       {
           if(!$this->onMessage)
           {
               $this->_recvBuffer = '';
               return ;
           }
           
           // ���������Э��
           if($this->protocol)
           {
               $parser = $this->protocol;
               while($this->_recvBuffer && !$this->_isPaused)
               {
                   // ��ǰ���ĳ�����֪
                   if($this->_currentPackageLength)
                   {
                       // ���ݲ���һ����
                       if($this->_currentPackageLength > strlen($this->_recvBuffer))
                       {
                           break;
                       }
                   }
                   else
                   {
                       // ��õ�ǰ����
                       $this->_currentPackageLength = $parser::input($this->_recvBuffer, $this);
                       // ���ݲ������޷���ð���
                       if($this->_currentPackageLength === 0)
                       {
                           break;
                       }
                       elseif($this->_currentPackageLength > 0 && $this->_currentPackageLength <= self::$maxPackageSize)
                       {
                           // ���ݲ���һ����
                           if($this->_currentPackageLength > strlen($this->_recvBuffer))
                           {
                               break;
                           }
                       }
                       // ������
                       else
                       {
                           $this->close('error package. package_length='.var_export($this->_currentPackageLength, true));
                       }
                   }
                   
                   // �����㹻һ������
                   self::$statistics['total_request']++;
                   // ��ǰ�����պõ���buffer�ĳ���
                   if(strlen($this->_recvBuffer) == $this->_currentPackageLength)
                   {
                       $one_request_buffer = $this->_recvBuffer;
                       $this->_recvBuffer = '';
                   }
                   else
                   {
                       // �ӻ������л�ȡһ�������İ�
                       $one_request_buffer = substr($this->_recvBuffer, 0, $this->_currentPackageLength);
                       // ����ǰ���ӽ��ܻ�������ȥ��
                       $this->_recvBuffer = substr($this->_recvBuffer, $this->_currentPackageLength);
                   }
                   // ���õ�ǰ����Ϊ0
                   $this->_currentPackageLength = 0;
                   // �������ݰ�
                   try
                   {
                       call_user_func($this->onMessage, $this, $parser::decode($one_request_buffer, $this));
                   }
                   catch(Exception $e)
                   {
                       self::$statistics['throw_exception']++;
                       //TODO
                       logging::error($e->getMessage());
                   }
               }
               if($this->_status !== self::STATUS_CLOSED && feof($socket))
               {
                   $this->destroy();
               }
               return;
           }
           // û������Э�飬��ֱ�Ӱѽ��յ����ݵ���һ��������
           self::$statistics['total_request']++;
           try 
           {
               call_user_func($this->onMessage, $this, $this->_recvBuffer);
           }
           catch(Exception $e)
           {
               self::$statistics['throw_exception']++;
               echo $e;
           }
           // ��ջ�����
           $this->_recvBuffer = '';
           // �ж������Ƿ��Ѿ��Ͽ�
           if($this->_status !== self::STATUS_CLOSED && feof($socket))
           {
               $this->destroy();
               return;
           }
       }
       // û�յ����ݣ��ж������Ƿ��Ѿ��Ͽ�
       else if(!is_resource($socket) || feof($socket))
       {
           $this->destroy();
           return;
       }
    }
    /**
    * socket��дʱ�Ļص�
    * @return void
    * @author dongjiang.dongj
    */
    public function baseWrite()
    {
        $len = @fwrite($this->_socket, $this->_sendBuffer);
        if($len === strlen($this->_sendBuffer))
        {
            $this->_event->del($this->_socket, EventInterface::EV_WRITE);
            $this->_sendBuffer = '';
            // ���ͻ����������ݱ�������ϣ����Դ���onBufferDrain�ص�
            if($this->onBufferDrain)
            {
                try 
                {
                    call_user_func($this->onBufferDrain, $this);
                }
                catch(Exception $e)
                {
                    //TODO
                    logging::error($e->getMessage());
                }
            }
            // �������״̬Ϊ�رգ�����������
            if($this->_status == self::STATUS_CLOSING)
            {
                $this->destroy();
            }
            return true;
        }
        if($len > 0)
        {
           $this->_sendBuffer = substr($this->_sendBuffer, $len);
        }
        else
        {
           if(feof($this->_socket))
           {
               self::$statistics['send_fail']++;
               $this->destroy();
           }
        }
    }
    
    /**
    * �ӻ����������ѵ�$length���ȵ�����
    * @param int $length
    * @return void
    * @author dongjiang.dongj@
    */
    public function consumeRecvBuffer($length)
    {
        $this->_recvBuffer = substr($this->_recvBuffer, $length);
    }
    /**
    * �ر�����
    * @param mixed $data
    * @return void
    * @author dongjiang.dongj@
    */
    public function close($data = null)
    {
        if($this->_status == self::STATUS_CLOSING || $this->_status == self::STATUS_CLOSED)
        {
            return false;
        }
        else
        {
            if($data !== null)
            {
                $this->send($data);
            }
            $this->_status = self::STATUS_CLOSING;
        }
        if($this->_sendBuffer === '')
        {
           $this->destroy();
        }
    }
    
    /**
    * ���socket����
    * @return resource
    * @author dongjiang.dongj@
    */
    public function getSocket()
    {
        return $this->_socket;
    }
    /**
    * ��鷢�ͻ������Ƿ�������������˳��Դ���onBufferFull�ص�
    * @return void
    * @author dongjiang.dongj@
    */
    protected function checkBufferIsFull()
    {
        if(self::$maxSendBufferSize <= strlen($this->_sendBuffer))
        {
            if($this->onBufferFull)
            {
                try
                {
                    call_user_func($this->onBufferFull, $this);
                }
                catch(Exception $e)
                {
                    // TODO
                    logging::error($e->getMessage());
                }
            }
        }
    }
    /**
    * ��������
    * @return void
    * @author dongjiang.dongj@
    */
    public function destroy()
    {
        // �����ظ�����
        if($this->_status == self::STATUS_CLOSED)
        {
            return false;
        }
        // ɾ���¼�����
        $this->_event->del($this->_socket, EventInterface::EV_READ);
        $this->_event->del($this->_socket, EventInterface::EV_WRITE);
        // �ر�socket
        @fclose($this->_socket);
        
        // ��������ɾ��
        if($this->worker)
        {
            unset($this->worker->connections[(int)$this->_socket]);
        }
        // ��Ǹ������Ѿ��ر�
       $this->_status = self::STATUS_CLOSED;
       // ����onClose�ص�
       if($this->onClose)
       {
           try
           {
               call_user_func($this->onClose, $this);
           }
           catch (Exception $e)
           {
               self::$statistics['throw_exception']++;
               logging::error($e->getMessage());
           }
       }
    }

    public function __destruct()
    {
        // ͳ������
        self::$statistics['connection_count']--;
    }

}
