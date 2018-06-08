<?php

namespace PrivateAccessBench\Console\Command;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\Task\ArrayCastReader;
use PrivateAccessBench\Task\ArrayCastWriter;
use PrivateAccessBench\Task\ClosureReader;
use PrivateAccessBench\Task\ClosureWriter;
use PrivateAccessBench\Task\ReflectionReader;
use PrivateAccessBench\Task\ReflectionWriter;
use PrivateAccessBench\TaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BenchmarkCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('benchmark')
            ->addArgument(
                'iterations',
                InputArgument::OPTIONAL,
                'Number of iterations',
                10000
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $iterations = (int) $input->getArgument('iterations');
        $output->writeln('PHP version: PHP ' . phpversion());
        $output->writeln('Host: ' . php_uname());
        $output->writeln('Iterations: ' . $iterations);
        $output->writeln('');

        $readers = [
            new ReflectionReader(),
            new ClosureReader(),
            new ArrayCastReader(),
        ];
        $writers = [
            new ReflectionWriter(),
            new ClosureWriter(),
            new ArrayCastWriter()
        ];

        $readers = $this->validateReaders($readers);
        $readerResults = $this->runTasks($readers, $iterations);
        $writers = $this->validateWriters($writers);
        $writerResults = $this->runTasks($writers, $iterations);

        $sortByTime = function (array $a, array $b) {
            return $a['time'] <=> $b['time'];
        };

        // Order by duration.
        uasort($readerResults, $sortByTime);
        uasort($writerResults, $sortByTime);

        $table = new Table($output);
        $table->setHeaders(array('Method', 'Time', 'Memory peak'));
        // Readers.
        $table->addRow([new TableCell('Readers', ['colspan' => 3])]);
        $table->addRow(new TableSeparator());
        $table->addRows($readerResults);
        // Writers.
        $table->addRow(new TableSeparator());
        $table->addRow([new TableCell('Writers', ['colspan' => 3])]);
        $table->addRow(new TableSeparator(['colspan' => 3]));
        $table->addRows($writerResults);

        $table->render();
    }

    /**
     * Filter out reader tasks that don't work correctly.
     *
     * @param TaskInterface[] $tasks
     * @return TaskInterface[]
     */
    protected function validateReaders(array $tasks): array
    {
        return array_filter($tasks, function (TaskInterface $task): bool {
            $class = new MyClass();
            return $task->run($class) === 'Some property';
        });
    }

    /**
     * @param TaskInterface[] $tasks
     * @return TaskInterface[]
     */
    protected function validateWriters(array $tasks): array
    {
        return array_filter($tasks, function (TaskInterface $task): bool {
            $class = new MyClass();
            $task->run($class);
            return $class->getProperty() === 'changed'
                // This is a quick workaround for the ArrayCastWriter, which
                // doesn't touch the original object. 
                || $task->run($class)->getProperty() === 'changed';
        });
    }

    /**
     * @param TaskInterface[] $tasks
     * @param int $iterations
     * @return array
     */
    protected function runTasks(array $tasks, int $iterations): array
    {
        return array_map(function (TaskInterface $task) use ($iterations): array {
            $bench = new \Ubench();

            $bench->start();
            for ($i = 0; $i < $iterations; $i++) {
                $class = new MyClass();
                $task->run($class);
            }
            $bench->end();

            return [
                'name' => $task->getName(),
                'time' => $bench->getTime(),
                'memory' => $bench->getMemoryPeak()
            ];
        }, $tasks);
    }
}
