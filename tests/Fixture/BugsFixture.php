<?php

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * BugsFixture
 */
class BugsFixture extends TestFixture
{
    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id'           => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'title'        => ['type' => 'string', 'length' => 256, 'null' => false, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'description'  => ['type' => 'string', 'length' => 1000, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'comment'      => ['type' => 'text', 'length' => null, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null],
        'type'         => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'author'       => ['type' => 'string', 'length' => 256, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'assigned'     => ['type' => 'string', 'length' => 256, 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'status'       => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'createAt'     => ['type' => 'datetime', 'length' => null, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null],
        'updateAt'     => ['type' => 'datetime', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'author_id'    => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'assigned_id'  => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        '_indexes'     => [
            'bugs_users_id_fk'   => ['type' => 'index', 'columns' => ['assigned_id'], 'length' => []],
            'bugs_users_id_fk_2' => ['type' => 'index', 'columns' => ['author_id'], 'length' => []],
        ],
        '_constraints' => [
            'primary'            => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'bugs_id_uindex'     => ['type' => 'unique', 'columns' => ['id'], 'length' => []],
            'bugs_users_id_fk'   => ['type' => 'foreign', 'columns' => ['assigned_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'bugs_users_id_fk_2' => ['type' => 'foreign', 'columns' => ['author_id'], 'references' => ['users', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
        ],
        '_options'     => [
            'engine'    => 'InnoDB',
            'collation' => 'latin1_swedish_ci',
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Init method
     *
     * @return void
     */
    public function init()
    {
        $this->records = [
            [
                'id'          => 1,
                'title'       => 'Lorem ipsum dolor sit amet',
                'description' => 'Lorem ipsum dolor sit amet',
                'comment'     => 'Lorem ipsum dolor sit amet, aliquet feugiat. Convallis morbi fringilla gravida, phasellus feugiat dapibus velit nunc, pulvinar eget sollicitudin venenatis cum nullam, vivamus ut a sed, mollitia lectus. Nulla vestibulum massa neque ut et, id hendrerit sit, feugiat in taciti enim proin nibh, tempor dignissim, rhoncus duis vestibulum nunc mattis convallis.',
                'type'        => 1,
                'author'      => 'Lorem ipsum dolor sit amet',
                'assigned'    => 'Lorem ipsum dolor sit amet',
                'status'      => 1,
                'createAt'    => '2023-02-06 16:39:02',
                'updateAt'    => '2023-02-06 16:39:02',
                'author_id'   => 1,
                'assigned_id' => 1,
            ],
        ];
        parent::init();
    }
}
