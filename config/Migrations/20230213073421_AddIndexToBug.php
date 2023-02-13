<?php

use Migrations\AbstractMigration;

class AddIndexToBug extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * @return void
     */
    public function change()
    {
        $this->execute('create index bugs_created_type_index on bugs (created desc, type asc);');
        $this->execute('create index bugs_type_index on bugs (type);');
    }
}
