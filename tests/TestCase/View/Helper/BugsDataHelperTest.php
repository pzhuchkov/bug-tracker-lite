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
    public function setUp()
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
    public function tearDown()
    {
        unset($this->BugsData);

        parent::tearDown();
    }

    /**
     * Test method getStatusById with wrong data
     *
     * @return void
     */
    public function testGetStatusByIdWrong()
    {
        $this->assertTextEquals(
            'Unknown',
            $this->BugsData->getStatusById(-1)
        );
    }

    /**
     * Test method getStatusById
     *
     * @return void
     */
    public function testGetStatusById()
    {
        $this->assertTextEquals(
            current(Bug::getStatusList()),
            $this->BugsData->getStatusById(1)
        );
    }

    /**
     * Test method getTypeById with wrong data
     *
     * @return void
     */
    public function testGetTypeByIdWrong()
    {
        $this->assertTextEquals(
            'Unknown',
            $this->BugsData->getTypeById(-1)
        );
    }

    /**
     * Test method getTypeById data
     *
     * @return void
     */
    public function testGetTypeById()
    {
        $this->assertTextEquals(
            current(Bug::getTypeList()),
            $this->BugsData->getTypeById(1)
        );
    }
}
