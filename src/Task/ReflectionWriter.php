<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class ReflectionWriter implements TaskInterface
{

    public function run(MyClass $class)
    {
        $property = new \ReflectionProperty($class, 'property');
        $property->setAccessible(true);
        $property->setValue($class, 'changed');
    }

    public function getName(): string
    {
        return 'Reflection';
    }

}
