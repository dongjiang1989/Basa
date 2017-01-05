<?php
declare(encoding='UTF-8');
require_once(dirname(__FILE__)."/com/util.php");
class Request extends Object {

    /**
    *   Send 自定义request处理相关方法
    * @author dongjiang.dongj
    */
    public function __construct() {
        parent::__construct();
        updated_data_TS();
        //TODO
    }

    /** 
    *   获取数组中对应key的value，没有时返回默认值
    * @param string $group kfc group
    * @param string $sock sock文件
    * @param string $request 请求数据
    * @return TypeErr异常出现；如果$arr并不是array类型
    * @author dongjiang.dongj@
    */
    static function SendRequestBykfc($group, $sock, $request) {
        $ret = null;
        try {
            $ka = kfc_joingroup($group, $sock);        //join in kfc group
            if($ka)
            {   
                $res = kfc_sendmsg($ka, $request, KFC_ASYNC, 20000);      // send the request
                if($res)
                {   
                    $ret = kfc_recvmsg($ka, KFC_ASYNC, 20000);
                }   
                else
                {   
                    logging::error("Send request failed! request:", $request);
                    $ret = false;
                }   
            }   
            else
            {   
                logging::error("Join in kfc group failed!", "group:", $group, "sock:", $sock);
                $ret = false;
            }   
            kfc_leavegroup($ka);
        } catch (exception $e) {
            logging::error("Has exception thown!!! msg:", $e->getMessage());
        }
        return $ret;
    }

    /** 析构方法 */
    public function __destruct(){
        parent::__destruct();
    }
}

?>
