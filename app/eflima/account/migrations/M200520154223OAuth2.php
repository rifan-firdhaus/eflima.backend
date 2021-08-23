<?php

namespace eflima\account\migrations;

use yii\db\Migration;

/**
 * Class M200520154223OAuth2
 */
class M200520154223OAuth2 extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->getDriverName() === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%oauth2_client}}', [
            'id' => $this->string(64)->notNull(),
            'client_secret' => $this->string(64)->notNull(),
            'is_public' => $this->boolean()->defaultValue(false)->notNull(),
            'last_activity_at' => $this->integer(10)->unsigned()->null(),
            'created_at' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%oauth2_access_token}}', [
            'id' => $this->primaryKey(10)->unsigned(),
            'client_id' => $this->string(64)->notNull(),
            'account_id' => $this->integer(10)->unsigned()->notNull(),
            'token' => $this->text()->notNull(),
            'expiration' => $this->integer(10)->unsigned()->notNull(),
            'is_granted' => $this->boolean()->defaultValue(true)->notNull(),
            'scope' => $this->string(255)->null(),
            'last_activity_at' => $this->integer(10)->unsigned()->null(),
            'created_at' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%oauth2_refresh_token}}', [
            'id' => $this->primaryKey(10)->unsigned(),
            'token' => $this->text()->notNull(),
            'expiration' => $this->integer(10)->unsigned()->notNull(),
            'client_id' => $this->string(64)->notNull(),
            'account_id' => $this->integer(10)->unsigned()->notNull(),
            'is_used' => $this->boolean()->defaultValue(false)->notNull(),
            'created_at' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%oauth2_authorization_code}}', [
            'id' => $this->primaryKey(10)->unsigned(),
            'client_id' => $this->string(64)->notNull(),
            'account_id' => $this->integer(10)->unsigned()->notNull(),
            'code' => $this->text()->notNull(),
            'redirect_uri' => $this->text()->notNull(),
            'is_used' => $this->boolean()->defaultValue(false)->notNull(),
            'scope' => $this->string(255)->null(),
            'expiration' => $this->integer(10)->unsigned()->notNull(),
            'used_at' => $this->integer(10)->unsigned(),
            'created_at' => $this->integer(10)->unsigned()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('client_id', '{{%oauth2_client}}', 'id');

        $this->addForeignKey(
            'client_of_access_token',
            '{{%oauth2_access_token}}', 'client_id',
            '{{%oauth2_client}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'account_of_access_token',
            '{{%oauth2_access_token}}', 'account_id',
            '{{%account}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'client_of_refresh_token',
            '{{%oauth2_refresh_token}}', 'client_id',
            '{{%oauth2_client}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'account_of_refresh_token',
            '{{%oauth2_refresh_token}}', 'account_id',
            '{{%account}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'account_of_authorization_code',
            '{{%oauth2_authorization_code}}', 'account_id',
            '{{%account}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'client_of_authorization_code',
            '{{%oauth2_authorization_code}}', 'client_id',
            '{{%oauth2_client}}', 'id',
            'CASCADE'
        );

        $this->insert('{{%oauth2_client}}', [
            'id' => 'development',
            'client_secret' => 'SDfcUD2UmP966HJmaSTeVLkGLO6HeM2F7aPOpa5DVLkGLO6HeM2F7aPOpa5DCR1',
            'created_at' => time(),
            'is_public' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('client_of_access_token', '{{%oauth2_access_token}}');
        $this->dropForeignKey('account_of_access_token', '{{%oauth2_access_token}}');
        $this->dropForeignKey('client_of_refresh_token', '{{%oauth2_refresh_token}}');
        $this->dropForeignKey('account_of_refresh_token', '{{%oauth2_refresh_token}}');
        $this->dropForeignKey('account_of_authorization_code', '{{%oauth2_authorization_code}}');
        $this->dropForeignKey('client_of_authorization_code', '{{%oauth2_authorization_code}}');

        $this->dropTable('{{%oauth2_access_token}}');
        $this->dropTable('{{%oauth2_refresh_token}}');
        $this->dropTable('{{%oauth2_authorization_code}}');
        $this->dropTable('{{%oauth2_client}}');
    }
}
