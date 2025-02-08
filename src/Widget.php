<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\CodeMirror;

use Gm;
use Gm\Http\Response;
use Gm\View\ClientScript;
use Gm\View\WidgetResourceTrait;

/**
 * Виджет редактора "CodeMirror".
 * 
 * @link https://codemirror.net/
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\CodeMirror
 * @since 1.0
 */
class Widget extends \Gm\View\BaseWidget
{
    use WidgetResourceTrait;

    /**
     * Расширение редактируемого файла, определяет режим работы редактора.
     * 
     * Указывается параметром конструктора класса.
     * 
     * Например: 'css', 'php', 'js'...
     * 
     * @var string
     */
    public string $fileExtension = '';

    /**
     * Дополнения редактора.
     * 
     * Каждому ключу (параметру настроек редактора) соответствуют подключенные стили 
     * и скрипты дополнений.
     *
     * @var array
     */
    public array $addons = [
        'styleActiveLine' => [
            'js' => '/selection/active-line.js'
        ],
        'matchBrackets' => [
            'js' => '/edit/matchbrackets.js'
        ],
        'foldCode' => [
            'js' => '/fold/foldcode.js'
        ],
        'foldGutter' => [
            'js'  => '/fold/foldgutter.js',
            'css' => '/fold/foldgutter.css'
        ],
        'xmlFold' => [
            'js' => '/fold/xml-fold.js'
        ],
        'markdownFold' => [
            'js' => '/fold/foldgutter.js'
        ],
        'inddentFold' => [
            'js' => '/fold/indent-fold.js'
        ],
        'commentFold' => [
            'js' => '/fold/comment-fold.js'
        ],
        'braceFold' => [
            'js' => '/fold/brace-fold.js'
        ]
    ];
    
    /**
     * {@inheritdoc}
     */
    public string $id = 'gm.wd.codemirror';

    /**
     * {@inheritdoc}
     */
    public function init(): void
    {
        $self = $this;
        $self->getSettings();

        // событие перед выводом параметров в шаблон workspace
        $this->on('gm.be.workspace:onRender', function ($params) use ($self) {
            $self->initScript();
        });
    }

    /**
     * Добавляет пакет скриптов клиенту для подключения редактора.
     * 
     * Все параметры по умолчанию {@see Widget::$options} должны быть указаны в файле 
     * конфигурации ".settings.php".
     * 
     * @return void
     */
    public function initScript(): void
    {
        $url = $this->getAssetsUrl() . '/dist';

        Gm::$app->clientScript
            ->appendPackage('codemirror', [
                'position' => ClientScript::POS_END,
                'css'      => ['codemirror.css' => [$url . '/lib/codemirror.css']],
                'js'       => ['codemirror.js' => [$url . '/lib/codemirror.js']]
            ])
            ->registerPackage('codemirror');

        $this->initAddons();
        $this->initModes();
    }

    /**
     * Инициализация дополнений редактора.
     * 
     * @return void
     */
    public function initAddons(): void
    {
        $js = $css = [];
        $url = $this->getAssetsUrl() . '/dist/addon';

        $index = 0;
        foreach ($this->addons as $addon => $params) {
            if (!$this->settings->empty($addon)) {
                $id = 'addon' . (++$index);
                if (isset($params['js'])) {
                    $js[$id] = [$url . $params['js']];
                }
                if (isset($params['css'])) {
                    $css[$id] = [$url . $params['css']];
                }
            }
        }

        Gm::$app->clientScript
            ->appendPackage('codemirrorAddons', [
                'position' => ClientScript::POS_END,
                'css'      => $css,
                'js'       => $js
            ])
            ->registerPackage('codemirrorAddons');
    }

    /**
     * Инициализация режимов редактора.
     * 
     * @return void
     */
    public function initModes(): void
    {
        if ($this->settings->modes) {
            $modes = explode(',', $this->settings->modes);
            if ($modes) {
                $js = [];
                $url = $this->getAssetsUrl() . '/dist/mode';

                foreach ($modes as $mode) {
                    $js[$mode] = [$url . '/' . $mode . '/' . $mode . '.js'];
                }

                Gm::$app->clientScript
                    ->appendPackage('codemirrorModes', [
                        'position' => ClientScript::POS_END,
                        'js'       => $js
                    ])
                    ->registerPackage('codemirrorModes');
            }
        }
    }

    /**
     * @param Response $response
     * 
     * @return void
     */
    public function initResponse(Response $response): void
    {
        if ($response instanceof \Gm\Panel\Http\Response) {
            $response
                ->meta
                    ->add('jsPath', ['gm.wd.codemirror', $this->getRequireUrl() . '/js'])
                    ->add('requires', 'gm.wd.codemirror.CodeMirror');
        }
    }

