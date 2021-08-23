<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\OAuth2AccessTokenQuery;
use eflima\account\models\queries\OAuth2AuthorizationCodeQuery;
use eflima\account\models\queries\OAuth2ClientQuery;
use eflima\account\models\queries\OAuth2RefreshTokenQuery;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property OAuth2AccessToken[]       $accessTokens
 * @property OAuth2AuthorizationCode[] $authorizationCodes
 * @property OAuth2RefreshToken[]      $refreshTokens
 *
 * @property string                    $id               [varchar(64)]
 * @property string                    $client_secret    [varchar(64)]
 * @property bool                      $is_public        [tinyint(1)]
 * @property int                       $last_activity_at [int(11) unsigned]
 * @property int                       $created_at       [int(11) unsigned]
 */
class OAuth2Client extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%oauth2_client}}';
    }

    /**
     * @inheritDoc
     *
     * @return OAuth2ClientQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new OAuth2ClientQuery(get_called_class()))->alias("oauth2_client");
    }

    /**
     * @inheritdoc
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
     * @return ActiveQuery|OAuth2AccessTokenQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(OAuth2AccessToken::class, ['client_id' => 'id'])
            ->inverseOf('client');
    }

    /**
     * @return ActiveQuery|OAuth2AuthorizationCodeQuery
     */
    public function getAuthorizationCodes()
    {
        return $this->hasMany(OAuth2AuthorizationCode::class, ['client_id' => 'id'])
            ->inverseOf('client');
    }

    /**
     * @return ActiveQuery|OAuth2RefreshTokenQuery
     */
    public function getRefreshTokens()
    {
        return $this->hasMany(OAuth2RefreshToken::class, ['client_id' => 'id'])
            ->inverseOf('client');
    }
}
