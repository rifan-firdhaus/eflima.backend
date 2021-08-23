<?php namespace eflima\account\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use eflima\account\models\queries\AccountQuery;
use eflima\account\models\queries\AccountVendorAuthQuery;
use eflima\account\models\queries\OAuth2AccessTokenQuery;
use eflima\account\models\queries\OAuth2AuthorizationCodeQuery;
use eflima\account\models\queries\OAuth2RefreshTokenQuery;
use eflima\core\behaviors\UuidAttributeBehavior;
use eflima\core\components\Setting as SettingComponent;
use eflima\core\Core;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use Yii;
use yii\behaviors\AttributeTypecastBehavior;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property AccountVendorAuth[]       $vendorAuths
 * @property OAuth2AccessToken[]       $accessTokens
 * @property OAuth2AuthorizationCode[] $authorizationCodes
 * @property OAuth2RefreshToken[]      $refreshTokens
 * @property string                    $authKey
 * @property SettingComponent          $setting
 *
 * @property int                       $id                              [int(10) unsigned]
 * @property string                    $uuid                            [char(36)]
 * @property string                    $username                        [varchar(255)]
 * @property string                    $email                           [varchar(255)]
 * @property string                    $phone
 * @property string                    $type                            [varchar(64)]
 * @property string                    $password
 * @property string                    $password_reset_token            [char(64)]
 * @property int                       $password_reset_token_expiration [int(11) unsigned]
 * @property string                    $auth_token                      [char(64)]
 * @property bool                      $is_blocked                      [tinyint(1) unsigned]
 * @property bool                      $is_system                       [tinyint(1) unsigned]
 * @property string                    $confirmation_token              [char(64)]
 * @property int                       $confirmation_expiration         [int(11) unsigned]
 * @property int                       $confirmed_at                    [int(11) unsigned]
 * @property int                       $last_active_at                  [int(11) unsigned]
 * @property int                       $registered_at                   [int(11) unsigned]
 */
class Account extends ActiveRecord implements IdentityInterface
{
    /** @var SettingComponent */
    protected $_setting;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%account}}';
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /** @var Core $core */
        $core = Yii::$app->getModule('core');

        $token = $core->oauth2->storages['access_token']->getAccessToken($token);

        if ($token === null) {
            return;
        }

        if ($token['expires'] < time()) {
            return;
        }

        return self::findIdentity($token['user_id']);
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return self::find()->andWhere(['uuid' => $id])
            ->andWhere(['is_blocked' => false])
            ->one();
    }

    /**
     * @inheritDoc
     *
     * @return AccountQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new AccountQuery(get_called_class()))->alias("account");
    }

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class' => TimestampBehavior::class,
            'createdAtAttribute' => 'registered_at',
            'updatedAtAttribute' => false,
        ];

        $behaviors['uuid'] = [
            'class' => UuidAttributeBehavior::class,
        ];

        $behaviors['attributeTypecast'] = [
            'class' => AttributeTypecastBehavior::class,
            'typecastAfterFind' => true,
            'attributeTypes' => [
                'is_system' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                'is_blocked' => AttributeTypecastBehavior::TYPE_BOOLEAN,
                'confirmed_at' => AttributeTypecastBehavior::TYPE_INTEGER,
                'last_active_at' => AttributeTypecastBehavior::TYPE_INTEGER,
                'registered_at' => AttributeTypecastBehavior::TYPE_INTEGER,
                'confirmation_expiration' => AttributeTypecastBehavior::TYPE_INTEGER,
                'password_reset_token_expiration' => AttributeTypecastBehavior::TYPE_INTEGER,
            ],
        ];

        return $behaviors;
    }

    /**
     * @inheritDoc
     */
    public function normalizeAttributesOnSave()
    {
        if ($this->getIsNewRecord()) {
            if (empty($this->auth_token)) {
                $this->auth_token = Yii::$app->getSecurity()->generateRandomString(64);
            }
        }

        if (!empty($this->password) && $this->isAttributeChanged('password')) {
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        } else {
            $this->password = $this->getOldAttribute('password');
        }

        parent::normalizeAttributesOnSave();
    }

    /**
     * @return ActiveQuery|AccountVendorAuthQuery
     */
    public function getVendorAuths()
    {
        return $this->hasMany(AccountVendorAuth::class, ['account_id' => 'id'])
            ->inverseOf('account');
    }

    /**
     * @return ActiveQuery|OAuth2AccessTokenQuery
     */
    public function getAccessTokens()
    {
        return $this->hasMany(OAuth2AccessToken::class, ['account_id' => 'id'])
            ->inverseOf('account');
    }

    /**
     * @return ActiveQuery|OAuth2AuthorizationCodeQuery
     */
    public function getAuthorizationCodes()
    {
        return $this->hasMany(OAuth2AuthorizationCode::class, ['account_id' => 'id'])
            ->inverseOf('account');
    }

    /**
     * @return ActiveQuery|OAuth2RefreshTokenQuery
     */
    public function getRefreshTokens()
    {
        return $this->hasMany(OAuth2RefreshToken::class, ['account_id' => 'id'])
            ->inverseOf('account');
    }

    /**
     * @return SettingComponent
     */
    public function getSetting()
    {
        if (!$this->_setting) {
            $this->_setting = new SettingComponent([
                'account' => $this,
            ]);
        }

        return $this->_setting;
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->uuid;
    }

    /**
     * @inheritDoc
     */
    public function getAuthKey()
    {
        return $this->auth_token;
    }

    /**
     * @inheritDoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_token === $authKey;
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function verifyPassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * @return bool
     */
    public function block()
    {
        if ($this->is_blocked) {
            return true;
        }

        $this->is_blocked = true;

        return $this->save(false);
    }

    /**
     * @return bool
     */
    public function unblock()
    {
        if (!$this->is_blocked) {
            return true;
        }

        $this->is_blocked = false;

        return $this->save(false);
    }
}
