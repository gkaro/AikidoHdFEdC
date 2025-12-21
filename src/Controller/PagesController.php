<?php
declare(strict_types=1);

/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;

/**
 * Static content controller
 *
 * This controller will render views from templates/Pages/
 *
 * @link https://book.cakephp.org/5/en/controllers/pages-controller.html
 */
class PagesController extends AppController
{
    /**
     * Displays a view
     *
     * @param string ...$path Path segments.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Http\Exception\ForbiddenException When a directory traversal attempt.
     * @throws \Cake\View\Exception\MissingTemplateException When the view file could not
     *   be found and in debug mode.
     * @throws \Cake\Http\Exception\NotFoundException When the view file could not
     *   be found and not in debug mode.
     * @throws \Cake\View\Exception\MissingTemplateException In debug mode.
     */
    public function display(string ...$path): ?Response
    {
        if (!$path) {
            return $this->redirect('/');
        }
        if (in_array('..', $path, true) || in_array('.', $path, true)) {
            throw new ForbiddenException();
        }
        $page = $subpage = null;

        $today = date("Y-m-d");
        $tomorrow = date('Y-m-d', strtotime('tomorrow'));
        $yesterday = date('Y-m-d', strtotime('yesterday'));

        $courseTable = $this->fetchTable('EdcCourses');
        $participantsTable = $this->fetchTable('EdcParticipants');

        $currentSeason = $this->fetchTable('EdcSeasons')->find()->all()->last();
        $prevSeason = $currentSeason->id - 1;

        if (!empty($path[0])) {
            $page = $path[0];

            /**récupération des données du précédent stage */
            $prevCourse = $courseTable
            ->find()
            ->contain(['EdcParticipants.EdcSubscriptions', 'EdcParticipants','EdcParticipants.EdcSubscriptions.EdcMembers','EdcTypes','EdcPlaces'])
            ->orderBy(['date'=>'DESC'])
            ->where(['date <'=> $yesterday])
            ->all()
            ->first();

            $avgKm = $participantsTable
            ->find()
            ->contain('EdcSubscriptions')
            ->where(['id_course'=>$prevCourse->id])
            ->where(['EdcSubscriptions.club_number IS NOT'=>'99999999'])/*pour ne pas prendre en compte les participants "hors ligue"*/
            ->all()
            ->avg('km');
      
            /**liste des prochains stages*/
            $nextCourses = $courseTable
            ->find()
            ->contain(['EdcTypes','EdcPlaces'])
            ->where(['date >='=> $yesterday])
            ->all();

            /**participants des dix derniers stages */
            $allParticipants = $participantsTable
            ->find('all')
            ->orderBy(['date'=>'DESC'])
            ->limit(10)
            ->contain(['EdcCourses'])
            ->select(['date'=>'EdcCourses.date']);
            
            $allParticipants->select([
                'count' => $allParticipants->func()->count('id_subscriptions')])->groupBy('id_course');
            
            /**Nombre de stages de la saison précédente */
            $allPrevCourses = $courseTable
                ->find('all')
                ->where(['id_season' => $prevSeason]);
            $allPrevCoursesTotal = $allPrevCourses->count();
            
            /**Participants aux stages de la saison précédente */
            $allPrevParticipantsTotal = $participantsTable
                ->find('all')
                ->contain(['EdcCourses','EdcSubscriptions','EdcSubscriptions.EdcMembers'])
                ->where(['EdcCourses.id_season' => $prevSeason]);
            $allPrevParticipantsCount = $allPrevParticipantsTotal
                ->find('all')
                ->count();
            
            /**Participants Ligue aux stages de la saison précédente */
            $allPrevParticipantsHdfCount = $allPrevParticipantsTotal
                ->find('all')
               ->where(['EdcSubscriptions.club_number IS NOT' => "99999999"])
                ->count();

            $uniquePrevParticipants = $participantsTable
                ->find('all')
                ->contain(['EdcCourses','EdcSubscriptions','EdcSubscriptions.EdcMembers'])
                ->where(['EdcCourses.id_season' => $prevSeason])
                ->groupBy(['id_subscriptions']);
            $uniquePrevParticipantsCount = $uniquePrevParticipants
                ->find('all')
                ->count();
            

            /**Nombre de stages de la saison en cours */
            $allCurrentCourses = $courseTable
                ->find('all')
                ->where(['id_season' => $currentSeason->id])
                ->andWhere(['date <'=> $today]);
            $allCurrentCoursesTotal = $allCurrentCourses->count();

            /**Participants aux stages de la saison en cours */
            $allCurrentParticipantsTotal = $participantsTable
                ->find('all')
                ->contain(['EdcCourses','EdcSubscriptions','EdcSubscriptions.EdcMembers'])
                ->where(['EdcCourses.id_season' => $currentSeason->id]);
            $allCurrentParticipantsCount = $allCurrentParticipantsTotal
                ->find('all')
                ->count();
            /**Participants Ligue aux stages de la saison en cours */
            $allCurrentParticipantsHdfCount = $allCurrentParticipantsTotal
                ->find('all')
                ->andWhere(['EdcSubscriptions.club_number IS NOT' => "99999999"])
                ->count();

            $uniqueParticipants = $participantsTable
                ->find('all')
                ->contain(['EdcCourses','EdcSubscriptions','EdcSubscriptions.EdcMembers'])
                ->where(['EdcCourses.id_season' => $currentSeason->id])
                ->groupBy(['id_subscriptions']);
            $uniqueParticipantsCount = $uniqueParticipants
                ->find('all')
                ->count();


            /**top 10 participations aux stages */
            $firstAttendant = $this->fetchTable('EdcMembers')
                ->find('all')
                ->orderBy(['course_count'=>'DESC'])
                ->limit(10);


            /**Top 10 stages */
            $bestCourses = $courseTable
                ->find('all')
                ->contain(['EdcTypes','EdcPlaces'])
                ->orderBy(['count'=>'DESC'])
                ->limit(10);
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }

        $this->set(compact('uniquePrevParticipantsCount','uniqueParticipantsCount','uniquePrevParticipants','uniqueParticipants','allCurrentParticipantsHdfCount','allCurrentParticipantsCount','allPrevParticipantsHdfCount','allPrevParticipantsCount','bestCourses','firstAttendant','prevCourse','nextCourses','allParticipants','avgKm','allPrevCoursesTotal','allPrevParticipantsTotal','allCurrentCoursesTotal','allCurrentParticipantsTotal'));


        try {
            return $this->render(implode('/', $path));
        } catch (MissingTemplateException $exception) {
            if (Configure::read('debug')) {
                throw $exception;
            }
            throw new NotFoundException();
        }
    }
}
