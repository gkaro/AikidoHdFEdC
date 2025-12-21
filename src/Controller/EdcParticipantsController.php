<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;
use Cake\View\JsonView;

DateTime::setDefaultLocale('fr_FR'); // For any immutable DateTime

class EdcParticipantsController extends AppController{

    public function viewClasses(): array
    {
        return [JsonView::class]; /**nécessaire pour requêtes AJAX de recherche */
    }
    
    public function add($id)/**ajouter un membre dans les participants au stage */
    {
        /**on récupère l'identification du stage via l'id */
        $course = $this->fetchTable('EdcCourses')
            ->findById($id)
            ->contain(['EdcTypes','EdcPlaces'])
            ->first();
        
        $idSeason = $course->id_season;
          
        /**liste des membres pour le champ de recherche : permet de vérifier que la personne est déjà présente dans la base */
        $members = $this->fetchTable('EdcMembers')
            ->find()
            ->contain('EdcSubscriptions', function (Query $q) use($idSeason){
                return $q
                ->where(['EdcSubscriptions.id_season IS' => $idSeason]);
            })
            ->orderBy(['last_name' => 'ASC'])
            ->all()
            ->combine('id', 'full_name');
       
            /**si la personne est dans la base et inscrite sur la saison -> addknown, sinon pas inscrite sur la saison -> addrenew */
        
        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

        $this->set(compact('course','members','optionsGrades','optionsDegrees','optionsClubs'));  
    }

    public function edit($id)/**modifier une inscription au stage */
    {
        /**récupère les informations du participant */
        $participant = $this->EdcParticipants
            ->findById($id)
            ->contain(['EdcSubscriptions','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades','EdcSubscriptions.EdcMembers','EdcCourses','EdcCourses.EdcPlaces'])
            ->first();
       
        /**mise à jour de la table edc-participant */
        if ($this->request->is(['post','put'])) {
            $this->EdcParticipants->patchEntity($participant, $this->request->getData(), [
            'associated' => ['EdcSubscriptions','EdcSubscriptions.EdcMembers']
            ]);
            if ($this->EdcParticipants->save($participant)) {
                $this->Flash->success(__('L\'inscription a été mise à jour.'));
                return $this->redirect(['controller'=>'edc-courses','action' => 'view',$participant->id_course]);
            }
            $this->Flash->error(__('Mise à jour impossible'));
            }

        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

        $this->set(compact('participant','optionsGrades','optionsDegrees','optionsClubs'));
    }

    public function delete($id)/**supprime un participant de la liste */
    {
        $this->request->allowMethod(['post', 'delete']);

        $participant = $this->EdcParticipants->findById($id)->firstOrFail();
        if ($this->EdcParticipants->delete($participant)) {
            $this->Flash->success(__('Effacé'));
            return $this->redirect(['controller'=>'edc-courses','action' => 'view',$participant->id_course]);
        }  
    }

    public function list($id)
    {
        $listCourses = $this->EdcParticipants
            ->find()
            ->contain(['EdcCourses'])
            ->where(['id_subscriptions' => $id])
            ->all();
        $subscription = $this->fetchTable('EdcSubscriptions')
            ->findById($id)
            ->contain(['EdcSeasons'])
            ->first();

        $this->set(compact('listCourses','subscription'));   
    }

