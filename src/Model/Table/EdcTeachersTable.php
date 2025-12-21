<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcTeachersTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_teachers');

        $this->belongsToMany('EdcCourses', [
            'joinTable' => 'EdcTeachersCourses',
            'foreignKey' => 'edc_teacher_id'
        ]);
    }
}