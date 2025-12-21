<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\Collection\Collection;
use Cake\View\JsonView;
use Cake\Mailer\Mailer;

DateTime::setDefaultLocale('fr_FR'); // For any immutable DateTime

class EdcCoursesController extends AppController
{
    public function initialize(): void
    {
        parent::initialize(); 
    }

    public function viewClasses(): array
    {
        return [JsonView::class]; /**nécessaire pour requêtes AJAX de recherche */
    }

    protected array $paginate = [
        'limit' => 10, 
    ];
    
    public function index()/**liste des stages */
    {
        /**on récupère la liste des stages*/
        $courses = $this->EdcCourses
            ->find()
            ->orderBy(['date' => 'DESC'])
            ->contain(['EdcTeachers']);

        $this->set('courses', $this->paginate($courses));
    }

    public function view($id)/**vue des détails du stage : liste des participants, ajouter un participant, stats, évaluations */
    {
        /**récupère le stage avec l'id de l'URL */
        $course = $this->EdcCourses
            ->findById($id)
            ->contain(['EdcTypes','EdcPlaces','EdcTeachers'])
            ->firstOrFail();
 
        /**récupère la liste des participants du stage */
        $coursesParticipants = $this->fetchTable('EdcParticipants')
            ->find()
            ->where(['id_course' => $id])
            ->orderBy(['EdcMembers.last_name'=>'ASC'])
            ->contain(['EdcSubscriptions','EdcSubscriptions.EdcMembers','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades'])
            ->all();


        /**compte le montant des paiements */
        $sum = (new Collection($coursesParticipants))->sumOf('payment');
        
        $this->set(compact('coursesParticipants','sum','course'));
    }

