<?php

namespace App\Controller;

use App\Model\Entity\Bug;
use App\Model\Table\BugsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Http\Response;
use Cake\I18n\FrozenTime;

/**
 * Bugs Controller
 *
 * @property BugsTable $Bugs
 *
 * @method \App\Model\Entity\Bug[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class BugsController extends AppController
{
    /**
     * Index method
     *
     * @return Response|null
     */
    public function index(): ?Response
    {
        $this->Authorization->skipAuthorization();

        $query = $this->Bugs->find();
        if ($this->request->getQuery('type')) {
            $query = $query->where(
                [
                    'type' => $this->request->getQuery('type'),
                ]
            );
        }
        if ($this->request->getQuery('from-date')) {
            $query = $query->where(
                [
                    'created >' => $this->request->getQuery('from-date'),
                ]
            );
        }
        if ($this->request->getQuery('to-date')) {
            $query = $query->where(
                [
                    'created <' => $this->request->getQuery('to-date'),
                ]
            );
        }

        $bugs = $this->paginate($query);

        $this->set('typeList', array_merge_recursive([0 => ''], Bug::getTypeList()));
        $this->set(compact('bugs'));

        return null;
    }

    /**
     * View method
     *
     * @param string|null $id Bug id.
     *
     * @return Response|null
     * @throws RecordNotFoundException When record not found.
     */
    public function view(string $id = null): ?Response
    {
        $this->Authorization->skipAuthorization();
        $bug = $this->Bugs->get($id, [
            'contain' => [],
        ]);

        $this->set('bug', $bug);

        return null;
    }

    /**
     * Add method
     *
     * @return Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(): ?Response
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

        $this->set('users', $this->Bugs->Users->find('list')->toArray());
        $this->set('typeList', Bug::getTypeList());
        $this->set('statusList', Bug::getStatusList());

        $this->set(compact('bug'));

        return null;
    }

    /**
     * Edit method
     *
     * @param string|null $id Bug id.
     *
     * @return Response|null Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(string $id = null): ?Response
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

        return null;
    }

    /**
     * Delete method
     *
     * @param string|null $id Bug id.
     *
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete(string $id = null): ?Response
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
