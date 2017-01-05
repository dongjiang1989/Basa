<?php
declare(encoding='UTF-8');
/**
 * connection��Ľӿ� 
 * @author dongjiang.dongj<dongjiang.dongj@alibaba-inc.com>
 */
abstract class  ConnectionInterface
{
    /**
    * status�����ͳ������
    */
    public static $statistics = array(
        'connection_count'=>0,
        'total_request'   => 0, 
        'throw_exception' => 0,
        'send_fail'       => 0,
    );
    
    /**
    * ���յ�����ʱ�����������$onMessage�ص�����ִ��
    * ����� callback function
    */
    public $onMessage = null;
    
    /**
    * �����ӹر�ʱ�����������$onClose�ص�����ִ��
    * �� callback function
    */
    public $onClose = null;
    
    /**
    * �����ִ���ʱ�����������$onError�ص�����ִ��
    * callback function
    */
    public $onError = null;
    
    /**
    * �������ݸ��Զ�
    * @param string $send_buffer
    * @author dongjiang.dongj@
    * @return void|boolean
    */
    abstract public function send($send_buffer);
    
    /**
    * ���Զ��ip
    * @return string
    * @author dongjiang.dongj@
    */
    abstract public function getRemoteIp();
    
    /**
    * ���Զ�˶˿�
    * @return int
    * @author dongjiang.dongj@
    */
    abstract public function getRemotePort();
    /**
    * �ر����ӣ�Ϊ�˱��ֽӿ�һ�£�udp�����˴˷���������udpʱ���ô˷������κ�����
    * @void
    * @author dongjiang.dongj@
    */
    abstract public function close($data = null);
}
