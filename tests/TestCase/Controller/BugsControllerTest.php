<?php

namespace App\Test\TestCase\Controller;

use App\Model\Entity\Bug;
use Cake\Datasource\EntityInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\BugsController Test Case
 *
 * @uses \App\Controller\BugsController
 */
class BugsControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var array<string, string> Данные для отправки в форму
     */
    protected array $_testBugData = [
        'title'       => 'UnitTest Bug title',
        'assigned_id' => 1,
        'type'        => 2,
        'status'      => 2,
        'description' => 'UnitTest Description',
        'comment'     => 'UnitTest Comment',
        'author'      => 'unit-test-author@example.com',
        'assigned'    => 'unit-test-author@example.com',
    ];

    /**
     * Fixtures
     *
     * @var array|string[]
     */
    public $fixtures = [
        'app.Bugs',
        'app.Users',
    ];

    /**
     * Авторизация
     *
     * @param int $userId Идентификатор пользователя, под которым проходит авторизация
     *
     * @return void
     */
    protected function _login(int $userId = 1): void
    {
        $users = TableRegistry::getTableLocator()->get('Users');
        $user = $users->get($userId);
        $this->session(['Auth' => $user]);
    }


    /**
     * Установка CSRF токена, для отправки формы
     *
     * @return void
     */
    protected function _mockCsrf(): void
    {
        $token = 'my-csrf-token';
        $this->cookie('csrfToken', $token);

        $this->configRequest([
            'headers' => [
                'X-CSRF-Token' => $token,
            ],
        ]);
    }

    /**
     * Проверка index без авторизации
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testIndexWithoutLogin(): void
    {
        $this->get('/bugs');
        $this->assertRedirectContains('/login');
    }

    /**
     * Проверка index с авторизацией
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testIndexWithLogin(): void
    {
        $this->_login();
        $this->get('/bugs');
        $this->assertResponseOk();
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Logout');
        $this->assertResponseContains('Id');
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    /**
     * Проверка view без авторизации
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testViewWithoutLogin(): void
    {
        $this->get('/bugs');
        $this->assertRedirectContains('/login');
    }

    /**
     * Проверка view с авторизацией
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testView(): void
    {
        $user = TableRegistry::getTableLocator()->get('Users')->get(1);

        $this->_login();
        $this->get('/bugs/view/' . $user->id);
        $this->assertResponseOk();
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Logout');
        $this->assertResponseContains('Id');
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    /**
     * Проверка добавления бага без авторизации
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAddWithoutLogin(): void
    {
        $this->get('/bugs/add');
        $this->assertRedirectContains('/login');
    }

    /**
     * Проверка добавления бага с авторизацией
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testAdd(): void
    {
        $this->_login();

        $this->get('/bugs/add');
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Title');
        $this->assertResponseContains('Assigned');
        $this->assertResponseContains('Type');
        $this->assertResponseContains('Status');
        $this->assertResponseContains('Description');
        $this->assertResponseContains('Comment');

        $this->_mockCsrf();

        $this->post('/bugs/add', $this->_testBugData);
        $this->assertRedirect('/bugs');
        $bugs = TableRegistry::getTableLocator()->get('Bugs');
        $query = $bugs->find()->where(['title' => $this->_testBugData['title']]);
        $this->assertEquals(1, $query->count());

        $this->_checkBugData($query->first());

        $this->get('/bugs');
        $this->assertResponseOk();
        $this->assertResponseContains($this->_testBugData['title']);
    }

    /**
     * Проверка редактирования бага без авторизации
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testEditWithoutLogin(): void
    {
        $this->get('/bugs/edit/1');
        $this->assertRedirectContains('/login');
    }

    /**
     * Проверка редактирования бага с авторизацией, но несуществующей записи
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testEditWithWrongEntity(): void
    {
        $this->_login();
        $this->get('/bugs/edit/1000');
        $this->assertResponseCode(404);
    }

    /**
     * Проверка редактирования бага с авторизацией
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testEdit(): void
    {
        $this->_login();
        $this->get('/bugs/edit/1');
        $this->assertResponseOk();

        $this->_mockCsrf();

        $this->post('/bugs/edit/1', $this->_testBugData);
        $this->assertRedirect('/bugs');
        $bugs = TableRegistry::getTableLocator()->get('Bugs');
        $query = $bugs->find()->where(['title' => $this->_testBugData['title']]);
        $this->assertEquals(1, $query->count());

        $this->_checkBugData($query->first());

        $this->get('/bugs');
        $this->assertResponseOk();
        $this->assertResponseContains($this->_testBugData['title']);
    }


    /**
     * Проверка редактирования бага с авторизацией под пользователем на которого назначена бага
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testEditAssigned(): void
    {
        $this->_login();
        $mockUser = [
            'email'    => 'new@user.com',
            'password' => 'test',
        ];
        $this->_mockCsrf();
        $this->post('/users/add', $mockUser);
        $this->assertRedirect('/users');
        $users = TableRegistry::getTableLocator()->get('Users');
        $query = $users->find()->where(['email' => $mockUser['email']]);
        $this->assertEquals(1, $query->count());

        $this->_mockCsrf();
        $newTestBugData = $this->_testBugData;
        $newTestBugData['assigned_id'] = 2;
        $newTestBugData['assigned'] = $mockUser['email'];
        $this->post('/bugs/edit/1', $newTestBugData);
        $this->assertRedirect('/bugs');
        $bugs = TableRegistry::getTableLocator()->get('Bugs');
        $query = $bugs->find()->where(['title' => $this->_testBugData['title']]);
        $this->assertEquals(1, $query->count());

        $this->_checkBugData($query->first(), $newTestBugData);

        $this->_login(2);
        $this->get('/bugs/edit/1');
        $this->assertResponseOk();

        $this->_mockCsrf();
        $newTestBugData['title'] = 'Last check';
        $this->post('/bugs/edit/1', $newTestBugData);
        $this->assertRedirect('/bugs');
        $bugs = TableRegistry::getTableLocator()->get('Bugs');
        $query = $bugs->find()->where(['title' => $newTestBugData['title']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * Проверка удаление бага без авторизации
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testDeleteWithoutLogin(): void
    {
        $this->_mockCsrf();
        $this->post('/bugs/delete/1');
        $this->assertRedirectContains('/login');
    }

    /**
     * Проверка удаление бага с авторизацией, но не автором бага
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testDeleteWithWrongRights(): void
    {
        $this->_login();
        $this->_mockCsrf();
        $this->post('/bugs/delete/1000');
        $this->assertResponseCode(404);
    }

    /**
     * Проверка удаление бага с авторизацией
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testDelete(): void
    {
        $this->_login();
        $this->_mockCsrf();

        $this->post('/bugs/add', $this->_testBugData);
        $this->assertRedirect('/bugs');
        $bugs = TableRegistry::getTableLocator()->get('Bugs');
        $query = $bugs->find()->where(['title' => $this->_testBugData['title']]);
        $this->assertEquals(1, $query->count());


        $this->_mockCsrf();
        $this->post('/bugs/delete/2');
        $this->assertRedirectContains('/bugs');

        $query = $bugs->find()->where(['title' => $this->_testBugData['title']]);
        $this->assertEquals(0, $query->count());
    }

    /**
     * Проверка index с авторизацией и фильтрацией по типу
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testIndexFilterType(): void
    {
        $this->_login();
        $this->_mockCsrf();

        $this->post('/bugs/add', $this->_testBugData);
        $this->assertRedirectContains('/bugs');
        $bugs = TableRegistry::getTableLocator()->get('Bugs');
        $query = $bugs->find()->where(['title' => $this->_testBugData['title']]);
        $this->assertEquals(1, $query->count());

        $this->get('/bugs');
        $this->assertResponseOk();
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
        $this->assertResponseContains($this->_testBugData['title']);

        $this->get('/bugs?type=' . 2);
        $this->assertResponseOk();
        $this->assertResponseContains($this->_testBugData['title']);
    }

    /**
     * Проверка index с авторизацией и фильтрацией по датам
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testIndexFilterDate(): void
    {
        $this->_login();
        $this->_mockCsrf();

        $this->post('/bugs/add', $this->_testBugData);
        $this->assertRedirectContains('/bugs');
        $bugs = TableRegistry::getTableLocator()->get('Bugs');
        /** @var Bug $bug */
        $bug = $bugs->get(2);
        $year = date('Y') + 1;
        $bug->created = FrozenTime::createFromDate($year);
        $bugs->save($bug);

        $this->get('/bugs?from-date=' . $bug->created->format('Y.m.d H:i:s'));
        $this->assertResponseOk();
        $this->assertResponseContains($this->_testBugData['title']);
        $this->assertResponseContains(Bug::getTypeList()[$this->_testBugData['type']]);
        $this->assertResponseContains(Bug::getStatusList()[$this->_testBugData['status']]);

        /** @var Bug $bug */
        $bug = $bugs->get(1);

        $this->get(
            sprintf(
                '/bugs?from-date=%s&to-date=%s',
                $bug->created->modify('-1 hour')->format('Y.m.d H:i:s'),
                $bug->created->modify('+1 hour')->format('Y.m.d H:i:s')
            )
        );
        $this->assertResponseOk();
        $this->assertResponseContains('Lorem ipsum dolor sit amet');
    }

    /**
     * Проверка полей бага
     *
     * @param Bug|EntityInterface $bug  проверяемый баг
     * @param array|null          $data данные для проверки
     *
     * @return void
     */
    protected function _checkBugData(Bug $bug, array $data = null): void
    {
        if (is_null($data) === true) {
            $data = $this->_testBugData;
        }

        $this->assertEquals($bug->type, $data['type']);
        $this->assertEquals($bug->status, $data['status']);
        $this->assertEquals($bug->description, $data['description']);
        $this->assertEquals($bug->comment, $data['comment']);
        $this->assertEquals($bug->assigned_id, $data['assigned_id']);
        $this->assertNotEmpty($bug->created);
        $this->assertNotEmpty($bug->modified);
    }
}
