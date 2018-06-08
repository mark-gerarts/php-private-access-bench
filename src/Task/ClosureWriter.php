<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class ClosureWriter implements TaskInterface
{

    public function run(MyClass $class)
    {
        $closure = \Closure::bind(function &(MyClass $class) {
            return $class->property;
        }, null, MyClass::class);

        $property = &$closure($class);
        $property = 'changed';

        return $class;
    }

    public function getName(): string
    {
        return 'Closures';
    }

}
