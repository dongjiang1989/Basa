<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/../connection/ConnectionInterface.php");
/**
* Protocol interface
* @author dongjiang.dongj@alibaba-inc.com
*/
interface ProtocolInterface
{
    /**
    * ���ڷְ������ڽ��յ�buffer�з��ص�ǰ����ĳ��ȣ��ֽڣ�
    * ���������$recv_buffer�еõ�������ĳ����򷵻س���
    * ���򷵻�0����ʾ��Ҫ��������ݲ��ܵõ���ǰ������ĳ���
    * �������false���߸�������������󲻷���Э�飬�����ӻ�Ͽ�
    * @param ConnectionInterface $connection
    * @param string $recv_buffer
    * @return int|false
    * @author dongjiang.dongj
    */
    public static function input($recv_buffer, ConnectionInterface $connection);
    
    /**
    * ����������
    * input����ֵ����0�������յ����㹻�����ݣ����Զ�����decode
    * Ȼ�󴥷�onMessage�ص�������decode���������ݴ��ݸ�onMessage�ص��ĵڶ�������
    * Ҳ����˵���յ������Ŀͻ�������ʱ�����Զ�����decode���룬����ҵ��������ֶ�����
    * @param ConnectionInterface $connection
    * @param string $recv_buffer
    * @return mixed
    * @author dongjiang.dongj
    */
    public static function decode($recv_buffer, ConnectionInterface $connection);
    
    /**
    * ����������
    * ����Ҫ��ͻ��˷������ݼ�����$connection->send($data);ʱ
    * ���Զ���$data��encode���һ�Σ���ɷ���Э������ݸ�ʽ��Ȼ���ٷ��͸��ͻ���
    * Ҳ����˵���͸��ͻ��˵����ݻ��Զ�encode���������ҵ��������ֶ�����
    * @param ConnectionInterface $connection
    * @param mixed $data
    * @return string
    * @author dongjiang.dongj
    */
    public static function encode($data, ConnectionInterface $connection);
}
