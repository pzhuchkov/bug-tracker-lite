<?php

namespace App\Test\TestCase\Controller;

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
     * @var array|string[] Данные для отправки запросов
     */
    protected array $_testUserData = [
        'email'    => 'unit-test@example.com',
        'password' => 'unit-test-password',
    ];

    /**
     * Fixtures
     *
     * @var array|string[]
     */
    public $fixtures = [
        'app.Users',
    ];

    /**
     * Мокаем ключик для отправки формы
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
     * Проверка index
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testIndex(): void
    {
        $this->get('/users');
        $this->assertResponseOk();
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Email');
        $this->assertResponseContains('unit-test-author@example.com');
    }

    /**
     * Проверка view
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testView(): void
    {
        $this->get('/users/view/1');
        $this->assertResponseOk();
        $this->assertResponseContains('Bugs');
        $this->assertResponseContains('Users');
        $this->assertResponseContains('Email');
        $this->assertResponseContains('Lorem');
    }

    /**
     * Проверка добавление пользователя
     *
     * @return void
     * @throws \PHPUnit\Exception
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
        $users = TableRegistry::getTableLocator()->get('Users');
        $query = $users->find()->where(['email' => $this->_testUserData['email']]);
        $this->assertEquals(1, $query->count());
    }

    /**
     * Проверка редактирования пользователя
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testEdit(): void
    {
        $this->get('/users/edit/1');
        $this->assertRedirectContains('/login');

        $this->_mockCsrf();
        $this->post('/users/add', $this->_testUserData);
        $this->assertRedirect('/users');
        $users = TableRegistry::getTableLocator()->get('Users');
        $query = $users->find()->where(['email' => $this->_testUserData['email']]);
        $this->assertEquals(1, $query->count());

        $this->_mockCsrf();
        $this->post('/users/login?redirect=%2Fusers%2Fedit%2F2', $this->_testUserData);

        $this->assertRedirectContains('/users/edit/2');
    }

    /**
     * Проверка удаления пользователя
     *
     * @return void
     * @throws \PHPUnit\Exception
     */
    public function testDelete(): void
    {
        $this->_mockCsrf();
        $this->post('/users/delete/1');
        $this->assertRedirectContains('/login');
    }
}
