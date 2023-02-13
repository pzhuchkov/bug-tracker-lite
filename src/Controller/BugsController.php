<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Entity\Bug;
use App\Model\Table\BugsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Response;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

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
     * @var bool[] Список полей доступных для редактирования
     */
    protected $allowedFields = [
        'title'       => true,
        'description' => true,
        'comment'     => true,
        'type'        => true,
        'status'      => true,
        'assigned_id' => true,
    ];

    /**
     * @param Event $event
     *
     * @return void
     */
    public function beforeFilter(Event $event): void
    {
        $checkRoutes = [
            'edit',
            'add',
        ];
        if (in_array($this->request->getParam('action'), $checkRoutes) !== true) {
            return;
        }

        if (count($this->request->getData()) === 0) {
            return;
        }

        $data = $this->request->getData();

        foreach ($data as $name => $value) {
            if (array_key_exists($name, $this->allowedFields) !== true) {
                unset($data[$name]);
            }
        }

        $this->request = $this->request->withParsedBody($data);
    }

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
                    'Bugs.type' => $this->request->getQuery('type'),
                ]
            );
        }
        if ($this->request->getQuery('from-date')) {
            $query = $query->where(
                [
                    'Bugs.created >=' => $this->request->getQuery('from-date'),
                ]
            );
        }
        if ($this->request->getQuery('to-date')) {
            $query = $query->where(
                [
                    'Bugs.created <=' => $this->request->getQuery('to-date'),
                ]
            );
        }

        $query->contain('AuthorUser')->contain('AssignedUser');

        $order = $this->request->getQuery('order');

        if (is_array($order)) {
            krsort($order);
        }

        $bugs = $this->paginate(
            $query,
            [
                'order' => $order,
            ]
        );

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

            $authorizedUser = TableRegistry::getTableLocator()->get('Users')->get($user->id);
            $bug->AuthorUser = $authorizedUser;

            if ($bug->has('assigned_id')) {
                $bug->AssignedUser = $this->Bugs->AssignedUser->get($bug->assigned_id);
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

        $this->set('users', TableRegistry::getTableLocator()->get('Users')->query()->find('list'));
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
                $bug->AssignedUser = $this->Bugs->AssignedUser->get($bug->assigned_id);
            }

            if ($this->Bugs->save($bug)) {
                $this->Flash->success(__('The bug has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The bug could not be saved. Please, try again.'));
        }

        $this->set('users', TableRegistry::getTableLocator()->get('Users')->query()->find('list'));
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
