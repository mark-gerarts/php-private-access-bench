<?php

namespace PrivateAccessBench;

interface TaskInterface
{
    public function run(MyClass $class): string;

    public function getName(): string;
}
