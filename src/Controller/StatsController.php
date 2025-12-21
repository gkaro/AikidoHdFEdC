<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;

DateTime::setDefaultLocale('fr_FR'); // For any immutable DateTime

class StatsController extends AppController
{
    public function index()
    {
        $seasons = $this->fetchTable('EdcSeasons')
            ->find('all',['orderBy' => ['id'=>'DESC']])
            ->select(['id','name'])
            ->limit(5);

        $participantsTable = $this->fetchTable('EdcParticipants');
        $participants = $participantsTable
            ->find('all')
            ->contain(['EdcCourses','EdcSubscriptions','EdcSubscriptions.EdcMembers']);

        $types = $this->fetchTable('EdcCourseTypes')
            ->find('all');

        $subscriptions = $this->fetchTable('EdcSubscriptions')
            ->find('all')
            ->contain(['EdcMembers']);

        $this->set(compact('subscriptions','participants','seasons','types'));  
    }
}