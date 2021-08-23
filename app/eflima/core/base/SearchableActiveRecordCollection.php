<?php namespace eflima\core\base;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\db\ActiveQuery;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
interface SearchableActiveRecordCollection extends SearchableCollection
{
    /**
     * @return ActiveQuery
     */
    public function getQuery();
}
