<?php

use Migrations\AbstractMigration;

class Link extends AbstractMigration
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
        $this->execute('alter table bugs add author_id int not null;');
        $this->execute('alter table bugs add assigned_id int null;');
        $this->execute('alter table bugs add constraint bugs_users_id_fk foreign key (assigned_id) references users (id);');
        $this->execute('alter table bugs add constraint bugs_users_id_fk_2 foreign key (author_id) references users (id);');
    }
}
