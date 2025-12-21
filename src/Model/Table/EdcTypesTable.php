<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcTypesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_types');
        
        $this->hasMany('EdcCourses',[
            'foreignKey' => 'idtype'
        ]); 
       /* $this->hasMany('EdcEval',[
            'foreignKey' => 'idtype'
        ]); */
    }
}