    public function addnewfromcourse($id,$licence = null)/**ajoute un participant en créant un nouveau membre et une nouvelle inscription */
    {
        /**pour créer la liste déroulante dans le champ Rechercher une licence */
        $searchLicences = $this->fetchTable('EdcLicences') 
            ->find()
            ->orderBy(['licence' => 'ASC'])
            ->all()
            ->combine('id', 'licence');

        $participant = $this->EdcParticipants->newEmptyEntity();
        $participant->id_course = $id;
        
        if ($this->request->is('post')) {
            $participant = $this->EdcParticipants->patchEntity($participant, $this->request->getData(), 
            ['associated' => ['EdcSubscriptions','EdcSubscriptions.EdcMembers']]);
            if ($this->EdcParticipants->save($participant)) {
                $this->Flash->success(__('Inscription enregistrée.'));
                return $this->redirect(['controller'=>'EdcCourses','action' => 'view',$id]);
            }
            $this->Flash->error(__('Impossible d\'ajouter cette inscription.'));
        }

        /**on récupère les informations du stage à partir de l'id */
        $course = $this->fetchTable('EdcCourses')
            ->findById($id)
            ->contain(['EdcPlaces'])
            ->first();

         /**chargement de la liste de saisons sportives*/
        $seasons = $this->fetchTable('EdcSeasons')->find()->orderBy(['name'=>'DESC'])->all()->combine('id', 'name');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

       /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        /**liste des genres */
        $optionsGender = ['H'=>'H','F'=>'F','Autre'=>'Autre'];

        //si QR CODE scanné et pas trouvé dans la base member on prend les infos dans la table licence:
        if (!empty($licence)){
            $prevresult = $this->fetchTable('EdcLicences')->find()
            ->where(['licence IS' => $licence])
            ->first();           
            $this->set('prevresult', $prevresult);
        }
        $this->set(compact('participant','searchLicences','seasons','course','licence','optionsGrades','optionsDegrees','optionsGender','optionsClubs'));
    }

    public function qrcode($id)/**page de scan QRCode */
    {
        /**récupère l'identification du stage */
        $course = $this->fetchTable('EdcCourses')
            ->findById($id)
            ->contain(['EdcTypes','EdcPlaces'])
            ->first();

        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

        $this->set(compact('optionsGrades','optionsDegrees','optionsClubs','course'));
    }

    public function addhelloasso()/**valider un participant pré-inscrit sur Helloasso */
    {
        $id1 = $this->request->getQuery('id1');/*id du stage*/
        $id2 = $this->request->getQuery('id2');/*id de l'inscription*/
        $clubname = $this->request->getQuery('club');
        $gradelabel = $this->request->getQuery('grade');
        $degreelabel = $this->request->getQuery('degree');

        /**récupère les informations du stage */
        $course = $this->fetchTable('EdcCourses')
            ->findById($id1)
            ->contain(['EdcTypes','EdcPlaces'])
            ->first();

        $idseason = $course->id_season;

        /**on contrôle les données uniquement si l'id d'inscription est renseignée */
        if($id2 != NULL){
            $member = $this->fetchTable('EdcMembers')
                ->findById($id2)
                ->contain('EdcSubscriptions', function (Query $q) use($idseason){
                    return $q
                    ->where(['EdcSubscriptions.id_season IS' => $idseason]);
                })
                ->contain('EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades')
                ->first();
       
            $subscription = $this->fetchTable('EdcSubscriptions')
                ->find()
                ->where(['EdcSubscriptions.id_member IS' => $id2])
                ->where(['EdcSubscriptions.id_season IS' => $idseason])
                ->contain('EdcMembers','EdcClubs','EdcGrades')
                ->first();  
            
            if ($this->request->is(['post','put'])) {
                $this->EdcParticipants->patchEntity($member, $this->request->getData(), [
                'associated' => ['EdcSubscriptions']
                ]);
                if ($this->EdcParticipants->save($member)) {
                    
                    $this->Flash->success(__('L\'inscription a été mise à jour.'));
                    return $this->redirect(['controller'=>'edc-courses','action' => 'view',$member->id_course]);
                }
                $this->Flash->error(__('Mise à jour impossible'));
            }

            $googleMapKey = Configure::read('GoogleMap.googleMapKey');
            $this->set(compact('member','subscription','googleMapKey'));
        }

        /**récupère le grade */
        $grade = $this->fetchTable('EdcGrades')
            ->find()
            ->where(['label LIKE' => '%'.$gradelabel.'%'])
            ->first();
    
        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')
            ->find('all')
            ->all()
            ->combine('id', 'label');
        
        /**récupère le diplôme */
        $degree = $this->fetchTable('EdcDegrees')
            ->find()
            ->where(['id LIKE' => '%'.$degreelabel.'%'])
            ->first();
        
        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')
            ->find()
            ->all()
            ->combine('id', 'label');

        /**récupère le club */
        $clubTable = $this->fetchTable('EdcClubs');
        $club = $clubTable
            ->find()
            ->where(['complete_name LIKE' => '%'.$clubname.'%'])
            ->first();

        /**liste des clubs */
        $optionsClubs = $clubTable
            ->find('all')
            ->orderBy(['city'=>'ASC'])
            ->all()
            ->combine('id_fed', 'complete_name');

        /**liste des saisons sportives */
        $seasons = $this->fetchTable('EdcSeasons')
            ->find()
            ->orderBy(['name'=>'DESC'])
            ->all()
            ->combine('id', 'name');

        /**clé googlemap */
        $googleMapKey = Configure::read('GoogleMap.googleMapKey');

        $this->set(compact('course','club','grade','degree','seasons','optionsGrades','optionsDegrees','optionsClubs','googleMapKey'));
    }

