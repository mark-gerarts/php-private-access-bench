<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class Getter implements TaskInterface
{

    public function run(MyClass $class)
    {
        return $class->getProperty();
    }

    public function getName(): string
    {
        return 'Getter';
    }

}
