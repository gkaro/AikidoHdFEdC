<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcParticipantsTable extends Table
{
    public function initialize(array $config): void
    {     
        $this->setTable('mod2025edc_participants');

        $this->belongsTo('EdcCourses',[
            'foreignKey' => 'id_course'
        ]);
        $this->belongsTo('EdcSubscriptions',[
            'foreignKey' => 'id_subscriptions'
        ]);
        $this->addBehavior('CounterCache', [
            'EdcSubscriptions' => ['nb_courses'],
            'EdcMembers' => ['course_count'],
            'EdcCoursess' => ['count']
        ]);
    }
}