<?php namespace eflima\core\models;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo

use DateTime;
use DateTimeZone;
use eflima\core\db\ActiveQuery;
use eflima\core\db\ActiveRecord;
use eflima\core\models\queries\SettingQuery;
use eflima\core\models\queries\SettingValueQuery;
use Exception;
use Yii;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @property SettingValue[] $values
 *
 * @property string         $code           [varchar(255)]
 * @property string         $type           [varchar(64)]
 * @property bool           $is_overridable [tinyint(1)]
 */
class Setting extends ActiveRecord
{
    /**
     * @inheritDoc
     */
    public static function tableName()
    {
        return '{{%setting}}';
    }

    /**
     * @inheritDoc
     *
     * @return SettingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return (new SettingQuery(get_called_class()))->alias("setting");
    }

    /**
     * @return ActiveQuery|SettingValueQuery
     */
    public function getValues()
    {
        return $this->hasMany(SettingValue::class, ['setting_code' => 'code'])->inverseOf('setting');
    }

    /**
     * @return array
     *
     * @throws Exception
     */
    public static function allowedTimezoneList()
    {
        $timezoneList = Yii::$app->cache->get('timezoneList');

        if ($timezoneList === false) {
            $timezoneIdentifiers = DateTimeZone::listIdentifiers();
            $utcTime = new DateTime('now', new DateTimeZone('UTC'));
            $tempTimezones = [];
            $timezoneList = [];

            foreach ($timezoneIdentifiers as $timezoneIdentifier) {
                $currentTimezone = new DateTimeZone($timezoneIdentifier);

                $tempTimezones[] = [
                    'offset' => (int) $currentTimezone->getOffset($utcTime),
                    'id' => $timezoneIdentifier,
                ];
            }

            usort($tempTimezones, function ($a, $b) {
                return strcmp($a['id'], $b['id']);
            });

            foreach ($tempTimezones as $tz) {
                $sign = ($tz['offset'] > 0) ? '+' : '-';
                $offset = gmdate('H:i', abs($tz['offset']));
                $name = str_replace('/', ', ', $tz['id']);
                $name = str_replace('_', ' ', $name);
                $name = str_replace('St ', 'St. ', $name);

                $timezoneList[] = [
                    'id' => $tz['id'],
                    'name' => $name,
                    'offset' => $sign . $offset,
                ];
            }

            Yii::$app->cache->set('timezoneList', $timezoneList);
        }

        return $timezoneList;
    }

    /**
     * @return array
     */
    public static function allowedDateFormat()
    {
        return [
            'y/m/d' => 'php:Y/m/d',
            'd/m/y' => 'php:d/m/Y',
            'm/d/y' => 'php:m/d/Y',
        ];
    }

    /**
     * @return array
     */
    public static function allowedTimeFormat()
    {
        return [
            '12' => '12',
            '24' => '24',
        ];
    }
}
