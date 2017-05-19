<?php

class m170517_215534_add_date_column_to_tbl_user extends CDbMigration
{
	public function up()
	{
	    $this->addColumn('tbl_user', 'last_password_change', 'datetime');
	    $this->addColumn('tbl_user', 'password_change_force', 'tinyint(1)');
	}

	public function down()
	{
		echo "m170517_215534_add_date_column_to_tbl_user does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}