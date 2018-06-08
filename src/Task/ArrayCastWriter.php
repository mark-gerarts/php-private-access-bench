<?php

namespace PrivateAccessBench\Task;

use PrivateAccessBench\MyClass;
use PrivateAccessBench\TaskInterface;

class ArrayCastWriter implements TaskInterface
{

    public function run(MyClass $class)
    {
        $array = (array) $class;
        $className = get_class($class);
        $array["\0{$className}\0property"] = 'changed';

        $classLength = strlen($className);
        $serializedArray = serialize($array);
        $serializedArray = substr($serializedArray, 1);

        $serializedClass = "O:{$classLength}:\"{$className}\"{$serializedArray}";

        return unserialize($serializedClass);
    }

    public function getName(): string
    {
        return 'Array cast';
    }

}
