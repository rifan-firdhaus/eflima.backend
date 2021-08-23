<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\AccountQuery;
use eflima\account\models\queries\AccountVendorAuthQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property Account $account
 *
 * @property int     $id         [int(10) unsigned]
 * @property string  $account_id [char(16)]
 * @property string  $vendor     [varchar(32)]
 * @property string  $access_token
 * @property string  $data
 * @property int     $expiration [int(11) unsigned]
 * @property int     $created_at [int(11) unsigned]
 */
class AccountVendorAuth extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account_vendor_auth}}';
    }

    /**
     * @inheritDoc
     *
     * @return AccountVendorAuthQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new AccountVendorAuthQuery(get_called_class()))->alias("account_vendor_auth");
    }

    /**
     * @return ActiveQuery|AccountQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id'])
            ->inverseOf('vendorAuths');
    }
}
