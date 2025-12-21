<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcEvalTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_eval');
        
        $this->belongsTo('EdcCourses',[
            'foreignKey' => 'id_course'
        ]);
        $this->belongsTo('EdcCourseTypes',[
            'foreignKey' => 'id'
        ]);
    }
}