    /**
     * Возвращает режим редактора из указанного расширения файла.
     * 
     * @param string $extension Расширение файла (например: 'php', 'css'...).
     * 
     * @return string|null
     */
    public function getMode(string $extension): ?string
    {
        $mode = [
            'apl'   => 'text/apl',
            'h'     => 'text/x-csrc',
            'php'   => 'application/x-httpd-php',
            'phtml' => 'application/x-httpd-php',
            'scss'  => 'text/x-scss',
            'gss'   => 'text/x-gss',
            'less'  => 'text/x-less',
            'css'   => 'text/x-gss',
            'html'  => 'text/html',
            'json'  => 'application/ld+json',
            'jsx'   => 'jsx',
            'js'    => 'text/typescript',
            'sql'   => 'text/x-mariadb',
            'xml'   => 'text/html'
        ];
        return $mode[strtolower($extension)] ?? null;
    }

    /**
     * Возвращает расширение файлов с соответствующем режимом редактора.
     * 
     * @return array
     */
    public function getModeExtensions(): array
    {
        return [
            'dyalog' => 'apl',
            'apl' => 'apl',
            'asc' => 'asciiarmor',
            'pgp' => 'asciiarmor',
            'sig' => 'asciiarmor',
            'asn' => 'asn.1',
            'asn1' => 'asn.1',
            'b' => 'brainfuck',
            'bf' => 'brainfuck',
            'c' => 'clike',
            'h' => 'clike',
            'ino' => 'clike',
            'cpp' => 'clike',
            'c++' => 'clike',
            'cc' => 'clike',
            'cxx' => 'clike',
            'hpp' => 'clike',
            'h++' => 'clike',
            'hh' => 'clike',
            'hxx' => 'clike',
            'cob' => 'cobol',
            'cpy' => 'cobol',
            'cs' => 'clike',
            'clj' => 'clojure',
            'cljc' => 'clojure',
            'cljx' => 'clojure',
            'cljs' => 'clojure',
            'gss' => 'css',
            'cmake' => 'cmake',
            'cmake.in' => 'cmake',
            'coffee' => 'coffeescript',
            'cl' => 'commonlisp',
            'lisp' => 'commonlisp',
            'el' => 'commonlisp',
            'cyp' => 'cypher',
            'cypher' => 'cypher',
            'pyx' => 'python',
            'pxd' => 'python',
            'pxi' => 'python',
            'cr' => 'crystal',
            'css' => 'css',
            'cql' => 'sql',
            'd' => 'd',
            'dart' => 'dart',
            'diff' => 'diff',
            'patch' => 'diff',
            'dtd' => 'dtd',
            'dylan' => 'dylan',
            'dyl' => 'dylan',
            'intr' => 'dylan',
            'ecl' => 'ecl',
            'edn' => 'clojure',
            'e' => 'eiffel',
            'elm' => 'elm',
            'ejs' => 'htmlembedded',
            'erb' => 'htmlembedded',
            'erl' => 'erlang',
            'factor' => 'factor',
            'forth' => 'forth',
            'fth' => 'forth',
            '4th' => 'forth',
            'f' => 'fortran',
            'for' => 'fortran',
            'f77' => 'fortran',
            'f90' => 'fortran',
            'f95' => 'fortran',
            'fs' => 'mllike',
            's' => 'gas',
            'feature' => 'gherkin',
            'go' => 'go',
            'groovy' => 'groovy',
            'gradle' => 'groovy',
            'haml' => 'haml',
            'hs' => 'haskell',
            'lhs' => 'haskell-literate',
            'hx' => 'haxe',
            'hxml' => 'haxe',
            'aspx' => 'htmlembedded',
            'html' => 'htmlmixed',
            'htm' => 'htmlmixed',
            'handlebars' => 'htmlmixed',
            'hbs' => 'htmlmixed',
            'pro' => 'idl',
            'jade' => 'pug',
            'pug' => 'pug',
            'java' => 'clike',
            'jsp' => 'htmlembedded',
            'js' => 'javascript',
            'json' => 'javascript',
            'map' => 'javascript',
            'jsonld' => 'javascript',
            'jsx' => 'jsx',
            'j2' => 'jinja2',
            'jinja' => 'jinja2',
            'jinja2' => 'jinja2',
            'jl' => 'julia',
            'kt' => 'clike',
            'less' => 'css',
            'ls' => 'livescript',
            'lua' => 'lua',
            'markdown' => 'markdown',
            'md' => 'markdown',
            'mkd' => 'markdown',
            'm' => 'mathematica',
            'nb' => 'mathematica',
            'wl' => 'mathematica',
            'wls' => 'mathematica',
            'mo' => 'modelica',
            'mps' => 'mumps',
            'mbox' => 'mbox',
            'nsh' => 'nsis',
            'nsi' => 'nsis',
            'nt' => 'ntriples',
            'nq' => 'ntriples',
            'm' => 'clike',
            'mm' => 'clike',
            'ml' => 'mllike',
            'mli' => 'mllike',
            'mll' => 'mllike',
            'mly' => 'mllike',
            'm' => 'octave',
            'oz' => 'oz',
            'p' => 'pascal',
            'pas' => 'pascal',
            'jsonld' => 'pegjs',
            'pl' => 'perl',
            'pm' => 'perl',
            'php' => 'php',
            'php3' => 'php',
            'php4' => 'php',
            'php5' => 'php',
            'php7' => 'php',
            'phtml' => 'php',
            'pig' => 'pig',
            'txt' => 'null',
            'text' => 'null',
            'conf' => 'null',
            'def' => 'null',
            'list' => 'null',
            'log' => 'null',
            'pls' => 'sql',
            'ps1' => 'powershell',
            'psd1' => 'powershell',
            'psm1' => 'powershell',
            'properties' => 'properties',
            'ini' => 'properties',
            'in' => 'properties',
            'proto' => 'protobuf',
            'BUILD' => 'python',
            'bzl' => 'python',
            'py' => 'python',
            'pyw' => 'python',
            'pp' => 'puppet',
            'q' => 'q',
            'r' => 'r',
            'R' => 'r',
            'rst' => 'rst',
            'spec' => 'rpm',
            'rb' => 'ruby',
            'rs' => 'rust',
            'sas' => 'sas',
            'sass' => 'sass',
            'scala' => 'clike',
            'scm' => 'scheme',
            'ss' => 'scheme',
            'scss' => 'css',
            'sh' => 'shell',
            'ksh' => 'shell',
            'bash' => 'shell',
            'siv' => 'sieve',
            'sieve' => 'sieve',
            'slim' => 'slim',
            'st' => 'smalltalk',
            'tpl' => 'smarty',
            'sml' => 'mllike',
            'sig' => 'mllike',
            'fun' => 'mllike',
            'smackspec' => 'mllike',
            'soy' => 'soy',
            'rq' => 'sparql',
            'sparql' => 'sparql',
            'sql' => 'sql',
            'nut' => 'clike',
            'styl' => 'stylus',
            'swift' => 'swift',
            'text' => 'stex',
            'ltx' => 'stex',
            'tex' => 'stex',
            'v' => 'verilog',
            'sv' => 'verilog',
            'svh' => 'verilog',
            'tcl' => 'tcl',
            'textile' => 'textile',
            'toml' => 'toml',
            'ttcn' => 'ttcn',
            'ttcn3' => 'ttcn',
            'ttcnpp' => 'ttcn',
            'cfg' => 'ttcn-cfg',
            'ttl' => 'turtle',
            'ts' => 'javascript',
            'tsx' => 'jsx',
            'webidl' => 'webidl',
            'vb' => 'vb',
            'vbs' => 'vbscript',
            'vtl' => 'velocity',
            'v' => 'verilog',
            'vhd' => 'vhdl',
            'vhdl' => 'vhdl',
            'vue' => 'vue',
            'xml' => 'xml',
            'xsl' => 'xml',
            'xsd' => 'xml',
            'svg' => 'xml',
            'xy' => 'xquery',
            'xquery' => 'xquery',
            'ys' => 'yacas',
            'yaml' => 'yaml',
            'yml' => 'yaml',
            'z80' => 'z80',
            'mscgen' => 'mscgen',
            'mscin' => 'mscgen',
            'msc' => 'mscgen',
            'xu' => 'mscgen',
            'msgenny' => 'mscgen',
            'wat' => 'wast',
            'wast' => 'wast'
        ];
    }

    /**
     * Возвращает интерфейс редактора Gm.wd.codemirror.CodeMirror GmJS.
     * 
     * @return mixed
     */
    public function run(): mixed
    {
        /** @var array $options  */
        $options = $this->getSettings()->getAll();

        // если не указан режим редактора
        if (empty($options['mode'])) {
            if ($this->fileExtension) {
                $extensions = $this->getModeExtensions();
                if (isset($extensions[$this->fileExtension])) {
                    $options['mode'] = $extensions[$this->fileExtension];
                }
            }
        }
        
        // использовать тему по умолчанию
        if (isset($options['theme']) && $options['theme'] === 'none') {
            unset($options['theme']);
        }

        return [
            'xtype'   => 'codemirror',
            'options' => $options
        ];
    }
}