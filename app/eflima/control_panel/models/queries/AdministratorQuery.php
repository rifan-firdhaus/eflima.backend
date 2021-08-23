<?php namespace eflima\control_panel\models\queries;

use eflima\control_panel\models\Administrator;
use eflima\core\db\ActiveQuery;
use eflima\core\db\FindByUUIDQQuery;

/**
 * This is the ActiveQuery class for [[\eflima\admin\models\Admin]].
 *
 * @see \eflima\control_panel\models\Administrator
 */
class AdministratorQuery extends ActiveQuery
{
    use FindByUUIDQQuery;

    /**
     * @inheritDoc
     * @return Administrator[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritDoc
     * @return Administrator|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
