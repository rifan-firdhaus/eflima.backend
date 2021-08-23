<?php namespace eflima\control_panel;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\components\oauth2\OAuth2;
use eflima\control_panel\web\Application;
use eflima\core\base\Module;
use yii\base\BootstrapInterface;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property OAuth2 $oauth2
 */
class ControlPanel extends Module implements BootstrapInterface
{
    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        if ($app instanceof Application) {
            $this->registerAdminRoute($app);
        }
    }

    /**
     * @param Application $app
     */
    protected function registerAdminRoute($app)
    {
        $app->getUrlManager()->addRules([
            "<module>" => "/<module>/control_panel/default/index",
            "<module>/<controller>" => "/<module>/control_panel/<controller>",
            "<module>/<controller>/<action>" => "/<module>/control_panel/<controller>/<action>",
        ]);
    }
}
