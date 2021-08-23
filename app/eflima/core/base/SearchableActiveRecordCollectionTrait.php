<?php namespace eflima\core\base;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\core\db\ActiveQuery;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
trait SearchableActiveRecordCollectionTrait
{
    use SearchableModelCollectionTrait;

    /** @var ActiveQuery */
    protected $_query;

    /**
     * @inheritDoc
     */
    public function asDataProvider()
    {
        /** @var ActiveDataProvider $dataProvider */
        $dataProvider = Yii::createObject($this->dataProvider);
        $dataProvider->query = clone $this->getQuery();

        return $dataProvider;
    }

    /**
     * @return array|ActiveRecord[]
     */
    public function asCollection()
    {
        return $this->getQuery()->all();
    }

    /**
     * @inheritDoc
     */
    public function filter()
    {
        if (!$this->validate()) {
            return false;
        }

        return true;
    }
}
