<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcMembersTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_members');
        
        $this->addBehavior('Timestamp');
        $this->hasMany('EdcSubscriptions')->setForeignKey('id_member');
    }
}