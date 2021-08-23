<?php

namespace eflima\control_panel\migrations;

use Yii;
use yii\db\Migration;

/**
 * Class M210518075344Admin
 */
class M210518075344Admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%administrator}}', [
            'id' => $this->primaryKey(10)->unsigned()->notNull(),
            'uuid' => $this->char(36)->unique()->notNull(),
            'account_id' => $this->integer(10)->unsigned()->notNull(),
            'name' => $this->text()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'account_of_admin',
            '{{%administrator}}', 'account_id',
            '{{%account}}', 'id',
            'CASCADE'
        );

        $this->registerRootAdministrator();
    }


    public function registerRootAdministrator()
    {
        $this->insert('{{%account}}', [
            'username' => 'rifan',
            'uuid' => '5b208e43-7a03-4353-b140-b489f2285853',
            'email' => 'rifan@gmail.com',
            'phone' => '098765789',
            'password' => Yii::$app->getSecurity()->generatePasswordHash('rifan123'),
            'type' => 'administrator',
            'is_system' => true,
            'auth_token' => Yii::$app->getSecurity()->generateRandomString(64),
            'registered_at' => time(),
        ]);

        $accountId = $this->getDb()->getLastInsertID();

        $this->insert('{{%administrator}}', [
            'uuid' => 'f20499ab-56ec-4ddf-a1a6-ebe7020940c8',
            'name' => 'Rifan Firdhaus',
            'account_id' => $accountId,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('account_of_admin', '{{%administrator}}');

        $this->dropTable('{{%administrator}}');
    }
}
