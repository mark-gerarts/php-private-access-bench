<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class ArrayWalkWriter implements TaskInterface
{

    public function run(MyClass $class)
    {
        array_walk($class, function (&$value, $key) {
            $property = 'property';
            $propertyLength  = strlen($property);
            if (substr($key, -$propertyLength) === $property) {
                $value = 'changed';
            }
        });
    }

    public function getName(): string
    {
        return 'Array walk';
    }

}