    public function addnewhelloasso()/**inscription depuis helloasso pour quelqu'un qui n'est pas dans la base */
    {
        $id1 = $this->request->getQuery('id1'); // Récupère l'identifiant du stage
        $clubname = $this->request->getQuery('club');
        $gradelabel = $this->request->getQuery('grade');
       
        /**récupère les données du stage */
        $course = $this->fetchTable('EdcCourses')
            ->findById($id1)
            ->contain(['EdcTypes','EdcPlaces'])
            ->first();
        $idseason = $course->id_season;

        /**récupère le grade */
        $grade = $this->fetchTable('EdcGrades')
            ->find()
            ->where(['label LIKE' => '%'.$gradelabel.'%'])
            ->first();
        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');
        
        /**récupère le club */
        $clubTable = $this->fetchTable('EdcClubs');
        $club = $clubTable
            ->find()
            ->where(['complete_name LIKE' => '%'.$clubname.'%'])
            ->first();
        
        /**liste des clubs */
        $optionsClubs = $clubTable
            ->find('all')
            ->orderBy(['city'=>'ASC'])
            ->all()
            ->combine('id_fed', 'complete_name');

        /**crée un nouveau participant avec une nouvelle inscription */
        $participant = $this->EdcParticipants->newEmptyEntity();
        $participant->id_course = $id1;
        if ($this->request->is('post')) {
            $participant = $this->EdcParticipants->patchEntity($participant, $this->request->getData(), 
            ['associated' => ['EdcSubscriptions','EdcSubscriptions.EdcMembers']]);
            if ($this->EdcParticipants->save($participant)) {
                $this->Flash->success(__('Inscription enregistrée.'));
                return $this->redirect(['controller'=>'EdcCourses','action' => 'helloasso',$course->hello_asso]);
            }
            $this->Flash->error(__('Impossible d\'ajouter cette inscription.'));
        }

        /**liste des saisons sportives */
        $seasons = $this->fetchTable('EdcSeasons')->find()->orderBy(['name'=>'DESC'])->all()->combine('id', 'name');

        $this->set(compact('course','participant','club','grade','seasons','optionsGrades','optionsDegrees','optionsClubs',));
    }


    /**REQUETES AJAX */
    public function result()/**requête AJAX pour retrouver la personne dans la base */
    {
		$search = $_POST['name'];
        $idSeason = $_POST['idseason']; //utilisé uniquement dans le résultat de la requête
      
        /**on recherche dans le nom et dans le champ licencename */
		if ($this->request->is(['post', 'put'])) {
            $result = $this->fetchTable('EdcMembers')
            ->find()
            ->where(['OR' => [['licence_name LIKE' => '%'.$search.'%'],['full_name LIKE' => '%'.$search.'%']]])
            ->contain('EdcSubscriptions', function (Query $q){
                return $q
                ->orderBy(['EdcSubscriptions.id_season' => "DESC"]);
            })
            ->contain(['EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades'])
            ->first();
		}  

        $this->set('result', $result);
        $this->viewBuilder()->setOption('serialize', 'result');
	}

    public function resultlicence() /**requête AJAX sur add pour vérifier si licence à jour */
    {
		$lastName = $_POST['lastname'];
        $firstName = $_POST['firstname'];
       
        if ($this->request->is(['post', 'put'])) {
            $result = $this->fetchTable('EdcLicences')
            ->find()
            ->where(['last_name LIKE' => '%'.$lastName.'%'])
            ->andWhere(['first_name LIKE' => '%'.$firstName.'%'])
            ->first();
        }  
            
        $this->set('result', $result);
        $this->viewBuilder()->setOption('serialize', 'result');
	}

