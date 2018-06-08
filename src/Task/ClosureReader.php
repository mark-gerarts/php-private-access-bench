<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class ClosureReader implements TaskInterface
{

    public function run(MyClass $class): string
    {
        $closure = \Closure::bind(function (MyClass $class) {
            return $class->property;
        }, null, MyClass::class);

        return $closure($class);
    }

    public function getName(): string
    {
        return 'Closures';
    }

}
