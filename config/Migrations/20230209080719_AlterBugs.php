<?php

use Migrations\AbstractMigration;

class AlterBugs extends AbstractMigration
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
        $this->execute('alter table bugs change createAt created datetime not null;');
        $this->execute('alter table bugs change updateAt modified datetime null;');
    }
}
