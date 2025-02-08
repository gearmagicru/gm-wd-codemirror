<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\CodeMirror\Model;

use Gm\Panel\Data\Model\WidgetSettingsModel;

/**
 * Модель настроек виджета.
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\CodeMirror\Model
 * @since 1.0
 */
class Settings extends WidgetSettingsModel
{
    /**
     * {@inheritdoc}
     */
    public function maskedAttributes(): array
    {
        return [
            'theme'           => 'theme',
            'styleActiveLine' => 'styleActiveLine',
            'foldGutter'      => 'foldGutter',
            'lineWrapping'    => 'lineWrapping',
            'lineNumbers'     => 'lineNumbers',
            'matchBrackets'   => 'matchBrackets',
            'xmlFold'         => 'xmlFold',
            'markdownFold'    => 'markdownFold',
            'inddentFold'     => 'inddentFold',
            'commentFold'     => 'commentFold',
            'braceFold'       => 'braceFold',
            'foldCode'        => 'foldCode',
            'modes'           => 'modes'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'theme'           => 'Theme',
            'styleActiveLine' => 'Active line',
            'foldGutter'      => 'Fold gutter',
            'matchBrackets'   => 'Match brackets',
            'lineWrapping'    => 'Line wrapping',
            'lineNumbers'     => 'Line numbers',
            'xmlFold'         => 'XML fold',
            'markdownFold'    => 'Markdown fold',
            'inddentFold'     => 'Indent fold',
            'commentFold'     => 'Comment fold',
            'braceFold'       => 'Brace fold',
            'foldCode'        => 'Fold code',
            'modes'           => 'Modes'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function formatterRules(): array
    {
        return [
            [
                [
                    'styleActiveLine', 'foldGutter', 'lineWrapping', 'lineNumbers', 'matchBrackets', 'xmlFold', 
                    'markdownFold', 'inddentFold', 'commentFold', 'braceFold',  'foldCode'
                ], 'logic' => [true, false],
            ],
            [
                'modes', 'tags'
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function validationRules(): array
    {
        return [
            [['theme', 'modes'], 'notEmpty']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function afterValidate(bool $isValid): bool
    {
        if ($isValid) {
            $modes = explode(',', $this->modes);
            if (!(in_array('htmlmixed', $modes) && in_array('xml', $modes) && in_array('clike', $modes))) {
                $this->setError($this->module->t('Modes must be selected'));
            }
            return false;
        }
        return $isValid;
    }
}