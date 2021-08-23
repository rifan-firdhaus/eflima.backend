<?php namespace eflima\control_panel\components\oauth2\storages;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\control_panel\models\AdministratorAccount;
use OAuth2\Storage\UserCredentialsInterface;
use yii\base\BaseObject;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class UserCredentials extends BaseObject implements UserCredentialsInterface
{
    /**
     * @inheritDoc
     */
    public function checkUserCredentials($username, $password)
    {
        $model = AdministratorAccount::find()->andWhere(['username' => $username, 'type' => 'administrator'])->one();

        if (!$model || !$model->verifyPassword($password)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function getUserDetails($username)
    {
        $model = AdministratorAccount::find()->andWhere(['username' => $username, 'type' => 'administrator'])->one();

        if (!$model) {
            return false;
        }

        return [
            'user_id' => $model->profile->uuid,
        ];
    }
}
