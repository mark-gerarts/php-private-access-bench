<?php

namespace PrivateAccessBench\Console\Command;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\Task\ReflectionTask;
use PrivateAccessBench\TaskInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
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

        $tasks = [
            new ReflectionTask()
        ];

        $this->validateTasks($tasks);
        $results = $this->runTasks($tasks, $iterations);

        // Order by duration
        uasort($results, function (array $a, array $b) {
            return $a['time'] <=> $b['time'];
        });



        $table = new Table($output);
        $table->setHeaders(array('Method', 'Time (ms)', 'Memory peak (MB)'));
        $table->addRows($results);
        $table->render();
    }

    /**
     * Filter out tasks that don't work correctly.
     *
     * @param array $tasks
     * @return array
     */
    protected function validateTasks(array $tasks): array
    {
        return array_filter($tasks, function (TaskInterface $task): bool {
            $class = new MyClass();
            return $task->run($class) === 'Some property';
        });
    }

    /**
     * @param array $tasks
     * @param int $iterations
     * @return array
     */
    protected function runTasks(array $tasks, int $iterations): array
    {
        return array_map(function (TaskInterface $task) use ($iterations): array {
            $bench = new \Ubench();
            $class = new MyClass();

            $bench->start();
            for ($i = 0; $i < $iterations; $i++) {
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
