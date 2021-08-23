<?php namespace eflima\core\db;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\db\ActiveRecord as BaseActiveRecord;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 *
 * @method ActiveQuery hasMany($class, array $link) see [[BaseActiveRecord::hasMany()]] for more info
 * @method ActiveQuery hasOne($class, array $link) see [[BaseActiveRecord::hasOne()]] for more info
 */
class ActiveRecord extends BaseActiveRecord
{
    /**
     * Normalize attributes before save or read
     *
     * @param boolean $save wheter normalize for saveing or for reading
     */
    public function normalizeAttributes($save)
    {
        if ($save) {
            $this->normalizeAttributesOnSave();
        } else {
            $this->normalizeAttributesOnRead();
        }
    }

    /**
     * Normalize before read
     */
    public function normalizeAttributesOnRead()
    {

    }

    /**
     * Normalize before save
     */
    public function normalizeAttributesOnSave()
    {

    }

    /**
     * @inheritDoc
     */
    public function beforeSave($insert)
    {
        $this->normalizeAttributes(true);

        return parent::beforeSave($insert);
    }

    /**
     * @inheritDoc
     */
    public function afterFind()
    {
        $this->normalizeAttributes(false);

        parent::afterFind();
    }

    /**
     * @inheritDoc
     */
    public function afterRefresh()
    {
        $this->normalizeAttributes(false);

        parent::afterRefresh();
    }
}
