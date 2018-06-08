<?php

namespace PrivateAccessBench;

class MyClass
{
    private $property = 'Some property';

    public function getProperty(): string
    {
        return $this->property;
    }
}
