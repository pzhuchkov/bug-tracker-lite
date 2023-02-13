<?php

namespace App\Test\TestCase\View\Helper;

use App\View\Helper\SortQueryHelper;
use Cake\Http\ServerRequestFactory;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * App\View\Helper\SortQueryHelper Test Case
 */
class SortQueryHelperTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->SortQuery);

        parent::tearDown();
    }

    /**
     * Test build url
     *
     * @return void
     */
    public function testBuildUrl()
    {
        $view = new View();
        $sortQuery = new SortQueryHelper($view, [], ServerRequestFactory::fromGlobals());
        $this->assertEquals('/?order%5Btitle%5D=DESC', $sortQuery->buildUrl('title'));
    }

    /**
     * Test build url
     *
     * @return void
     */
    public function testBuildUrlWithExistCurrentOrder()
    {
        $view = new View();
        $sortQuery = new SortQueryHelper(
            $view,
            [],
            ServerRequestFactory::fromGlobals(
                null,
                ['order' => ['title' => 'DESC']]
            )
        );
        $this->assertEquals('/?order%5Btitle%5D=ASC', $sortQuery->buildUrl('title'));
    }

    /**
     * Test build url
     *
     * @return void
     */
    public function testBuildUrlWithExistOrder()
    {
        $view = new View();
        $sortQuery = new SortQueryHelper(
            $view,
            [],
            ServerRequestFactory::fromGlobals(
                null,
                ['sort' => ['created' => 'DESC']]
            )
        );
        $this->assertEquals('/?sort%5Bcreated%5D=DESC&order%5Btitle%5D=DESC', $sortQuery->buildUrl('title'));
    }
}