    public function resultlicencenew() /**requête AJAX sur addnewfromcourse pour rechercher dans table des licences */
    {
        $licence = $_POST['licence'];

        if ($this->request->is(['post', 'put'])) {
            $result = $this->getTableLocator()->get('EdcLicences')
            ->find()
            ->where(['licence' => $licence])
            ->first();
        }  
            
        $this->set('result', $result);
        $this->viewBuilder()->setOption('serialize', 'result');
    }

     public function resultscan()/**traitement AJAX de la lecture du QRCode */
    {
		$decodedText = $_POST['decodedText'];/**numéro de licence */
        $idseason = $_POST['idseason'];
      
		if ($this->request->is(['post', 'put'])) {
            /**on récupère les infos de la licence dans la base */
            $prevresult = $this->fetchTable('EdcLicences')
                ->find()
                ->where(['licence IS' => $decodedText])
                ->first();
            /**on concatène le nom et le prénom */
            $name = $prevresult->last_name . " " . $prevresult->first_name;

            /**on fait une recherche dans la table edc-member sur le nom complet */
            $prevresult2 = $this->fetchTable('EdcMembers')
                ->find()
                ->where(['full_name LIKE' => '%'. $name .'%'])
                ->contain('EdcSubscriptions', function (Query $q){
                    return $q
                    ->order(['EdcSubscriptions.id_season' => "DESC"]);
                })
                ->contain(['EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades'])
                ->first();
		}  

         /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

        $this->set(compact('prevresult','prevresult2','optionsGrades','optionsDegrees','optionsClubs'));
        $this->viewBuilder()->setOption('serialize', 'prevresult2');
	}

    public function addknown()/**requête AJAX pour ajouter un membre dans la liste des participants du stage */
    {
        //$idparticipant = $_POST['idmember'];
        $idsubs = $_POST['idsubs'];
        $idcourse = $_POST['idcourse'];
        $name = $_POST['name'];
        //$lastname = $_POST['lastname'];
       // $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $paid = $_POST['paid'];
		$age = $_POST['age'];
        $samediam = $_POST['samediam'];
        $samedipm = $_POST['samedipm'];
        $dimancheam = $_POST['dimancheam'];
        $edc = $_POST['edc'];
        $rgpd = $_POST['rgpd'];
        $idmember = $_POST['idmember'];
        $clubid = $_POST['clubid'];
        $gradeid = $_POST['gradeid'];
        $degree = $_POST['degree'];
        $idseason = $_POST['idseason'];

        /**récupère les informations de l'inscription saisonnière du membre */
        $subscriptionsTable =  $this->fetchTable('EdcSubscriptions');
        $subscription =  $subscriptionsTable
            ->findById($idsubs)
            ->contain('EdcClubs')
            ->firstOrFail();
        

        /**récupère les informations du stage */
        $course = $this->fetchTable('EdcCourses')
            ->findById($idcourse)
            ->contain(['EdcPlaces'])
            ->first();

        /**récupère les informations du membre */
        $membersTable= $this->fetchTable('EdcMembers');
        $member = $membersTable
            ->findById($idmember)
            ->firstOrFail();
       
        /**géolocalisation et calcul des km parcourus pour venir au stage */
        $origin = str_replace(' ', '_', $course->edc_place->name);
        $destination = str_replace(' ', '_', $subscription->edc_club->map);
        $key = Configure::read('GoogleMap.googleMapKey');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($origin)."&destinations=" . urlencode( $destination) . "&key=" . $key;
        $jsonfile = file_get_contents($url);
        $jsondata = json_decode($jsonfile);
        $km = round($jsondata->rows[0]->elements[0]->distance->value/1000);

        /**on construits les données pour mettre à jour la table edc-membre, edc-subscription et edc-participant */
        $data = [
                'id_subscriptions' => $idsubs,
                'id_course' => $idcourse,
                'saturday_am' => $samediam,
                'saturday_pm' => $samedipm,
                'sunday_am' => $dimancheam,
                'payment' => $paid,
                'km' => $km,
                'age' => $age,
                'edc'   => $edc,
                'rgpd'  => $rgpd,
                ];
        
        $datamember = [
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                ];
        
        $datasubscription = [
                'club_number' => $clubid,
                'grade' => $gradeid,
                'degree' => $degree,  
                'id_season' => $idseason, 
                ];         
        
        /**on met à jour les tables et on crée une nouvelle entrée dans la table edc-participant */
        $participant = $this->EdcParticipants->newEmptyEntity();
        if ($this->request->is('post')) {
            $this->EdcParticipants->patchEntity($participant, $data);
            if ($this->EdcParticipants->save($participant)) {
                $this->Flash->success(__('Inscription enregistrée.'));
            }
        }
        

       if ($this->request->is(['post','put'])) {
            $membersTable->patchEntity($member, $datamember);
            if ($membersTable->save($member)) {
                $this->Flash->success(__('L\'inscription a été mise à jour.'));
            }
        }

        if ($this->request->is(['post','put'])) {
            $subscriptionsTable->patchEntity($subscription, $datasubscription);
            if ($subscriptionsTable->save($subscription)) {
                $this->Flash->success(__('L\'inscription a été mise à jour.'));
            }
        }
    }

