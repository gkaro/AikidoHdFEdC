<?php
namespace App\Controller;

use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;


DateTime::setDefaultLocale('fr_FR'); // For any immutable DateTime


class EdcMembersController extends AppController
{
    public function index()/**liste des personnes inscrites dans la table edc-members */
    {
        $memberlist = $this->EdcMembers
            ->find('all')
            ->contain(['EdcSubscriptions','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcSeasons','EdcSubscriptions.EdcParticipants'])
            ->orderBy(['last_name'=>'ASC'])
            ->distinct(['full_name']);

        $this->set(compact('memberlist'));
    }

    public function view($id)/**vue détaillée d'un membre */
    {
        /**on récupère les infos via l'id */
        $member = $this->EdcMembers
            ->findById($id)
            ->contain(['EdcSubscriptions.EdcSeasons','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades'])
            ->firstOrFail();

        $this->set(compact('member'));   
    }

    public function result()/**requête AJAX pour rechercher un pratiquant dans la base edc_members sur le vue edc-members/index*/
    {
		$search = $_POST['name'];/**nom du pratiquant envoyé par la requête via le champ recherche */

		if ($this->request->is(['post', 'put'])) {
			$result = $this->EdcMembers
            ->find()
            ->where(['EdcMembers.last_name LIKE' => '%'.$search.'%'])
            ->contain(['EdcSubscriptions','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcSeasons'])
			->all();
		}  

        $this->set('result', $result);
        $this->viewBuilder()->setLayout('ajax');
	}

    public function edit($id)/**modifier un membre */
    {
        /**on récupère les infos via l'id */
        $member = $this->EdcMembers
            ->findById($id)
            ->firstOrFail();

        /**enregistrement du formulaire */
        if ($this->request->is(['post','put'])) {
            $this->EdcMembers->patchEntity($member, $this->request->getData());
            if ($this->EdcMembers->save($member)) {
                $this->Flash->success(__('L\'inscription a été mise à jour.'));
                return $this->redirect(['controller'=>'EdcMembers','action' => 'view',$id]);
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }
        
        $this->set(compact('member'));
    }
}