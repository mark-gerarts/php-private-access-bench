<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class ArrayCastTask implements TaskInterface
{

    public function run(MyClass $class): string
    {
        $array = (array) $class;
        $property = 'property';
        $propertyLength = strlen($property);
        foreach ($array as $key => $value) {
            if (substr($key, -$propertyLength) === $property) {
                return $value;
            }
        }

        return '';
    }

    public function getName(): string
    {
        return 'Array cast';
    }

}
