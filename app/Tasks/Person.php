<?php

declare(strict_types=1);

namespace App\Tasks;

abstract class Person
{
    public function __construct(
        protected string $name,
        protected int $age
    ) {}

    protected function getBasicDetails(): string
    {
        return 'Ім’я: '.$this->name.', вік: '.$this->age;
    }

    abstract public function getRoleDetails(): string;
}
