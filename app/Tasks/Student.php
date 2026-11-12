<?php

declare(strict_types=1);

namespace App\Tasks;

class Student extends Person
{
    public function __construct(
        string $name,
        int $age,
        protected string $group
    ) {
        parent::__construct($name, $age);
    }

    public function getRoleDetails(): string
    {
        return $this->getBasicDetails()
            .', роль: студент'
            .', група: '.$this->group;
    }
}
