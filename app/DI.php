<?php declare(strict_types=1);

namespace App;

use Dewep\Container;

/**
 * Class DI
 *
 * @package App
 */
class DI
{
    /**
     * @return \Monolog\Logger
     */
    public static function logger(): \Monolog\Logger
    {
        return Container::get('logger');
    }

    /**
     * @return \Dewep\Mysql
     */
    public static function db(): \Dewep\Mysql
    {
        return Container::get('db');
    }

    /**
     * @return \Twig\Environment
     */
    public static function twig(): \Twig\Environment
    {
        return Container::get('twig');
    }

    /**
     * @return \Predis\Client
     */
    public static function redis(): \Predis\Client
    {
        return Container::get('redis');
    }

    /**
     * @return \App\Helpers\RabbitMQ
     */
    public static function amq(): \App\Helpers\RabbitMQ
    {
        return Container::get('rmq');
    }
}
