<?php

declare(strict_types=1);

namespace App\Tasks;

class HeadOfDepartment extends Teacher
{
    public function __construct(
        string $name,
        int $age,
        string $group,
        string $subject,
        private string $department
    ) {
        parent::__construct($name, $age, $group, $subject);
    }

    public function getRoleDetails(): string
    {
        return parent::getRoleDetails()
            .', посада: завідувач кафедри'
            .', кафедра: '.$this->department;
    }
}
