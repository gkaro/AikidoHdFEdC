<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcGradesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_grades');
        $this->hasMany('EdcSubscriptions')->setForeignKey('actualgrade');
        $this->hasMany('EdcCourses');
    }
}