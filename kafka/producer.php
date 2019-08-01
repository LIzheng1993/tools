<?php
/**
 * User: LiZheng  271648298@qq.com
 * Date: 2019/8/1
 */
$conf = new RdKafka\Conf();

$conf->setDrmSgCb(function ($kafka, $message){
    file_put_contents("d:/dr_cb.log", var_export($message, true).PHP_EOL, FILE_APPEND);
});
$conf->setErrorCb(function ($kafka, $err, $reason){
    file_put_contents("d:/err_cb.log",sprintf("Kafka error: %s (reason: %s)", rd_kafka_err2str($err), $reason).PHP_EOL, FILE_APPEND);
});
$rk = new RdKafka\Producer($conf);
$rk->setLogLevel(LOG_DEBUG);

$rk->addBrokers("127.0.0.1");

$cf = new RdKafka\TopicConf();
// -1必须等所有brokers同步完成的确认 1当前服务器确认 0不确认，这里如果是0回调里的offset无返回，如果是1和-1会返回offset
// 我们可以利用该机制做消息生产的确认，不过还不是100%，因为有可能会中途kafka服务器挂掉
$cf->set('request.required.acks', 0);
$topic = $rk->newTopic("test", $cf);

$option = 'qkl';
for ($i = 0; $i < 10; $i++) {
    //RD_KAFKA_PARTITION_UA自动选择分区
    //$option可选
    $topic->produce(RD_KAFKA_PARTITION_UA, 0, "Message . $i", $option);
}

$len = $rk->getOutQLen();
while ($len > 0) {
    $len = $rk->getOutQLen();
//            var_dump($len);
    $rk->poll(10);
}
var_dump("finish");exit;