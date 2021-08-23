<?php namespace eflima\core\components;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use Exception;
use yii\helpers\ArrayHelper;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
trait SettingInstaller
{
    /**
     * @param array $settings
     *
     * @throws Exception
     */
    public function installSettings($settings)
    {
        $data = [];
        $values = [];

        foreach ($settings as $code => $setting) {
            if (is_numeric($code)) {
                $setting = ['code' => $setting];
            } else {
                $setting['code'] = $code;
            }

            $data[] = [
                ArrayHelper::getValue($setting, 'code', null),
                ArrayHelper::getValue($setting, 'type', 'text'),
                ArrayHelper::getValue($setting, 'is_overridable', false),
            ];

            if (!empty($setting['default'])) {
                $values[] = [
                    $setting['code'],
                    $setting['default'],
                    time(),
                ];
            }
        }

        if (!empty($data)) {
            $this->batchInsert('{{%setting}}', ['code', 'type', 'is_overridable'], $data);
        }

        if (!empty($values)) {
            $this->batchInsert('{{%setting_value}}', ['setting_code', 'value', 'updated_at'], $values);
        }

    }

    /**
     * @param array $settings
     */
    public function uninstallSettings($settings)
    {
        $codes = [];

        foreach ($settings as $code => $setting) {
            if (is_numeric($code)) {
                $codes[] = $setting;
            } else {
                $codes[] = $code;
            }
        }

        if (empty($codes)) {
            return;
        }

        $this->delete('{{%setting}}', ['code' => $codes]);
    }
}
