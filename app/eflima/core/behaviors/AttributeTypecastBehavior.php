<?php namespace eflima\core\behaviors;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use yii\behaviors\AttributeTypecastBehavior as BaseAttributeTypecastBehavior;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class AttributeTypecastBehavior extends BaseAttributeTypecastBehavior
{
    public $typecastAfterFind = true;
    public $typecastAfterSave = true;
    public $skipOnNull = true;
    public $skipOnEmpty = true;
}
