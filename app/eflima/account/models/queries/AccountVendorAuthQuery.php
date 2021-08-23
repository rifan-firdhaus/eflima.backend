<?php namespace eflima\account\models\queries;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\account\models\AccountVendorAuth;
use eflima\core\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * This is the ActiveQuery class for [[\eflima\account\models\AccountVendorAuth]].
 *
 * @see    \eflima\account\models\AccountVendorAuth
 */
class AccountVendorAuthQuery extends ActiveQuery
{
    /**
     * @inheritDoc
     *
     * @return AccountVendorAuth[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     *
     * @return AccountVendorAuth|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
