<?php
declare(strict_types=1);

namespace App\Controller;

use App\Model\Table\UsersTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Http\Response;

/**
 * Users Controller
 *
 * @property UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * @param Event $event
     *
     * @return Response|void|null
     */
    public function beforeFilter(Event $event): ?Response
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(
            [
                'view',
                'index',
                'login',
                'display',
                'add',
            ]
        );

        $this->set('isAuth', $this->Authentication->getResult()->isValid());

        return null;
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index(): void
    {
        $this->Authorization->skipAuthorization();
        $users = $this->paginate($this->Users);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     *
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function view(string $id = null): void
    {
        $this->Authorization->skipAuthorization();
        $user = $this->Users->get($id, ['contain' => [],]);
        $this->set('user', $user);
    }

    /**
     * Add method
     *
     * @return Response|null Redirects on successful add, renders view otherwise.
     */
    public function add(): ?Response
    {
        $this->Authorization->skipAuthorization();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));

        return null;
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     *
     * @return Response|null Redirects on successful edit, renders view otherwise.
     * @throws RecordNotFoundException When record not found.
     */
    public function edit(string $id = null): ?Response
    {
        $this->Authorization->skipAuthorization();
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The user could not be saved. Please, try again.'));
        }
        $this->set(compact('user'));

        return null;
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     *
     * @return Response|null Redirects to index.
     * @throws RecordNotFoundException When record not found.
     */
    public function delete(string $id = null): ?Response
    {
        $this->Authorization->skipAuthorization();
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Login action
     *
     * @return Response|void|null
     */
    public function login(): ?Response
    {
        $this->Authorization->skipAuthorization();

        $result = $this->Authentication->getResult();

        if ($result->isValid()) {
            $target = $this->Authentication->getLoginRedirect() ?? '/bugs';
            return $this->redirect($target);
        }

        if ($this->request->is('post') && !$result->isValid()) {
            $this->Flash->error('Invalid username or password');
        }

        return null;
    }

    /**
     * Logout action
     *
     * @return Response|null
     */
    public function logout(): ?Response
    {
        $this->Authorization->skipAuthorization();

        $this->Flash->success('You are now logged out.');
        $this->Authentication->logout();

        return $this->redirect(['controller' => 'Users', 'action' => 'login']);
    }
}
