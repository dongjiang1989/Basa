<?php
declare(encoding='UTF-8');
interface EventInterface {
    /**
    * ���¼�
    * @author dongjiang.dongj
    */
    const EV_READ = 1;
    
    /**
    * д�¼�
    * @author dongjiang.dongj
    */
    const EV_WRITE = 2;
    
    /**
    * �ź��¼�
    * @author dongjiang.dongj
    */
    const EV_SIGNAL = 4;
    
    /**
    * �����Ķ�ʱ�¼�
    * @author dongjiang.dongj
    */
    const EV_TIMER = 8;
    
    /**
    * ��ʱһ��
    * @author dongjiang.dongj
    */
    const EV_TIMER_ONCE = 16;
    
    /**
    * ����¼��ص� 
    * @param resource $fd
    * @param int $flag
    * @param callable $func
    * @return bool
    * @author dongjiang.dongj
    */
    public function add($fd, $flag, $func, $args = null);
    
    /**
    * ɾ���¼��ص�
    * @param resource $fd
    * @param int $flag
    * @return bool
    * @author dongjiang.dongj
    */
    public function del($fd, $flag);
    
    /**
    * ������ж�ʱ��
    * @return void
    * @author dongjiang.dongj
    */
    public function clearAllTimer();
    
    /**
    * �¼�ѭ��
    * @return void
    * @author dongjiang.dongj
    */
    public function loop();
}
