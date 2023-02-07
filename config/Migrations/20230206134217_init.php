<?php

use Migrations\AbstractMigration;

class Init extends AbstractMigration
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
        $this->execute('create table bugs
(
    id          int auto_increment,
    title       varchar(256)  not null,
    description varchar(1000) null,
    comment     text          null,
    type        int           not null,
    author      varchar(256)  null,
    assigned    varchar(256)  null,
    status      int           null,
    createAt    datetime      not null,
    updateAt    datetime      null,
    constraint bugs_pk
        primary key (id)
);

create unique index bugs_id_uindex
    on bugs (id);

');
        $this->execute('CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    created DATETIME,
    modified DATETIME
);
');
    }
}
