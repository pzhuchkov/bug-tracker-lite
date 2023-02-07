<?php

namespace App\Test\TestCase\View\Helper;

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
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
