<?php
Yii::setAlias('app', dirname(__DIR__));
Yii::setAlias('modules', '@app/modules');
Yii::setAlias('eflima', '@app/eflima');
Yii::setAlias('vendor', '@app/vendor');

// Replace yii2 yii\base\ArrayableTrait and yii\helpers\ArrayHelper to support expand recursively
Yii::$classMap['yii\base\ArrayableTrait'] = Yii::getAlias('@eflima/core/base/ArrayableTrait.php');
Yii::$classMap['yii\helpers\ArrayHelper'] = Yii::getAlias('@eflima/core/helpers/ArrayHelper.php');
Yii::$classMap['yii\validators\Validator'] = Yii::getAlias('@eflima/core/validators/Validator.php');
