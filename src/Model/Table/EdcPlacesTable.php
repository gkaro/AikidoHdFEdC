<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcPlacesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_places');

        $this->hasMany('EdcCourses',[
            'foreignKey' => 'idplace'
        ]);
    }
}