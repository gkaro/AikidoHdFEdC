<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcClubsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_clubs');
        $this->hasMany('EdcSubscriptions')->setForeignKey('club_number')->setbindingKey('id_fed');
    }
}

