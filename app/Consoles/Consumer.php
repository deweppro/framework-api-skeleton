<?php declare(strict_types=1);

namespace App\Consoles;

use App\DI;
use App\Helpers\RabbitMQ;
use Dewep\Console\Input;
use Dewep\Console\Output;
use Dewep\Interfaces\ConsoleInterface;

/**
 * Class Consumer
 *
 * @package App\Console
 */
class Consumer implements ConsoleInterface
{
    /**
     * @return string
     */
    public function help(): string
    {
        return 'RabbitMQ Consumer';
    }

    /**
     * @param Input $input
     */
    public function setup(Input $input): void
    {
        $input->setOptions('queue', RabbitMQ::QUEUE_TEST);
    }

    /**
     * @param Input  $input
     * @param Output $output
     *
     * @throws \ErrorException
     */
    public function handler(Input $input, Output $output): void
    {
        $queue = (string)$input->get('queue');

        try{
            DI::amq()->consume(
                $queue,
                function ($message) use ($output) {
                    $output->info($message);

                    return RabbitMQ::ASK;
                }
            );
        }catch (\Throwable $e){
            $output->danger($e->getMessage());
        }

    }
}
