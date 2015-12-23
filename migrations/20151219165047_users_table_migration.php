<?php

use Phinx\Migration\AbstractMigration;

class UsersTableMigration extends AbstractMigration
{
    public function up()
    {
        $table = $this->table('users');
        $table->addColumn('name', 'string')
            ->addColumn('email', 'string')
            ->addColumn('password', 'string')
            ->addColumn('timezone', 'string')
            ->addColumn('country', 'string')
            ->addColumn('created', 'datetime')
            ->addIndex(['email'], ['unique' => true])
            ->create();

    }

    public function down()
    {

    }
}
