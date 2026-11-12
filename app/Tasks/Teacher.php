<?php

declare(strict_types=1);

namespace App\Tasks;

class Teacher extends Student
{
    public function __construct(
        string $name,
        int $age,
        string $group,
        protected string $subject
    ) {
        parent::__construct($name, $age, $group);
    }

    public function getRoleDetails(): string
    {
        return parent::getRoleDetails()
            .', роль: викладач'
            .', предмет: '.$this->subject;
    }
}
