<?php
namespace App\Model\Table;
use Cake\ORM\Table;

class EdcCoursesTable extends Table
{
    public function initialize(array $config): void
    {
        $this->setTable('mod2025edc_courses');
        
        $this->hasOne('EdcSeasons');
        $this->belongsTo('EdcTypes',[
            'foreignKey' => 'id_type'
        ]);
        $this->belongsTo('EdcPlaces',[
            'foreignKey' => 'id_place'
        ]);
        $this->belongsToMany('EdcTeachers', [
            'joinTable' => ' EdcTeachersCourses',
            'foreignKey' => 'edc_course_id'
        ]);
        $this->hasMany('EdcParticipants',[
            'foreignKey' => 'id_course'
        ]);
        $this->hasMany('EdcEval',[
            'foreignKey' => 'id_course'
        ]);
    }
}