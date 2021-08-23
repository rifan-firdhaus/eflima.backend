<?php namespace eflima\core;

// "Keep the essence of your code, code isn't just a code, it's an art." -- Rifan Firdhaus Widigdo
use eflima\core\base\Module;
use eflima\core\components\Setting;
use eflima\core\validators\CommaSeparatedValidator;
use eflima\core\validators\DateModifierValidator;
use Yii;
use yii\base\BootstrapInterface;
use yii\data\Pagination;
use yii\validators\BooleanValidator;
use yii\validators\CompareValidator;
use yii\validators\DateValidator;
use yii\validators\EmailValidator;
use yii\validators\ExistValidator;
use yii\validators\FileValidator;
use yii\validators\ImageValidator;
use yii\validators\IpValidator;
use yii\validators\NumberValidator;
use yii\validators\RangeValidator;
use yii\validators\RegularExpressionValidator;
use yii\validators\RequiredValidator;
use yii\validators\StringValidator;
use yii\validators\UniqueValidator;
use yii\validators\UrlValidator;
use yii\validators\Validator;

/**
 * @author Rifan Firdhaus Widigdo <rifanfirdhaus@gmail.com>
 */
class Core extends Module implements BootstrapInterface
{
    public function init()
    {
        parent::init();

        $this->setValidators();

        Yii::$container->set(Pagination::class, [
            'pageSizeParam' => 'page_size',
            'pageSizeLimit' => [1, 200],
        ]);

        $this->setSettingOptions();
    }

    public function bootstrap($app)
    {
        $app->setTimeZone('Asia/Jakarta');
    }

    public function setValidators()
    {
        Validator::$builtInValidators['commaSeparated'] = CommaSeparatedValidator::class;
        Validator::$builtInValidators['dateModifier'] = DateModifierValidator::class;

        Yii::$container->set(BooleanValidator::class, [
            'message' => 'boolean',
        ]);
        Yii::$container->set(DateValidator::class, [
            'message' => 'date',
            'tooSmall' => 'dateMin',
            'tooBig' => 'dateMax',
        ]);
        Yii::$container->set(CompareValidator::class, [
            'message' => 'compare',
        ]);
        Yii::$container->set(EmailValidator::class, [
            'message' => 'email',
        ]);
        Yii::$container->set(RequiredValidator::class, [
            'message' => 'required',
        ]);
        Yii::$container->set(ExistValidator::class, [
            'message' => 'exist',
        ]);
        Yii::$container->set(FileValidator::class, [
            'message' => 'file',
            'uploadRequired' => 'fileRequired',
            'tooSmall' => 'fileMinSize',
            'tooBig' => 'fileMaxSize',
            'tooMany' => 'fileMax',
            'tooFew' => 'fileFew',
            'wrongExtension' => 'fileExtension',
            'wrongMimeType' => 'fileMimeType',
        ]);
        Yii::$container->set(ImageValidator::class, [
            'notImage' => 'image',
            'underWidth' => 'imageMinWidth',
            'overWidth' => 'imageMaxWidth',
            'underHeight' => 'imageMinHeight',
            'overHeight' => 'imageMaxHeight',
        ]);
        Yii::$container->set(IpValidator::class, [
            'message' => 'ip',
            'ipv6NotAllowed' => 'ipV6NotAllowed',
            'ipv4NotAllowed' => 'ipV4NotSllowed',
            'wrongCidr' => 'ipSubnetMask',
            'noSubnet' => 'ipWithSubnetMask',
            'hasSubnet' => 'ipNotSubnetMask',
            'notInRange' => 'ipRange',
        ]);
        Yii::$container->set(NumberValidator::class, [
            'message' => 'number',
            'tooBig' => 'numberMax',
            'tooSmall' => 'numberMin',
        ]);
        Yii::$container->set(RangeValidator::class, [
            'message' => 'range',
        ]);
        Yii::$container->set(RegularExpressionValidator::class, [
            'message' => 'regex',
        ]);
        Yii::$container->set(StringValidator::class, [
            'message' => 'string',
            'tooShort' => 'stringMinLength',
            'tooLong' => 'stringMaxLength',
            'notEqual' => 'stringEqualLength',
        ]);
        Yii::$container->set(UniqueValidator::class, [
            'message' => 'unique',
        ]);
        Yii::$container->set(UrlValidator::class, [
            'message' => 'url',
        ]);
    }

    public function setSettingOptions()
    {
        Setting::setOptions([
            'date_format' => [
                'rules' => ['string'],
            ],
            'time_format' => [
                'rules' => ['string'],
            ],
        ]);
    }
}
