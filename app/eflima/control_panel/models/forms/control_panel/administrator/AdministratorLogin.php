<?php namespace eflima\control_panel\models\forms\control_panel\administrator;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\control_panel\ControlPanel;
use eflima\control_panel\models\AdministratorAccount;
use OAuth2\ResponseInterface;
use Yii;
use yii\base\Model;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AdministratorLogin extends Model
{
    public $username;
    public $password;
    public $remember_me;

    /**
     * @inheritDoc
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['remember_me'], 'boolean'],
        ];
    }


    /**
     * @return array|false
     */
    public function login()
    {
        if (!$this->validate()) {
            return false;
        }

        /** @var ControlPanel $controlPanel */
        $controlPanel = Yii::$app->getModule('control_panel');

        /** @var ResponseInterface $response */
        $response = $controlPanel->oauth2->handleTokenRequest();

        if (empty($response->parameters['access_token'])) {
            return false;
        }

        $account = AdministratorAccount::findIdentityByAccessToken($response->parameters['access_token']);

        Yii::$app->getUser()->login($account);

        return $response->parameters;
    }
}
