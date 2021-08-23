<?php namespace eflima\account\components\oauth2\storages;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\Account;
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
        $model = Account::find()->andWhere(['username' => $username])->one();

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
        $model = Account::find()->andWhere(['username' => $username])->one();

        if (!$model) {
            return false;
        }

        return [
            'user_id' => $model->uuid,
        ];
    }
}
