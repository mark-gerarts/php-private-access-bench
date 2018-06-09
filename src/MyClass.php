<?php

namespace PrivateAccessBench;

class MyClass
{
    private $property = 'Some property';

    public function getProperty(): string
    {
        return $this->property;
    }

    public function setProperty(string $property): void
    {
        $this->property = $property;
    }
}
