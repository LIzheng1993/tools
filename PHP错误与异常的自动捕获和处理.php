<?php
/**
 * 本方法直接捕捉错误并记录日志，如有兴趣，可以捕捉到错误后再次抛出异常，将错误转为异常后，由set_exception_handler统一进行处理
 * 引入本文件即可使用
 * User: LiZheng  271648298@qq.com
 * Date: 2019/3/21
 */
register_shutdown_function('high_level_error');   //使用了register_shutdown_function 后，当程序遇见一些致命错误时会自动调用函数 high_level_error
set_error_handler('low_level_error');        //使用了set_error_handler 后，当程序遇见Notice 和Warning错误时会自动调用函数 low_level_error
set_exception_handler('exception_log'); //使用了set_exception_handler后，当遇到所有的未捕获的异常时会自动调用函数 exception_log
/**
 * 该方法只能捕获系统产生的一些Warning、Notice级别的错误
 * @param $type
 * @param $message
 * @param $file
 * @param $line
 * User: LiZheng  271648298@qq.com
 * Date: 2019/3/21
 */
function low_level_error($type, $message, $file, $line)
{
    $time = date('Y-m-d H:i:s');
    //拼接要记录成日志的信息
    $str = "\n ".$time.' set_error_handler: ' . exception_self::get_type($type) . ':' . $message . ' in ' . $file . ' on ' . $line . ' line .';
    //记录日志    error_log是PHP自带函数，记录到指定位置，详情见官网
    error_log($str,3,ROOT_DIR."/runtime/logs/php_error.log");
    //对于notice和warning 级别的错误，只进行记录而不会终止程序
    //echo json_encode(array('code'=>“ERR_ERROR”, 'msg'=>$time, 'data'=>array()), true);exit;
}

/**
 * 捕捉一些致命错误
 * User: LiZheng  271648298@qq.com
 * Date: 2019/3/21
 */
function high_level_error()
{
    //error_get_last() 获取最近一条发生的错误,包含"type"、 "message"、"file" 和 "line"
    if ($error = error_get_last()) {
        $time = date('Y-m-d H:i:s');
        //拼接要记录成日志的信息
        $str = "\n ".$time.' register_shutdown_function: Type:' . exception_self::get_type($error['type']) . ' Msg: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'];
        //记录日志    error_log是PHP自带函数，记录到指定位置，还可以发送邮件以及其他操作
        error_log($str,3,ROOT_DIR."/runtime/logs/php_error.log");
        //直接输出接口的返回信息， 给前端一个错误码code, msg返回时间用于方便查找日志
        echo json_encode(array('code'=>“ERR_ERROR”, 'msg'=>$time, 'data'=>array()), true);exit;
    }
}

/**
 * 捕捉异常
 * @param $exception
 * User: LiZheng  271648298@qq.com
 * Date: 2019/3/21
 */
function exception_log($exception)
{
    $time = date('Y-m-d H:i:s');
    //拼接要记录成日志的信息
    $str = "\n ".$time." set_exception_handler: Exception: ". $exception->getMessage()." in ".$exception->getFile()." on line ". $exception->getLine();
    //记录日志
    error_log($str,3,ROOT_DIR."/runtime/logs/php_error.log");
    //输出信息
    echo json_encode(array('code'=>“ERR_EXCEPTION”, 'msg'=>$time, 'data'=>array()), true);exit;
}

/**
 * Class error_handler
 * Create on 2019/3/21 14:15
 * User: LiZheng  271648298@qq.com
 * Date: 2019/3/21
 */
class exception_self extends Exception
{
    /**
     * 构造函数
     * @param string $message
     * @param string $code
     */
    public function __construct($message=null, $code=null)
    {
        parent::__construct($message, $code);
//        self::log($this->__toString());
    }
    //PHP的错误级别
    public static $type =  array(
        '1' => 'E_ERROR ',
        '2' => 'E_WARNING  ',
        '4' => 'E_PARSE  ',
        '8' => 'E_NOTICE  ',
        '16' => 'E_CORE_ERROR  ',
        '32' => 'E_CORE_WARNING  ',
        '64' => 'E_COMPILE_ERROR  ',
        '128' => 'E_COMPILE_WARNING  ',
        '256' => 'E_USER_ERROR  ',
        '512' => 'E_USER_WARNING  ',
        '1024' => 'E_USER_NOTICE  ',
        '2048' => 'E_STRICT  ',
        '4096' => 'E_RECOVERABLE_ERROR  ',
        '8191' => 'E_ALL  ',
    );

    /**
     * 通过数字 置换 对应的英文
     * @param $key
     * @return mixed
     * User: LiZheng  271648298@qq.com
     * Date: 2019/3/21
     */
    public static function get_type($key)
    {
        if(isset(self::$type[$key]))
        {
            return self::$type[$key];
        }else
        {
            return $key;
        }
    }
}