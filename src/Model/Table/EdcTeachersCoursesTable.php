<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcTeachersCoursesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edcteachers_edccourses');
    }
}