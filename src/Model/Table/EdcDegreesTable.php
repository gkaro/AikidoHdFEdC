<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcDegreesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_degrees');
         
        $this->hasMany('EdcSubscriptions');
        $this->hasMany('EdcParticipants');
    }
}