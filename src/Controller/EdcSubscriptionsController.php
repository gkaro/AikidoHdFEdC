<?php
namespace App\Controller;

use Cake\I18n\DateTime;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\ORM\Locator\LocatorAwareTrait;


DateTime::setDefaultLocale('fr_FR'); // For any immutable DateTime

class EdcSubscriptionsController extends AppController{

    public function add()/*ajouter une inscription dans la saison*/
    {
        $sub = $this->EdcSubscriptions->newEmptyEntity();

        if ($this->request->is('post')) {
            $sub = $this->EdcSubscriptions->patchEntity($sub, $this->request->getData(), 
            ['associated' => ['EdcMembers']]);
            if ($this->EdcSubscriptions->save($sub)) {
                $this->Flash->success(__('Inscription enregistrée.'));
                return $this->redirect(['controller'=>'EdcMembers','action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'ajouter cette inscription.'));
        }

       /**on récupère la liste des saisons sportives */
        $seasons = $this->fetchTable('EdcSeasons')->find()->orderBy(['name'=>'DESC'])->all()->combine('id', 'name');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        $this->set(compact('sub','optionsClubs','optionsGrades','optionsDegrees','seasons'));
    }

    public function renew($id)/**renouveler l'inscription depuis la page edc-members/view */
    {
        /**récupère les informations du membre */
        $member = $this->fetchTable('EdcMembers')
            ->findById($id)
            ->contain('EdcSubscriptions', function (Query $q){
                return $q->orderBy(['id_season'=>'DESC']);
            })
            ->contain(['EdcSubscriptions.EdcSeasons','EdcSubscriptions.EdcClubs','EdcSubscriptions.EdcGrades'])
            ->firstOrFail();

        $newSub = $this->EdcSubscriptions->newEmptyEntity();
        if ($this->request->is(['post'])) {
            $this->EdcSubscriptions->patchEntity($newSub, $this->request->getData(), [
                'associated' => ['EdcMembers','EdcSeasons','EdcGrades','EdcClubs']
            ]);
            if ($this->EdcSubscriptions->save($newSub)) {
                $this->Flash->success(__('L\'inscription a été mise à jour.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }

        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

        /**liste des saisons sportives */
        $seasonTable = $this->fetchTable('EdcSeasons')->find()->orderBy(['name'=>'DESC'])->all()->combine('id', 'name');

        $this->set(compact('optionsDegrees','optionsClubs','optionsGrades', 'newSub','member','seasonTable'));
    }

    public function edit($id)/**modifier une inscription */
    {
        /**récupère les données de l'inscription */
        $sub = $this->EdcSubscriptions
            ->findById($id)
            ->contain(['EdcSeasons','EdcMembers'])
            ->firstOrFail();

        if ($this->request->is(['post','put'])) {
            $this->EdcSubscriptions->patchEntity($sub, $this->request->getData(), [
                'associated' => ['EdcMembers','EdcSeasons']
            ]);
            if ($this->EdcSubscriptions->save($sub)) {
                $this->Flash->success(__('L\'inscription a été mise à jour.'));
                return $this->redirect(['controller'=>'EdcMembers','action' => 'view',$sub->idmember]);
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }
        
        /**liste des grades */
        $optionsGrades = $this->fetchTable('EdcGrades')->find()->all()->combine('id', 'label');

        /**liste des clubs */
        $optionsClubs = $this->fetchTable('EdcClubs')->find()->orderBy(['city'=>'ASC'])->all()->combine('id_fed', 'complete_name');

        /**liste des diplômes */
        $optionsDegrees = $this->fetchTable('EdcDegrees')->find()->all()->combine('id', 'label');

        /**liste des saisons */
        $seasonTable = $this->fetchTable('EdcSeasons')->find()->orderBy(['name'=>'DESC'])->all()->combine('id', 'name');

        $this->set(compact('sub','optionsClubs','optionsGrades','optionsDegrees','seasonTable'));
    }

    public function delete($id)/**supprimer l'inscription */
    {
        $this->request->allowMethod(['post', 'delete']);

        $sub = $this->EdcSubscriptions->findById($id)->firstOrFail();
        if ($this->EdcSubscriptions->delete($sub)) {
            $memberid = $sub->id_member;
            $this->Flash->success(__('Inscription supprimée.'));
            return $this->redirect(['controller'=>'edc-members','action' => 'view',$memberid]);
        }
    }
}