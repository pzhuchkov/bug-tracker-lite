<?php

namespace App\Test\TestCase\View\Helper;

use App\Model\Entity\Bug;
use App\View\Helper\BugsDataHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\BugsDataHelper Test Case
 */
class BugsDataHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \App\View\Helper\BugsDataHelper
     */
    public $BugsData;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->BugsData = new BugsDataHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->BugsData);

        parent::tearDown();
    }

    /**
     * Тест получение кривого статуса
     *
     * @return void
     */
    public function testGetStatusByIdWrong(): void
    {
        $this->assertTextEquals(
            'Unknown',
            $this->BugsData->getStatusById(-1)
        );
    }

    /**
     * Тест получение правильного статуса
     *
     * @return void
     */
    public function testGetStatusById(): void
    {
        $this->assertTextEquals(
            current(Bug::getStatusList()),
            $this->BugsData->getStatusById(1)
        );
    }

    /**
     * Тест получение кривого типа
     *
     * @return void
     */
    public function testGetTypeByIdWrong(): void
    {
        $this->assertTextEquals(
            'Unknown',
            $this->BugsData->getTypeById(-1)
        );
    }

    /**
     * Тест получение правильного типа
     *
     * @return void
     */
    public function testGetTypeById(): void
    {
        $this->assertTextEquals(
            current(Bug::getTypeList()),
            $this->BugsData->getTypeById(1)
        );
    }
}
