<?php

namespace App\Test\TestCase\Controller;

use App\Controller\BugsController;
use App\Model\Entity\Bug;
use Cake\ORM\Locator\TableLocator;
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
     * @var array Данные для отправки в форму
     */
    protected array $_testBugData = [
        'title'       => 'UnitTest Bug title',
        'assigned_id' => 1,
        'type'        => 1,
        'status'      => 1,
        'description' => 'UnitTest Description',
        'comment'     => 'UnitTest Comment',
    ];

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Bugs',
        'app.Users',
    ];

    /**
     * Авторизация
     *
     * @param int $userId
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
     * Мокаем ключик
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
     * Test index method without login
     *
     * @return void
     */
    public function testIndexWithoutLogin(): void
    {
        $this->get('/bugs');
        $this->assertRedirectContains('/login');
    }

    /**
     * Test index method
     *
     * @return void
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
     * Test view method without login
     *
     * @return void
     */
    public function testViewWithoutLogin(): void
    {
        $this->get('/bugs');
        $this->assertRedirectContains('/login');
    }

    /**
     * Test view method
     *
     * @return void
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
     * Test add method
     *
     * @return void
     */
    public function testAddWithoutLogin()
    {
        $this->get('/bugs/add');
        $this->assertRedirectContains('/login');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
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

        $this->get('/bugs');
        $this->assertResponseOk();
        $this->assertResponseContains($this->_testBugData['title']);
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEditWithoutLogin()
    {
        $this->get('/bugs/view/1');
        $this->assertRedirectContains('/login');
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->_login();
        $this->get('/bugs/view/1');
        $this->assertResponseOk();
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
