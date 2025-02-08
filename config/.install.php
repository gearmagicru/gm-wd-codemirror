<?php
/**
 * Этот файл является частью виджета веб-приложения GearMagic.
 * 
 * Файл конфигурации установки виджета.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'use'         => BACKEND,
    'id'          => 'gm.wd.codemirror',
    'category'    => 'editor',
    'name'        => 'CodeMirror',
    'description' => 'Extensible Code Editor',
    'namespace'   => 'Gm\Widget\CodeMirror',
    'path'        => '/gm/gm.wd.codemirror',
    'locales'     => ['ru_RU', 'en_GB'],
    'events'      => ['gm.be.workspace:onRender'],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM MS'],
        ['app', 'code' => 'GM CMS'],
        ['app', 'code' => 'GM CRM'],
        ['module', 'id' => 'gm.be.workspace']
    ]
];
