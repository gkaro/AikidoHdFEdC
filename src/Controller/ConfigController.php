<?php
namespace App\Controller;

class ConfigController extends AppController
{
    public function index()
    {
        $types = $this->fetchTable('EdcCourseTypes')->find('all');
        $places = $this->fetchTable('EdcCoursePlaces')->find('all');
        $teachers = $this->fetchTable('EdcTeachers')->find('all')->orderBy(['name'=>'ASC']);
        $clubs = $this->fetchTable('EdcClubs')->find('all');
        $seasons = $this->fetchTable('EdcSeasons')->find('all')->orderBy(['name'=>'DESC']);

        $this->set(compact('types','places','teachers','clubs','seasons'));
    }

    public function addtype()/**ajouter un type de stage */
    {  
        $typesTable = $this->fetchTable('EdcCourseTypes');
          
        $type = $typesTable->newEmptyEntity();
        if ($this->request->is('post')) {
            $type = $typesTable->patchEntity($type, $this->request->getData()); 
            if ($typesTable->save($type)) {   
                $this->Flash->success(__('Type de stage enregistré.'));
                return $this->redirect(['controller'=>'Config','action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'ajouter ce type de stage.'));
        }

        $this->set(compact('type'));
    }

    public function edittype($id) /**modifier un type de stage */
    {  
        $typesTable = $this->fetchTable('EdcCourseTypes');
          
        $type = $typesTable
            ->findById($id)
            ->firstOrFail();

        if ($this->request->is(['post','put'])) {
            $typesTable->patchEntity($type, $this->request->getData());
            if ($typesTable->save($type)) {
                $this->Flash->success(__('Le type a été mise à jour.'));
                return $this->redirect(['controller'=>'Config','action' => 'index']);
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }

        $this->set(compact('type'));
    }

    public function deletetype($id) /**supprimer un type de stage */
    {  
        $typesTable = $this->fetchTable('EdcCourseTypes');
          
        $this->request->allowMethod(['post', 'delete']);

        $type = $ttypesTableypes->findById($id)->firstOrFail();
        if ($typesTable->delete($type)) {
            $this->Flash->success(__('Effacé'));
            return $this->redirect(['controller'=>'Config','action' => 'index']);
        }
    }



    public function addclub() /**ajouter un club */
    {  
        $clubsTable = $this->fetchTable('EdcClubs');
          
        $club = $clubsTable->newEmptyEntity();
        if ($this->request->is('post')) {
            $club = $clubsTable->patchEntity($club, $this->request->getData()); 
            if ($clubsTable->save($club)) {   
                $this->Flash->success(__('Club enregistré.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'ajouter ce club.'));
        }

        $this->set(compact('club'));
    }

    public function editclub($id)/**modifier un club */
    {  
        $clubsTable = $this->fetchTable('EdcClubs');
          
        $club = $clubsTable
            ->findById($id)
            ->firstOrFail();

        if ($this->request->is(['post','put'])) {
            $clubsTable->patchEntity($club, $this->request->getData());
            if ($clubsTable->save($club)) {
                $this->Flash->success(__('Le club a été mise à jour.'));
                return $this->redirect(['controller'=>'Config','action' => 'index']);
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }

        $this->set(compact('club'));
    }

    public function deleteclub($id) /**supprimer un club */
    {  
        $clubsTable = $this->fetchTable('EdcClubs');
          
        $this->request->allowMethod(['post', 'delete']);

        $club = $clubsTable->findById($id)->firstOrFail();
        if ($clubsTable->delete($club)) {
            $this->Flash->success(__('Effacé'));
            return $this->redirect(['controller'=>'Config','action' => 'index']);
        }
    }



    public function addplace() /**ajouter un lieu de stage */
    {  
        $placesTable = $this->fetchTable('EdcCoursePlaces');
          
        $place = $placesTable->newEmptyEntity();
        if ($this->request->is('post')) {
            $place = $placesTable->patchEntity($place, $this->request->getData()); 
            if ($placesTable->save($place)) {   
                $this->Flash->success(__('Lieu de stage enregistré.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'ajouter ce lieu de stage.'));
        }

        $this->set(compact('place'));
    }

    public function editplace($id) /**modifier un lieu de stage */
    {  
        $placesTable = $this->fetchTable('EdcCoursePlaces');
          
        $place = $placesTable
            ->findById($id)
            ->firstOrFail();

        if ($this->request->is(['post','put'])) {
            $placesTable->patchEntity($place, $this->request->getData());
            if ($placesTable->save($place)) {
                $this->Flash->success(__('Le lieu a été mis à jour.'));
                return $this->redirect(['controller'=>'Config','action' => 'index']);
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }

        $this->set(compact('place'));
    }

    public function deleteplace($id)/**supprimer un lieu de stage */
    {  
        $placesTable = $this->fetchTable('EdcCoursePlaces');
          
        $this->request->allowMethod(['post', 'delete']);

        $place = $placesTable->findById($id)->firstOrFail();
        if ($placesTable->delete($place)) {
            $this->Flash->success(__('Effacé'));
            return $this->redirect(['controller'=>'Config','action' => 'index']);
        }
    }



    public function addteacher() /**ajouter un animateur de stage */
    {  
        $teachersTable = $this->fetchTable('EdcTeachers');
          
        $teacher = $teachersTable->newEmptyEntity();
        if ($this->request->is('post')) {
            $teacher = $teachersTable->patchEntity($teacher, $this->request->getData()); 
            if ($teachersTable->save($teacher)) {   
                $this->Flash->success(__('Intervenant enregistré.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'ajouter cet intervenant.'));
        }

        $this->set(compact('teacher'));
    }

    public function editteacher($id)/**modifier un animateur de stage */
    {  
        $teachersTable = $this->fetchTable('EdcTeachers');
          
        $teacher = $teachersTable
            ->findById($id)
            ->firstOrFail();

        if ($this->request->is(['post','put'])) {
            $teachersTable->patchEntity($teacher, $this->request->getData());
            if ($teachersTable->save($teacher)) {
                $this->Flash->success(__('L\'intervenant a été mis à jour.'));
                return $this->redirect(['controller'=>'Config','action' => 'index']);
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }

        $this->set(compact('teacher'));
    }

    public function deleteteacher($id) /**supprimer un animateur de stage */
    {  
        $teachersTable = $this->fetchTable('EdcTeachers');
          
        $this->request->allowMethod(['post', 'delete']);

        $teacher = $teachersTable->findById($id)->firstOrFail();
        if ($teachersTable->delete($teacher)) {
            $this->Flash->success(__('Effacé'));
            return $this->redirect(['controller'=>'Config','action' => 'index']);
        }
    }


    public function addseason()/**ajout d'une nouvelle saison */
    {
        $seasonsTable = $this->fetchTable('EdcSeasons');

        $season = $seasonsTable->newEmptyEntity();
        if ($this->request->is('post')) {
            $season = $seasonsTable->patchEntity($season, $this->request->getData());
            if ($seasonsTable->save($season)) {
                $this->Flash->success(__('Nouvelle saison sportive ajoutée'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossible d\'enregistrer.'));
        }
        $this->set('season', $season);
    }

    public function editseason($id)/**modifier un animateur de stage */
    {  
        $seasonsTable = $this->fetchTable('EdcSeasons');
          
        $season = $seasonsTable
            ->findById($id)
            ->firstOrFail();

        if ($this->request->is(['post','put'])) {
            $seasonsTable->patchEntity($season, $this->request->getData());
            if ($seasonsTable->save($season)) {
                $this->Flash->success(__('La saison a été mise à jour.'));
                return $this->redirect(['controller'=>'Config','action' => 'index']);
            }
            $this->Flash->error(__('Mise à jour impossible'));
        }

        $this->set(compact('season'));
    }

    public function deleteseason($id) /**supprimer un animateur de stage */
    {  
        $seasonsTable = $this->fetchTable('EdcSeasons');
          
        $this->request->allowMethod(['post', 'delete']);
        $season = $seasonsTable->findById($id)->firstOrFail();
        if ($seasonsTable->delete($season)) {
            $this->Flash->success(__('Effacé'));
            return $this->redirect(['controller'=>'Config','action' => 'index']);
        }
    }
}