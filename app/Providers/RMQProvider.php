<?php declare(strict_types=1);

namespace App\Providers;

use App\Helpers\RabbitMQ;
use Dewep\Interfaces\ProviderInterface;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RMQProvider
 *
 * @package App\Providers
 */
class RMQProvider implements ProviderInterface
{
    /**
     * @param array $config
     *
     * @return mixed|\PhpAmqpLib\Connection\AMQPStreamConnection
     */
    public function handler(array $config)
    {
        $amqp = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['user'],
            $config['password'],
            $config['vhost']
        );

        return new RabbitMQ($amqp);
    }
}
