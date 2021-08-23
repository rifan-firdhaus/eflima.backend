<?php

namespace eflima\account\migrations;

use yii\db\Migration;

/**
 * Class M210806170437Notification
 */
class M210806170437Notification extends Migration
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

        $this->createTable('{{%notification_topic}}', [
            'id' => $this->primaryKey()->unsigned(),
            'code' => $this->char(64)->notNull(),
            'subscriber_id' => $this->integer()->unsigned()->notNull(),
            'subscribed_at' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'subscriber_of_notification_topic',
            '{{%notification_topic}}', 'subscriber_id',
            '{{%account}}', 'id',
            'CASCADE'
        );

        $this->createTable('{{%notification}}', [
            'id' => $this->primaryKey()->unsigned(),
            'uuid' => $this->char(36)->unique()->notNull(),
            'topic_code' => $this->char(64)->null(),
            'title' => $this->text()->null(),
            'image' => $this->text()->null(),
            'content' => $this->text()->null(),
            'type' => $this->char(64)->null(),
            'data' => $this->text()->null(),
            'at' => $this->integer()->unsigned(),
        ], $tableOptions);


        $this->createTable('{{%notification_receiver}}', [
            'id' => $this->primaryKey()->unsigned(),
            'notification_id' => $this->integer()->unsigned()->notNull(),
            'receiver_id' => $this->integer()->unsigned()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'notification_of_receiver',
            '{{%notification_receiver}}', 'notification_id',
            '{{%notification}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'receiver_of_notification',
            '{{%notification_receiver}}', 'receiver_id',
            '{{%account}}', 'id',
            'CASCADE'
        );


        $this->createTable('{{%notification_receipt}}', [
            'id' => $this->primaryKey()->unsigned(),
            'notification_id' => $this->integer()->unsigned()->notNull(),
            'account_id' => $this->integer()->unsigned()->notNull(),
            'read_at' => $this->integer()->unsigned()->null(),
            'opened_at' => $this->integer()->unsigned()->null(),
        ], $tableOptions);

        $this->addForeignKey(
            'notification_of_receipt',
            '{{%notification_receipt}}', 'notification_id',
            '{{%notification}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'account_of_notification',
            '{{%notification_receipt}}', 'account_id',
            '{{%account}}', 'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('notification_of_receiver', '{{%notification_receiver}}');
        $this->dropForeignKey('receiver_of_notification', '{{%notification_receiver}}');
        $this->dropForeignKey('subscriber_of_notification_topic', '{{%notification_topic}}');
        $this->dropForeignKey('notification_of_receipt', '{{%notification_receipt}}');
        $this->dropForeignKey('account_of_notification', '{{%notification_receipt}}');

        $this->dropTable('{{%notification}}');
        $this->dropTable('{{%notification_receiver}}');
        $this->dropTable('{{%notification_topic}}');
        $this->dropTable('{{%notification_receipt}}');
    }
}
