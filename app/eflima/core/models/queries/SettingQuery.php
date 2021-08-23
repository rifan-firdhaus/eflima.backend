<?php namespace eflima\core\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\core\db\ActiveQuery;
use eflima\core\models\Setting;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\core\models\Setting]].
 *
 * @see    \eflima\core\models\Setting
 */
class SettingQuery extends ActiveQuery
{
    /**
     * @inheritDoc
     * @return Setting[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     * @return Setting|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
