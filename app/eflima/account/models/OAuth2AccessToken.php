<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\AccountQuery;
use eflima\account\models\queries\OAuth2AccessTokenQuery;
use eflima\account\models\queries\OAuth2ClientQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property Account      $account
 * @property OAuth2Client $client
 *
 * @property int          $id               [int(10) unsigned]
 * @property string       $client_id        [varchar(64)]
 * @property string       $account_id       [char(16)]
 * @property string       $token
 * @property int          $expiration       [int(11) unsigned]
 * @property bool         $is_granted       [tinyint(1)]
 * @property string       $scope            [varchar(255)]
 * @property int          $last_activity_at [int(11) unsigned]
 * @property int          $created_at       [int(11) unsigned]
 */
class OAuth2AccessToken extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth2_access_token}}';
    }

    /**
     * @inheritDoc
     *
     * @return OAuth2AccessTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new OAuth2AccessTokenQuery(get_called_class()))->alias("oauth2_access_token");
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'updatedAtAttribute' => false,
        ];

        return $behaviors;
    }

    /**
     * @return ActiveQuery|AccountQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id'])->inverseOf('accessTokens');
    }

    /**
     * @return ActiveQuery|OAuth2ClientQuery
     */
    public function getClient()
    {
        return $this->hasOne(OAuth2Client::class, ['id' => 'client_id'])->inverseOf('accessTokens');
    }
}
