<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcSubscriptionsTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_subscriptions');

        $this->addBehavior('Timestamp');
        $this->belongsTo('EdcSeasons')->setForeignKey('id_season');
        $this->hasMany('EdcParticipants')->setForeignKey('id_subscriptions');
        $this->belongsTo('EdcMembers')->setForeignKey('id_member');
        $this->belongsTo('EdcClubs')->setForeignKey('club_number')->setbindingKey('id_fed');
        $this->belongsTo('EdcGrades')->setForeignKey('grade');
    }
}