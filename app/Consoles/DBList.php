<?php declare(strict_types=1);

namespace App\Consoles;

use App\DI;
use Dewep\Console\Input;
use Dewep\Console\Output;
use Dewep\Interfaces\ConsoleInterface;

/**
 * Class DBList
 *
 * @package App\Consoles
 */
class DBList implements ConsoleInterface
{
    /**
     * @return string
     */
    public function help(): string
    {
        return 'db list';
    }

    /**
     * @param Input $input
     */
    public function setup(Input $input): void
    {
        $input->setOptions('name', null);
    }

    /**
     * @param Input  $input
     * @param Output $output
     *
     * @throws \Exception
     */
    public function handler(Input $input, Output $output): void
    {
        $name = $input->get('name');

        if (empty($name)) {
            DI::db()->select('SHOW TABLES;')
                ->getChunk(
                    function ($data) use ($output) {
                        foreach ($data as $name) {
                            DBList::show($output, $name);
                        }
                    }
                );
        } else {
            DBList::show($output, $name);
        }

    }

    protected static function show(Output $output, string $name): void
    {
        $output->danger('Table: '.$name);
        $output->success(
            sprintf(
                "|%' 15s|%' 15s|%' 15s|%' 15s|%' 15s|%' 15s|",
                'Field',
                'Type',
                'Null',
                'Key',
                'Default',
                'Extra'
            )
        );
        $output->success(sprintf("|%'-95s|", ''));

        DI::db()->select(sprintf('DESCRIBE %s;', $name), [])
            ->getChunk(
                function ($data) use ($output) {
                    $output->success(
                        sprintf(
                            "|%' 15s|%' 15s|%' 15s|%' 15s|%' 15s|%' 15s|",
                            $data['Field'] ?? '',
                            $data['Type'] ?? '',
                            $data['Null'] ?? '',
                            $data['Key'] ?? '',
                            $data['Default'] ?? '',
                            $data['Extra'] ?? ''
                        )
                    );
                }
            );
        $output->success(sprintf("|%'-95s|\n\n", ''));
    }
}