    public function add()/**ajouter un stage */
    {
        /**création d'une nouvelle entrée dans la table edc_courses */
        $course = $this->EdcCourses->newEmptyEntity();
        
        if ($this->request->is('post')) {
            $course = $this->EdcCourses->patchEntity($course, $this->request->getData());
            if ($this->EdcCourses->save($course)) {
                $this->Flash->success(__('Le stage a été ajouté avec succès.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le stage n\'a pas pu être ajouté. Veuillez réessayer.'));
        }

        /**liste des saisons sportives */
        $seasons = $this->fetchTable('EdcSeasons')->find('list',limit: 3, order:'name DESC')->all();

        /**liste des types de stage */
        $types = $this->fetchTable('EdcTypes')->find()->all()->combine('id','name');

        /**liste des lieux de stage */
        $places = $this->fetchTable('EdcPlaces')->find('all', order:'name ASC')->all()->combine('id', 'name');

        /**liste des animateurs de stage */
        $teachers = $this->fetchTable('EdcTeachers')->find('all', order:'name ASC')->all()->combine('id', 'name');

        $this->set(compact('seasons','teachers','types','places','course'));
    }

    public function edit($id)/**ajouter un stage */
    {
         /**récupère le stage avec l'id de l'URL */
        $course = $this->EdcCourses
            ->findById($id)
            ->contain(['EdcTypes','EdcPlaces','EdcTeachers'])
            ->firstOrFail();
        
        /**enregistre les modifications */
        if ($this->request->is(['post', 'put'])) {
            $course = $this->EdcCourses->patchEntity($course, $this->request->getData(), [
                'associated' => ['EdcTypes','EdcPlaces','EdcTeachers']
            ]);
            if ($this->EdcCourses->save($course)) {
                $this->Flash->success(__('Le stage a été mis à jour avec succès.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Le stage n\'a pas pu être mis à jour. Veuillez réessayer.'));
        }

        /**liste des saisons sportives */
        $seasons = $this->fetchTable('EdcSeasons')->find('list',limit: 3, order:'name DESC')->all();

        /**liste des types de stage */
        $types = $this->fetchTable('EdcTypes')->find()->all()->combine('id','name');

        /**liste des lieux de stage */
        $places = $this->fetchTable('EdcPlaces')->find('all', order:'name ASC')->all()->combine('id', 'name');

        /**liste des animateurs de stage */
        $teachers = $this->fetchTable('EdcTeachers')->find('all', order:'name ASC')->all()->combine('id', 'name');

        $this->set(compact('seasons','teachers','types','places','course'));
    }

    public function listparticipants($id)/**génère la liste des participants depuis la vue edc-courses/index */
    {
        /**récupère les informations du stage pour afficher info dans le titre*/
        $course = $this->EdcCourses
            ->findById($id)
            ->first();

        /**récupère la liste des participants au stage */
        $participants = $this->fetchTable('EdcParticipants')
            ->find()
            ->contain(['EdcSubscriptions', 'EdcSubscriptions.EdcMembers','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades'])
            ->where(['id_course'=>$id])
            ->orderBy(['EdcMembers.last_name'=>'ASC'])
            ->all();

        $this->set(compact('participants','course'));
    }

    public function stats($id)/**génère la vue statistiques depuis la vue edc-courses/index */
    {
        /**récupère les informations du stage pour afficher info dans le titre et pour localisation du stage dans googlemap*/
        $course = $this->EdcCourses
            ->findById($id)
            ->contain(['EdcTypes','EdcPlaces'])
            ->first();

        /**récupère les données des participants au stage */
        $participantsTable = $this->fetchTable('EdcParticipants');  
        
        $participants = $participantsTable /**liste complète des participants */
            ->find('all')
            ->contain(['EdcSubscriptions','EdcCourses','EdcSubscriptions.EdcClubs', 'EdcSubscriptions.EdcMembers'])
            ->where(['id_course'=>$id]);

        $participantsClubs = $participantsTable/**liste qui exclu les participants hors ligue pour faire les stats de geoloc */
            ->find('all')
            ->contain(['EdcSubscriptions','EdcCourses','EdcSubscriptions.EdcClubs'])
            ->where(['id_course'=>$id])
            ->where(['EdcSubscriptions.club_number IS NOT'=>'99999999'])
            ->select(['clubname'=>'EdcClubs.name','ville'=>'EdcClubs.city','map'=>'EdcClubs.map','km']);

        $participantsClubs->select([
            'count' => $participantsClubs->func()->count('EdcClubs.name')])
            ->group('EdcClubs.name');

        $participantsGrades = $participantsTable /**liste des participants par grade */
            ->find('all')
            ->contain(['EdcSubscriptions','EdcSubscriptions.EdcGrades'])
            ->where(['id_course'=>$id])
            ->select(['grade'=>'EdcGrades.label']);

        $participantsGrades
            ->select(['count' => $participantsGrades->func()->count('EdcGrades.label')])
            ->group('EdcGrades.label')
            ->orderBy(['EdcGrades.id' => 'ASC']);

        $participantsDegrees = $participantsTable/**liste des participants par diplôme */
            ->find('all')
            ->contain(['EdcSubscriptions'])
            ->where(['id_course'=>$id])
            ->select(['degree'=>'degree']);

        $participantsDegrees
            ->select(['count' => $participantsDegrees->func()->count('degree')])
            ->group('degree');
        
        $participantsAge = $participantsTable/**liste des participants par âge avec exclusion des âges non renseignés */
            ->find('all')
            ->where(['id_course'=>$id])
            ->where(['age IS NOT'=> NULL])
            ->select(['age']);

        $participantsAge
            ->select(['count' => $participantsAge->func()->count('age')])
            ->group('age');

        $avgAge = $participantsTable /**calcul moyenne d'âge */
            ->find()
            ->where(['id_course'=>$id])
            ->andWhere(['age IS NOT'=> NULL])
            ->all()
            ->avg('age');

        $avgKm = $participantsTable/**calcul moyenne de km parcourus en excluant les participants hors ligue */
            ->find()
            ->contain('EdcSubscriptions')
            ->where(['id_course'=>$id])
            ->andWhere(['EdcSubscriptions.club_number IS NOT'=>'99999999'])/*pour ne pas prendre en compte les les participants "hors ligue"*/
            ->all()
            ->avg('km');

        $googleMapKey = Configure::read('GoogleMap.googleMapKey');
        $this->set(compact('participants','course','participantsClubs','participantsAge','participantsGrades','participantsDegrees','avgAge','avgKm','googleMapKey'));
    }

    public function exportparticipants($id)/**export des participants au format excel */
    {
        /**récupère les informations du stage */
        $course = $this->EdcCourses
            ->findById($id)
            ->contain(['EdcTypes'])
            ->firstOrFail();
 
        /**récupère la liste des participants du stage */
        $coursesParticipants = $this->fetchTable('EdcParticipants')
            ->find()
            ->where(['id_course' => $id])
            ->contain(['EdcSubscriptions','EdcSubscriptions.EdcMembers','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades'])
            ->all()
            ->toArray();

        /**construit le tableau */
        $datatabl='';
        $datatabl = '<table cellspacing="2" cellpadding="5">';
        $datatabl .= '<thead>
			<th>Nom et prenom</th>
			<th>Club</th>
            <th>Email</th>
            <th>Grade</th>
            <th>Diplome</th>
            <th>Samedi matin</th>
            <th>Samedi apres-midi</th>
            <th>Dimanche</th>
            <th>Paiement</th>
            <th>Km</th>
            <th>EdC</th>
            <th>RGPD</th>
            </thead>';
        foreach($coursesParticipants as $i){
            $name = $i['edc_subscription']['edc_member']['full_name'];
            $club = $i['edc_subscription']['edc_club']['name'];
            $email = $i['edc_subscription']['edc_member']['email'];
            $grade = $i['edc_subscription']['edc_grade']['label'];
            $degree = $i['edc_subscription']['degree'];
            $satam = $i['saturday_am'];
			$satpm = $i['saturday_pm'];
            $sunam = $i['sunday_am'];
            $payment = $i['payment'];
            $km = $i['km'];
            $rgpd = $i['rgpd'];
			
            if($i['edc'] == 'oui'){
                $edc = '1';
            }else if($i['edc'] == 'non' | $i['edc'] == null)
            {
                $edc = '0';
            }

			$color_border = '#d5d5d5';
			$background_color = '#eeeeee';
            $style = 'vertical-align:middle;border:1px solid';

            $datatabl .= '<tr>
				<td style="vertical-align:middle;border:1px solid ' . $color_border . '">' . $name . '</td>';
			$datatabl .= '<td style="' . $style  . $color_border . '" >' . $club . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $email . '</td>';
			$datatabl .= '<td style="' . $style  . $color_border . '" >' . $grade . '</td>';
			$datatabl .= '<td style="' . $style  . $color_border . '" >' . $degree . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $satam . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $satpm . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $sunam . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $payment . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $km . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $edc . '</td>';
            $datatabl .= '<td style="' . $style  . $color_border . '" >' . $rgpd . '</td>';
        }
        $datatabl .= '</table>';
        header('Content-type: application/force-download; charset=UTF-8;');
        header('Content-disposition:attachment; filename= export_participants_'. $course->edc_course_type->name .'.xls');
        header('Pragma: ');
        header('cache-control: ');
        echo $datatabl;
        die;
	}



    /**Evaluations des stages */
    public function evaluation($id)/**vue des évaluations avec lien pour formulaire et export */
    {
        /**récupère les infos du stage pour mettre l'id sur les boutons formulaire et export */
        $course = $this->EdcCourses
            ->findById($id)
            ->contain(['EdcTypes'])
            ->firstOrFail();

        /**récupère les informations enregistrées via le formulaire */
        $evaluations = $this->fetchTable('EdcEval')
            ->find()
            ->where(['id_course'=>$course->id])
            ->all();

        $this->set(compact('course','evaluations'));
    }

    public function form($id)/**formulaire d'évaluation */
    {
       /**on utilise un layout spécifique sans menu */
        $this->viewBuilder()->setLayout('form');

        /**récupère les infos du stage à évaluer*/
        $course = $this->EdcCourses
        ->findById($id)
        ->contain(['EdcTypes'])
        ->firstOrFail();

        /**on récupère l'email d'envoi dans la config */
        $emailSender = Configure::read('Email.emailAddress');
        
        /**enregistre une nouvelle entrée dans la tabe edc-eval */
        $evalTable = $this->fetchTable('EdcEval');  
        $form = $evalTable->newEmptyEntity();

        if ($this->request->is('post')) {
            $form = $evalTable->patchEntity($form, $this->request->getData(), [
                'associated' => ['EdcCourses']
            ]);
            if ($evalTable->save($form)) {//on envoi le formulaire par mail à celui qui l'a rempli
                $name = $form->name;
                $emailReceiver = $form->email;
                $id = $course->edc_course_type->name;
                $question1 = $form->question1;
                $question2 = $form->question2;
                $question3 = $form->question3;
                $comments = $form->comments;
                $mailer = new Mailer('default');
                
                $mailer
                    ->setFrom('act@aikido-hdf.fr')
                    ->setViewVars([ //email view variables
                        'name' => $name,
                        'id' => $id,
                        'question1' => $question1,
                        'question2' => $question2,
                        'question3' => $question3,
                        'comments' => $comments,
                        ])
                    ->setTo($emailReceiver)
                    ->setSubject('Evaluation du stage')
                    ->setEmailFormat('html')
                    ->viewBuilder()
                    ->setTemplate('eval');
                $mailer->send();
                $this->Flash->success(__('Evaluation envoyée'));
                return $this->redirect(['action' => 'thanks']);
            }
            $this->Flash->error(__('Echec de l\'envoi'));
        }
        $this->set(compact('course','form'));
    }

    public function exporteval($id)/**export des évaluations */
    {
        /**récupère les infos du stage évalué*/
        $course = $this->EdcCourses
        ->findById($id)
        ->firstOrFail();

        /**on construit le tableau */
        $evalTable = $this->fetchTable('EdcEval');  

        $evaluations = $evalTable->find()->where(['id_course'=>$course->id])->all()->toArray();

        $datatabl='';
        $datatabl = '<table cellspacing="2" cellpadding="5">';
        $datatabl .= '<thead>
			<th>Qualité de l\'organisation (inscription, accueil, information, conditions matérielles...)</th>
			<th>Qualité de l\'animation, de l\'intervention des animateurs</th>
            <th>Qualité et pertinence du contenu proposé, des ressources mises à disposition</th>
            <th>Commentaires</th>
            </thead>';
        foreach($evaluations as $i){
            $question1 = $i['question1'];
            $question2 = $i['question2'];
            $question3 = $i['question3'];
            $comments = $i['comments'];
			
			$color_border = '#d5d5d5';
			$background_color = '#eeeeee';

            $datatabl .= '<tr>
				    <td style="vertical-align:middle;border:1px solid ' . $color_border . '">' . $question1 . '</td>';
			$datatabl .= '<td style="vertical-align:middle;border:1px solid ' . $color_border . '" >' . $question2 . '</td>';
            $datatabl .= '<td style="vertical-align:middle;border:1px solid ' . $color_border . '" >' . $question3 . '</td>';
            $datatabl .= '<td style="vertical-align:middle;border:1px solid ' . $color_border . '" >' . $comments . '</td>';
        }
        $datatabl .= '</table>';
        header('Content-type: application/force-download; charset=UTF-8;');
        header('Content-disposition:attachment; filename= export_evaluation_'. $course->id .'.xls');
        header('Pragma: ');
        header('cache-control: ');
        echo $datatabl;
        die;
	}

    public function thanks()/**page de remerciement après envoi formulaire */
    {
        $this->viewBuilder()->setLayout('form');
    }

/**--------------------------------*/
    /**Gestion HelloAsso*/

    public function helloasso($id)/**vue liste des pré-inscriptions de HelloAsso */
    {
        /**infos config app_local */
        $association = Configure::read('HelloAsso.association');
        $helloassoClient = Configure::read('HelloAsso.helloassoClient');
        $helloassoClientSecret = Configure::read('HelloAsso.helloassoClientSecret');

        /**récupère les infos du stage*/
        $course = $this->EdcCourses
            ->find()
            ->where(['hello_asso' => $id])
            ->firstOrFail();

        $this->set(compact('course','association','helloassoClient','helloassoClientSecret'));
    }

    public function searchparticipant()/**fonction AJAX sur vue edc-courses/helloasso pour déterminer si la personne inscrite sur Helloasso a été validée */
    { 
        /*données envoyées par la fonction AJAX*/
        $idcourse = $_POST['idcourse'];
        $name =  $_POST['name'];

        /**rechercher les participants par nom */
        $participant = $this->fetchTable('EdcParticipants')
            ->find()
            ->contain(['EdcSubscriptions', 'EdcSubscriptions.EdcMembers'])
            ->where(['EdcMembers.full_name LIKE' => '%'.$name.'%'])
            ->andWhere(['EdcParticipants.id_course'=>$idcourse])
            ->first();
		
        $this->set('participant', $participant);
        $this->viewBuilder()->setOption('serialize', 'participant');
    }
}