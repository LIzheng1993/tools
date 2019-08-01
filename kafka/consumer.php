<?php
/**
 * 对 中台生产的  用户信息 进行消费
 * User: LiZheng  271648298@qq.com
 * Date: 2019/7/31
 */
// 设置将要消费消息的主题
$topic = 'alikafka-jl-yz-zt-updata-test';
$host = '172.168.50.233';
$group_id = 'CID_alikafka_jl_lz';

$conf = new \RdKafka\Conf();
// 当有新的消费进程加入或者退出消费组时，kafka 会自动重新分配分区给消费者进程，这里注册了一个回调函数，当分区被重新分配时触发
$conf->setRebalanceCb(function (RdKafka\KafkaConsumer $kafka, $err, array $partitions = null) {
    switch ($err) {
        case RD_KAFKA_RESP_ERR__ASSIGN_PARTITIONS:
//            echo "Assign: ";
//            var_dump($partitions);
            $kafka->assign($partitions);
            break;
        case RD_KAFKA_RESP_ERR__REVOKE_PARTITIONS:
//            echo "Revoke: ";
//            var_dump($partitions);
            $kafka->assign(NULL);
            break;
        default:
            throw new \Exception($err);
    }
});
// 配置groud.id 具有相同 group.id 的consumer 将会处理不同分区的消息，
// 所以同一个组内的消费者数量如果订阅了一个topic，
// 那么消费者进程的数量多于 多于这个topic 分区的数量是没有意义的。
$conf->set('group.id', $group_id);

// 添加 kafka集群服务器地址
$conf->set('metadata.broker.list', $host); //'localhost:9092,localhost:9093,localhost:9094,localhost:9095'

// 针对低延迟进行了优化的配置。这允许PHP进程/请求尽快发送消息并快速终止
$conf->set('socket.timeout.ms', 50);
//多进程和信号
if (function_exists('pcntl_sigprocmask')) {
    pcntl_sigprocmask(SIG_BLOCK, array(SIGIO));
    $conf->set('internal.termination.signal', SIGIO);
} else {
    $conf->set('queue.buffering.max.ms', 1);
}

$topicConf = new \RdKafka\TopicConf();
// 在interval.ms的时间内自动提交确认、建议不要启动, 1是启动，0是未启动
$topicConf->set('auto.commit.enable', 1);
$topicConf->set('auto.commit.interval.ms', 100);
//smallest：简单理解为从头开始消费，largest：简单理解为从最新的开始消费
$topicConf->set('auto.offset.reset', 'smallest');
// 设置offset的存储为broker
//$topicConf->set('offset.store.method', 'broker');
// 设置offset的存储为file
//$topicConf->set('offset.store.method', 'file');
// 设置offset的存储路径
$topicConf->set('offset.store.path', 'kafka_offset.log');
//$topicConf->set('offset.store.path', __DIR__);

$conf->setDefaultTopicConf($topicConf);

$consumer = new \RdKafka\KafkaConsumer($conf);

// 更新订阅集（自动分配partitions ）
$consumer->subscribe([$topic]);

//        指定topic分配partitions使用那个分区
//        $consumer->assign([
//            new \RdKafka\TopicPartition("zzy8", 0),
//            new \RdKafka\TopicPartition("zzy8", 1),
//            ]);

while (true) {
//            设置120s为超时
    $message = $consumer->consume(3 * 1000);
    if (!empty($message)) {
        switch ($message->err) {
            case RD_KAFKA_RESP_ERR_NO_ERROR:
//                var_dump('New message received ：', $message);
//              拆解对象为数组，并根据业务需求处理数据
                $payload = json_decode($message->payload,true);
                $key = $message->key;
//              根据kafka中不同key，调用对应方法传递处理数据*（如果有必要的话）
                //对该条message进行处理，比如用户数据同步， 记录日志。
//                var_dump("asasasasasasasasasasasas");
                break;
            case RD_KAFKA_RESP_ERR__PARTITION_EOF:
                echo "No more messages; will wait for more\n";
                break;
            case RD_KAFKA_RESP_ERR__TIMED_OUT:
                echo "Timed out\n";
                var_dump("##################");
                break;
            default:
                var_dump("nothing");
                throw new \Exception($message->errstr(), $message->err);
                break;
        }

    } else {
        var_dump('this is empty obj!!!');
    }
}