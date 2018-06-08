<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class ReflectionTask implements TaskInterface
{
    public function run(MyClass $class): string
    {
        $reflectionProperty = new \ReflectionProperty($class, 'property');
        $reflectionProperty->setAccessible(true);
        return $reflectionProperty->getValue($class);
    }

    public function getName(): string
    {
        return 'Reflection';
    }
}
