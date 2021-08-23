<?php namespace eflima\control_panel\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\Account;
use eflima\control_panel\ControlPanel;
use eflima\control_panel\models\queries\AdministratorQuery;
use eflima\core\db\ActiveQuery;
use OAuth2\Storage\AccessTokenInterface;
use Yii;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property-read Administrator $profile
 */
class AdministratorAccount extends Account
{
    /**
     * @return ActiveQuery|AdministratorQuery
     */
    public function getProfile()
    {
        return $this->hasOne(Administrator::class, ['account_id' => 'id'])
            ->alias('administrator_of_account')
            ->inverseOf('account');
    }

    /**
     * @inheritDoc
     */
    public function normalizeAttributesOnSave()
    {
        if ($this->getIsNewRecord()) {
            $this->type = 'admin';
        }

        parent::normalizeAttributesOnSave();
    }

    /**
     * @inheritDoc
     */
    public function getId()
    {
        return $this->profile->uuid;
    }

    /**
     * @inheritDoc
     */
    public static function findIdentity($id)
    {
        return self::find()
            ->joinWith('profile')
            ->andWhere(['administrator_of_account.uuid' => $id])
            ->andWhere(['account.is_blocked' => false])
            ->one();
    }

    /**
     * @inheritDoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        /** @var ControlPanel $admin */
        $admin = Yii::$app->getModule('control_panel');

        /** @var AccessTokenInterface $tokenStorage */
        $tokenStorage = $admin->oauth2->storages['access_token'];

        $token = $tokenStorage->getAccessToken($token);

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
    public function fields()
    {
        $fields = parent::fields();

        unset(
            $fields['id'],
            $fields['password'],
            $fields['password_reset_token'],
            $fields['password_reset_token_expiration'],
            $fields['confirmation_expiration'],
            $fields['confirmation_token'],
            $fields['auth_token'],
            $fields['is_system'],
            $fields['type']
        );

        return $fields;
    }
}
