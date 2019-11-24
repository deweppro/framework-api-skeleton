<?php declare(strict_types=1);

namespace App\Helpers;

use App\DI;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class RabbitMQ
 *
 * @package App\Helpers\RabbitMQ
 */
class RabbitMQ
{
    const QUEUE_TEST = 'test';

    const ASK            = 0;
    const REJECT         = 1;
    const REJECT_REQUEUE = 2;

    /** @var \PhpAmqpLib\Connection\AMQPStreamConnection */
    protected $connection;

    /** @var \PhpAmqpLib\Channel\AMQPChannel */
    protected $channel;

    /**
     * RabbitMQ constructor.
     *
     * @param \PhpAmqpLib\Connection\AMQPStreamConnection $connection
     */
    public function __construct(AMQPStreamConnection $connection)
    {
        $this->connection = $connection;
        $this->channel = $connection->channel();
    }

    /**
     * @throws \Exception
     */
    public function __destruct()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @param string $queue
     *
     * @return string
     */
    public static function exchange(string $queue): string
    {
        return sprintf('ex_%s', $queue);
    }

    /**
     * @return string
     */
    public static function uniqkey(): string
    {
        return uniqid((string)time().'.');
    }

    /**
     * @param string $queue
     * @param array  $message
     */
    public function publish(string $queue, array $message)
    {
        $this->channel->queue_declare(
            $queue,
            false,
            true,
            false,
            false
        );
        $this->channel->exchange_declare(
            self::exchange($queue),
            AMQPExchangeType::DIRECT,
            false,
            true,
            false
        );
        $this->channel->queue_bind($queue, self::exchange($queue));

        $message['_uniqkey'] = self::uniqkey();

        $messageBody = (string)json_encode($message);
        $message = new AMQPMessage(
            $messageBody,
            [
                'content_type'  => 'application/json',
                'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT,
            ]
        );
        $this->channel->basic_publish($message, self::exchange($queue));
    }

    /**
     * @param string   $queue
     * @param callable $callback
     *
     * @throws \ErrorException
     */
    public function consume(string $queue, callable $callback)
    {
        $this->channel->exchange_declare(
            self::exchange($queue),
            AMQPExchangeType::DIRECT,
            false,
            true,
            false
        );
        $this->channel->queue_bind($queue, self::exchange($queue));

        $this->channel->basic_consume(
            $queue,
            'tag',
            false,
            false,
            false,
            false,
            function (\PhpAmqpLib\Message\AMQPMessage $message) use ($callback, $queue) {

                /** @var \PhpAmqpLib\Channel\AMQPChannel $channel */
                $channel = $message->delivery_info['channel'];

                /** @var string $tag */
                $tag = $message->delivery_info['delivery_tag'];

                try {

                    switch ($callback(json_decode($message->body, true))) {
                        case RabbitMQ::ASK:
                            $channel->basic_ack($tag);
                            break;
                        case RabbitMQ::REJECT_REQUEUE:
                            $channel->basic_reject($tag, true);
                            break;
                        default:
                            $channel->basic_reject($tag, false);
                            break;
                    }

                } catch (\Throwable $exception) {
                    DI::logger()->error(
                        $exception->getMessage(),
                        [
                            'queue' => $queue,
                            'body'  => $message->body,

                        ]
                    );

                    $channel->basic_reject($tag, true);
                }

            }
        );

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }
}
