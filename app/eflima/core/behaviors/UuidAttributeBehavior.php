<?php namespace eflima\core\behaviors;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use Ramsey\Uuid\Uuid;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class UuidAttributeBehavior extends AttributeBehavior
{
    /** @var string */
    public $uuidAttribute = 'uuid';

    /** @var string */
    public $value;

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->uuidAttribute],
            ];
        }
    }

    /**
     * @inheritDoc
     */
    protected function getValue($event)
    {
        if ($this->value === null) {
            return Uuid::uuid4()->toString();
        }

        return parent::getValue($event);
    }
}
