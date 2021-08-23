<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\AccountQuery;
use eflima\account\models\queries\OAuth2ClientQuery;
use eflima\account\models\queries\OAuth2RefreshTokenQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property Account      $account
 * @property Oauth2Client $client
 *
 * @property int          $id         [int(10) unsigned]
 * @property string       $token
 * @property int          $expiration [int(11) unsigned]
 * @property string       $client_id  [varchar(64)]
 * @property int          $account_id [int(11) unsigned]
 * @property bool         $is_used    [tinyint(1)]
 * @property int          $created_at [int(11) unsigned]
 */
class OAuth2RefreshToken extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%oauth2_refresh_token}}';
    }

    /**
     * @inheritDoc
     *
     * @return OAuth2RefreshTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new OAuth2RefreshTokenQuery(get_called_class()))->alias("oauth2_refresh_token");
    }

    /**
     * @return ActiveQuery|AccountQuery
     */
    public function getAccount()
    {
        return $this->hasOne(Account::class, ['id' => 'account_id'])->inverseOf('refreshTokens');
    }

    /**
     * @return ActiveQuery|OAuth2ClientQuery
     */
    public function getClient()
    {
        return $this->hasOne(OAuth2Client::class, ['id' => 'client_id'])->inverseOf('refreshTokens');
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
}
