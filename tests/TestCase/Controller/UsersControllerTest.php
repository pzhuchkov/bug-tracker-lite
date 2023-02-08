<?php

namespace App\Test\TestCase\Controller;

use App\Controller\UsersController;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

/**
 * App\Controller\UsersController Test Case
 *
 * @uses \App\Controller\UsersController
 */
class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.Users',
    ];

    protected $_testUserData = [
        'email'    => 'unit-test@example.com',
        'password' => 'unit-test-password',
    ];

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
     * Test index method
     *
     * @return void
     */
    public function testIndex()
    {
        $this->get('/users');
        $this->assertResponseOk();
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Email');
        $this->assertResponseContains('Lorem');
    }

    /**
     * Test view method
     *
     * @return void
     */
    public function testView()
    {
        $this->get('/users/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Email');
        $this->assertResponseContains('Lorem');
    }

    /**
     * Test add method
     *
     * @return void
     */
    public function testAdd()
    {
        $this->get('/users/add');
        $this->assertResponseOk();
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Add User');
        $this->assertResponseContains('Email');
        $this->assertResponseContains('Password');

        $this->_mockCsrf();

        $this->post('/users/add', $this->_testUserData);

        $this->assertRedirect('/users');
        $articles = TableRegistry::getTableLocator()->get('Users');
        $query = $articles->find()->where(['email' => $this->_testUserData['email']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * Test edit method
     *
     * @return void
     */
    public function testEdit()
    {
        $this->get('/users/edit/1');
        $this->assertRedirectContains('/login');

        $this->_mockCsrf();
        $this->post('/users/add', $this->_testUserData);
        $this->assertRedirect('/users');
        $articles = TableRegistry::getTableLocator()->get('Users');
        $query = $articles->find()->where(['email' => $this->_testUserData['email']]);
        $this->assertEquals(1, $query->count());

        $this->_mockCsrf();
        $this->post('/users/login?redirect=%2Fusers%2Fedit%2F2', $this->_testUserData);

        $this->assertRedirectContains('/users/edit/2');
    }

    /**
     * Test delete method
     *
     * @return void
     */
    public function testDelete()
    {
        $this->get('/users/edit/1');
        $this->assertRedirectContains('/login');
    }
}
