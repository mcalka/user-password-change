<?php

class m170518_224443_add_password_history_table extends CDbMigration
{
	public function up()
	{
	    $transaction = $this->dbConnection->beginTransaction();
	    try {
            $this->createTable('tbl_user_passwords_history', [
                'id' => 'pk',
                'user_id' => 'INT(11)',
                'password' => 'VARCHAR(128)'
            ]);
            $this->addForeignKey(
                'password_history_2_user',
                'tbl_user_passwords_history',
                'user_id',
                'tbl_user',
                'id',
                'cascade',
                'cascade'
            );
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollback();
        }
	}

	public function down()
	{
		echo "m170518_224443_add_password_history_table does not support migration down.\n";
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