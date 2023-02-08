<?php

namespace App\Controller;

use App\Controller\AppController;
use App\Model\Entity\Bug;
use Cake\I18n\FrozenTime;

/**
 * Bugs Controller
 *
 * @property \App\Model\Table\BugsTable $Bugs
 *
 * @method \App\Model\Entity\Bug[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BugsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $this->Authorization->skipAuthorization();
        $bugs = $this->paginate($this->Bugs);

        $this->set(compact('bugs'));
    }

    /**
     * View method
     *
     * @param string|null $id Bug id.
     *
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $this->Authorization->skipAuthorization();
        $bug = $this->Bugs->get($id, [
            'contain' => [],
        ]);

        $this->set('bug', $bug);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $bug = $this->Bugs->newEntity();

        if ($this->Authorization->can($bug, 'create') === false) {
            $this->Flash->error(__('Access denied.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is('post')) {
            $bug = $this->Bugs->patchEntity($bug, $this->request->getData());

            $user = $this->Authentication->getIdentity();

            $bug->author = $user->email;
            $bug->author_id = $user->id;

            $bug->createAt = FrozenTime::create();

            if ($bug->has('assigned_id')) {
                $bug->assigned = $this->Bugs->Users->get($bug->assigned_id)->email;
            }

            if ($this->Bugs->save($bug)) {
                $this->Flash->success(__('The bug has been saved.'));

                return $this->redirect(['action' => 'index']);
            }

            $errorsMessage = '';

            if ($bug->hasErrors()) {
                foreach ($bug->getErrors() as $fieldName => $errors) {
                    foreach ($errors as $type => $message) {
                        $errorsMessage .= $fieldName . ' - ' . $message . '. ';
                    }
                }
            }

            $this->Flash->error(__('The bug could not be saved. Please, try again. ' . trim($errorsMessage)));

        }

        $users = $this->Bugs->Users->find('list')->toArray();

        $this->set('users', $users);
        $this->set('typeList', Bug::getTypeList());
        $this->set('statusList', Bug::getStatusList());

        $this->set(compact('bug'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Bug id.
     *
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $bug = $this->Bugs->get($id, [
            'contain' => [],
        ]);

        if ($this->Authorization->can($bug, 'update') === false) {
            $this->Flash->error(__('Only author or assigned user access to modify.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $bug = $this->Bugs->patchEntity($bug, $this->request->getData());

            $bug->updateAt = FrozenTime::create();

            if ($bug->has('assigned_id')) {
                $bug->assigned = $this->Bugs->Users->get($bug->assigned_id)->email;
            }

            if ($this->Bugs->save($bug)) {
                $this->Flash->success(__('The bug has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bug could not be saved. Please, try again.'));
        }

        $users = $this->Bugs->Users->find('list')->toArray();

        $this->set('users', $users);
        $this->set('typeList', Bug::getTypeList());
        $this->set('statusList', Bug::getStatusList());

        $this->set(compact('bug'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Bug id.
     *
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $bug = $this->Bugs->get($id);

        if ($this->Authorization->can($bug, 'delete') === false) {
            $this->Flash->error(__('Only author or assigned user access to modify.'));
            return $this->redirect(['action' => 'index']);
        }

        if ($this->Bugs->delete($bug)) {
            $this->Flash->success(__('The bug has been deleted.'));
        } else {
            $this->Flash->error(__('The bug could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