    public function addrenew()/**requête AJAX pour renouveler l'inscription d'un membre avant de valider l'inscription au stage */
    {
        $idparticipant = $_POST['idmember'];
        $idcourse = $_POST['idcourse'];
        $name = $_POST['name'];
        $paid = $_POST['paid'];
		$age = $_POST['age'];
        $edc = $_POST['edc'];
        $rgpd = $_POST['rgpd'];
        $idmember = $_POST['idmember'];
        $clubid = $_POST['clubid'];
        $gradeid = $_POST['gradeid'];
        $degree = $_POST['degree'];
        $idseason = $_POST['idseason'];

        $subscriptionsTable =  $this->fetchTable('EdcSubscriptions');
        
        /**récupère les informations du stage */
        $course = $this->fetchTable('EdcCourses')
        ->findById($idcourse)
        ->contain(['EdcPlaces'])
        ->first();
        
         /**on construits les données pour mettre à jour la table edc-subscription */
        $datasubscription = [
            'id_member' => $idmember,
            'club_number' => $clubid,
            'grade' => $gradeid,
            'degree' => $degree,  
            'id_season' => $idseason, 
            'age' => $age,
            'edc' => $edc
        ];         
        
        $newsub = $subscriptionsTable->newEmptyEntity();
        if ($this->request->is(['post'])) {
            $subscriptionsTable->patchEntity($newsub, $datasubscription);
            if ($subscriptionsTable->save($newsub)) {
                $this->Flash->success(__('L\'inscription a été mise à jour.'));
                $member = $this->fetchTable('EdcMembers')
                ->findById($idmember)
                ->contain('EdcSubscriptions', function (Query $q) use($idseason){
                    return $q
                    ->where(['EdcSubscriptions.id_season IS' => $idseason]);
                })
                ->firstOrFail();
            }
        }

        $this->set('member', $member);
        $this->viewBuilder()->setOption('serialize', 'member');   
    }

    public function recalckm($id)/**fonction AJAX pour mettre à jour les km parcourus */
    {
        $key = Configure::read('GoogleMap.googleMapKey');
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($_POST['origin'])."&destinations=" . urlencode(str_replace(' ', '_', $_POST['destination'])) . "&key=" . $key;

        /**on récupère les informations du participant */
        $participant =  $this->EdcParticipants
            ->findById($id)
            ->firstOrFail();

        /**calcule des km */
        $jsonfile = file_get_contents($url);
        $jsondata = json_decode($jsonfile);
        $km = round($jsondata->rows[0]->elements[0]->distance->value/1000);
        if ($this->request->is('ajax')) {
            $participant->km = $km;
            $this->EdcParticipants ->save($participant);
        }
    }

    public function recalcage($id)/**fonction AJAX pour mettre à jour l'âge */
    {
        /**on récupère les informations du participant */
        $participant = $this->EdcParticipants
            ->findById($id)
            ->contain(['EdcSubscriptions','EdcSubscriptions.EdcMembers'])
            ->first();

        //$dob = new DateTime($participant->EdcSubscriptions->EdcMembers->dob);
        $today = new DateTime();
        //$age = $today->diff($dob);
        $this->set(compact('participant'));
    }

    public function checkparticipant()
    {
       /**fonction AJAX qui vérifie dans la liste des préinscriptions HelloAsso si la personne a validé son inscription */
    }
    
}