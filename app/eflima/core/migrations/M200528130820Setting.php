<?php

namespace eflima\core\migrations;

use eflima\core\components\SettingInstaller;
use yii\db\Migration;

/**
 * Class M200528130820Setting
 */
class M200528130820Setting extends Migration
{
    use SettingInstaller;

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%setting}}', [
            'code' => $this->string(255)->notNull(),
            'type' => $this->string(64),
            'is_overridable' => $this->boolean()->defaultValue(false),
        ], $tableOptions);

        $this->createTable('{{%setting_value}}', [
            'id' => $this->primaryKey(10)->unsigned(),
            'setting_code' => $this->string(255)->notNull(),
            'account_id' => $this->integer(10)->unsigned()->null(),
            'value' => $this->text()->null(),
            'updated_at' => $this->integer(10)->null(),
        ], $tableOptions);

        $this->addPrimaryKey('code', '{{%setting}}', 'code');

        $this->addForeignKey(
            'account_of_setting',
            '{{%setting_value}}', 'account_id',
            '{{%account}}', 'id',
            'CASCADE'
        );

        $this->addForeignKey(
            'setting_of_value',
            '{{%setting_value}}', 'setting_code',
            '{{%setting}}', 'code',
            'CASCADE'
        );

        $this->installSettings($this->settings());
    }

    protected function settings()
    {
        return [
            'date_format' => [
                'is_overridable' => true,
                'type' => 'text',
                'default' => 'd-m-Y',
            ],
            'time_format' => [
                'is_overridable' => true,
                'type' => 'text',
                'default' => 'H:i:s',
            ],
            'timezone' => [
                'is_overridable' => true,
                'type' => 'text',
                'default' => 'Asia/Jakarta',
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('account_of_setting', '{{%setting_value}}');
        $this->dropForeignKey('setting_of_value', '{{%setting_value}}');

        $this->dropTable('{{%setting}}');
        $this->dropTable('{{%setting_value}}');
    }
}
