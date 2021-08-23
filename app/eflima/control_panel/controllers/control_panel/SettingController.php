<?php namespace eflima\control_panel\controllers\control_panel;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\control_panel\models\AdministratorAccount;
use eflima\control_panel\rest\Controller;
use eflima\core\components\Setting;
use Yii;
use yii\base\Model;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class SettingController extends Controller
{
    public function actionGet()
    {
        /** @var AdministratorAccount $account */
        $account = Yii::$app->getUser()->getIdentity();

        return $account->getSetting()->getValues(Yii::$app->getRequest()->getBodyParam('codes', []));
    }

    public function actionSet()
    {
        /** @var AdministratorAccount $account */
        $account = Yii::$app->getUser()->getIdentity();

        /** @var Setting $globalSetting */
        $globalSetting = Yii::$app->get('setting');
        $accountSetting = $account->getSetting();

        $data = Yii::$app->getRequest()->getBodyParams();
        $models = [];

        foreach ($data as $code => $value) {
            if (isset($value['is_global']) && $value['is_global']) {
                $models[$code] = $globalSetting->getModel($code);
            } else {
                $models[$code] = $accountSetting->getModel($code);
            }

            $models[$code]->value = $value['value'];
        }

        if (!Model::validateMultiple($models)) {
            return $models;
        }

        foreach ($data as $code => $value) {
            if (isset($value['is_global']) && $value['is_global']) {
                if (!$globalSetting->setValue($code, $models[$code]->value)) {
                    return;
                }
            } else {
                if (!$accountSetting->setValue($code, $models[$code]->value)) {
                    return;
                }
            }
        }
    }
}
