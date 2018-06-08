<?php

namespace PrivateAccessBench;

interface TaskInterface
{
    public function run(MyClass $class);

    public function getName(): string;
}
