<?php

namespace Zym\Bundle\ResqueBundle\Failure;

class Redis implements \Resque_Failure_Interface
{
    /**
     * Initialize a failed job class and save it (where appropriate).
     *
     * @param object $payload Object containing details of the failed job.
     * @param object $exception Instance of the exception that was thrown by the failed job.
     * @param object $worker Instance of Resque_Worker that received the job.
     * @param string $queue The name of the queue the job was fetched from.
     */
    public function __construct($payload, $exception, $worker, $queue)
    {
        $data = new \stdClass;
        $data->failed_at = strftime('%a %b %d %H:%M:%S %Z %Y');
        $data->payload = $payload;
        $data->exception = get_class($exception);
        $data->error = $exception->getMessage();
        $data->backtrace = explode("\n", $exception->getTraceAsString());
        $data->worker = (string)$worker;
        $data->queue = $queue;
        $data = json_encode($data);

        \Resque::redis()->rpush('failed', $data);
    }

    public static function count()
    {
        return \Resque::redis()->llen('failed');
    }

    public static function all($offset = 0, $limit = 100)
    {
        return \array_map(function($data) {
            return json_decode($data);
        }, \Resque::redis()->lrange('failed', $offset, $limit));
    }

    public static function requeue($id)
    {
//        item = all(id)
//        item['retried_at'] = Time.now.rfc2822
//        Resque.redis.lset(:failed, id, Resque.encode(item))
//        Job.create(item['queue'], item['payload']['class'], *item['payload']['args'])
        $item = current(self::all($id, $id));
        $item->retried_at = strftime('%a %b %d %H:%M:%S %Z %Y');
        $data = json_encode($item);

        \Resque::redis()->lset('failed', $id, $data);
        \Resque::enqueue($item->queue, $item->payload->class, (array)$item->payload->args[0]);
    }
}