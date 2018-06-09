<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class Setter implements TaskInterface
{

    public function run(MyClass $class)
    {
        $class->setProperty('changed');
    }

    public function getName(): string
    {
        return 'Setter';
    }

}
