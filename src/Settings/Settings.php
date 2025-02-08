<?php
/**
 * Виджет веб-приложения GearMagic.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

namespace Gm\Widget\CodeMirror\Settings;

use Gm;
use Gm\Panel\Helper\ExtForm;
use Gm\Panel\Helper\ExtCombo;
use Gm\Filesystem\Filesystem;
use Gm\Panel\Widget\SettingsWindow;

/**
 * Настройки редактора "CodeMirror".
 * 
 * @link https://codemirror.net/5/doc/manual.html#config
 * 
 * @author Anton Tivonenko <anton.tivonenko@gmail.com>
 * @package Gm\Widget\CodeMirror\Settings
 * @since 1.0
 */
class Settings extends SettingsWindow
{
    /**
     * Возвращает доступные темы редактора.
     * 
     * Для выпадающего списка.
     * 
     * @return array
     */
    protected function getThemes(): array
    {
        $themes = [['none', Gm::t(BACKEND, '[None]')]];

        // путь к каталогу тем
        $path = __DIR__ . DS . '..' . DS . '..' . DS . 'assets' . DS . 'dist' . DS . 'theme';
        $rows = iterator_to_array(
            Filesystem::finder()->files()->ignoreDotFiles(true)->in($path)->sortByName(), false
        );

        foreach ($rows as $row) {
            $filename = $row->getFilename();
            if ($filename) {
                $name = pathinfo($filename, PATHINFO_FILENAME);
                $themes[] = [$name, str_replace('-', ' ', ucfirst($name))];
            }
        }
        return $themes;
    }

    /**
     * Возвращает доступные режимы редактора.
     * 
     * Для выпадающего списка.
     * 
     * @return array
     */
    protected function getModes(): array
    {
        return [
            ['id' => 'apl', 'name' => 'APL'],
            ['id' => 'asciiarmor', 'name' => 'PGP'],
            ['id' => 'asn.1', 'name' => 'ASN.1'],
            ['id' => 'asterisk', 'name' => 'Asterisk'],
            ['id' => 'brainfuck', 'name' => 'Brainfuck'],
            ['id' => 'clike', 'name' => 'C, C++, C#, Java, Kotlin, Objective-C, Objective-C++, Scala, Squirrel'],
            ['id' => 'cobol', 'name' => 'Cobol'],
            ['id' => 'clojure', 'name' => 'Clojure, ClojureScript, edn'],
            ['id' => 'css', 'name' => 'Closure Stylesheets (GSS), CSS, LESS, SCSS'],
            ['id' => 'cmake', 'name' => 'CMake'],
            ['id' => 'coffeescript', 'name' => 'CoffeeScript'],
            ['id' => 'commonlisp', 'name' => 'Common Lisp'],
            ['id' => 'cypher', 'name' => 'Cypher'],
            ['id' => 'python', 'name' => 'Cython, Python'],
            ['id' => 'crystal', 'name' => 'Crystal'],
            ['id' => 'sql', 'name' => 'CQL, Esper, MariaDB SQL, MS SQL, MySQL, PLSQL, PostgreSQL, SQL, SQLite'],
            ['id' => 'd', 'name' => 'D'],
            ['id' => 'dart', 'name' => 'Dart'],
            ['id' => 'diff', 'name' => 'diff'],
            ['id' => 'django', 'name' => 'Django'],
            ['id' => 'dockerfile', 'name' => 'Dockerfile'],
            ['id' => 'dtd', 'name' => 'DTD'],
            ['id' => 'dylan', 'name' => 'Dylan'],
            ['id' => 'ebnf', 'name' => 'EBNF'],
            ['id' => 'ecl', 'name' => 'ECL'],
            ['id' => 'eiffel', 'name' => 'Eiffel'],
            ['id' => 'elm', 'name' => 'Elm'],
            ['id' => 'htmlembedded', 'name' => 'Embedded JavaScript, Embedded Ruby, ASP.NET, Java Server Pages'],
            ['id' => 'erlang', 'name' => 'Erlang'],
            ['id' => 'factor', 'name' => 'Factor'],
            ['id' => 'fcl', 'name' => 'FCL'],
            ['id' => 'forth', 'name' => 'Forth'],
            ['id' => 'fortran', 'name' => 'Fortran'],
            ['id' => 'mllike', 'name' => 'F#, OCaml, SML'],
            ['id' => 'gas', 'name' => 'Gas'],
            ['id' => 'gherkin', 'name' => 'Gherkin'],
            ['id' => 'gfm', 'name' => 'GitHub Flavored Markdown'],
            ['id' => 'go', 'name' => 'Go'],
            ['id' => 'groovy', 'name' => 'Groovy'],
            ['id' => 'haml', 'name' => 'HAML'],
            ['id' => 'haskell', 'name' => 'Haskell'],
            ['id' => 'haskell-literate', 'name' => 'Haskell (Literate)'],
            ['id' => 'haxe', 'name' => 'Haxe, HXML'],
            ['id' => 'htmlmixed', 'name' => 'HTML'],
            ['id' => 'http', 'name' => 'HTTP'],
            ['id' => 'idl', 'name' => 'IDL'],
            ['id' => 'pug', 'name' => 'Pug'],
            ['id' => 'javascript', 'name' => 'JavaScript, JSON, JSON-LD, TypeScript'],
            ['id' => 'jsx', 'name' => 'JSX, TypeScript-JSX'],
            ['id' => 'jinja2', 'name' => 'Jinja2'],
            ['id' => 'julia', 'name' => 'Julia'],
            ['id' => 'livescript', 'name' => 'LiveScript'],
            ['id' => 'lua', 'name' => 'Lua'],
            ['id' => 'markdown', 'name' => 'Markdown'],
            ['id' => 'mirc', 'name' => 'mIRC'],
            ['id' => 'mathematica', 'name' => 'Mathematica'],
            ['id' => 'modelica', 'name' => 'Modelica'],
            ['id' => 'mumps', 'name' => 'MUMPS'],
            ['id' => 'mbox', 'name' => 'mbox'],
            ['id' => 'nginx', 'name' => 'Nginx'],
            ['id' => 'nsis', 'name' => 'NSIS'],
            ['id' => 'ntriples', 'name' => 'NTriples'],
            ['id' => 'octave', 'name' => 'Octave'],
            ['id' => 'oz', 'name' => 'Oz'],
            ['id' => 'pascal', 'name' => 'Pascal'],
            ['id' => 'pegjs', 'name' => 'PEG.js'],
            ['id' => 'perl', 'name' => 'Perl'],
            ['id' => 'php', 'name' => 'PHP'],
            ['id' => 'pig', 'name' => 'Pig'],
            ['id' => 'null', 'name' => 'Plain Text'],
            ['id' => 'powershell', 'name' => 'PowerShell'],
            ['id' => 'properties', 'name' => 'Properties files'],
            ['id' => 'protobuf', 'name' => 'ProtoBuf'],
            ['id' => 'puppet', 'name' => 'Puppet'],
            ['id' => 'q', 'name' => 'Q'],
            ['id' => 'r', 'name' => 'R'],
            ['id' => 'rst', 'name' => 'reStructuredText'],
            ['id' => 'rpm', 'name' => 'RPM Changes, RPM Spec'],
            ['id' => 'ruby', 'name' => 'Ruby'],
            ['id' => 'rust', 'name' => 'Rust'],
            ['id' => 'sas', 'name' => 'SAS'],
            ['id' => 'sass', 'name' => 'Sass'],
            ['id' => 'scheme', 'name' => 'Scheme'],
            ['id' => 'shell', 'name' => 'Shell'],
            ['id' => 'sieve', 'name' => 'Sieve'],
            ['id' => 'slim', 'name' => 'Slim'],
            ['id' => 'smalltalk', 'name' => 'Smalltalk'],
            ['id' => 'smarty', 'name' => 'Smarty'],
            ['id' => 'solr', 'name' => 'Solr'],
            ['id' => 'soy', 'name' => 'Soy'],
            ['id' => 'sparql', 'name' => 'SPARQL'],
            ['id' => 'spreadsheet', 'name' => 'Spreadsheet'],
            ['id' => 'stylus', 'name' => 'Stylus'],
            ['id' => 'swift', 'name' => 'Swift'],
            ['id' => 'stex', 'name' => 'sTeX, LaTeX'],
            ['id' => 'verilog', 'name' => 'SystemVerilog, Verilog'],
            ['id' => 'tcl', 'name' => 'Tcl'],
            ['id' => 'textile', 'name' => 'Textile'],
            ['id' => 'tiddlywiki', 'name' => 'TiddlyWiki'],
            ['id' => 'tiki', 'name' => 'Tiki wiki'],
            ['id' => 'toml', 'name' => 'TOML'],
            ['id' => 'tornado', 'name' => 'Tornado'],
            ['id' => 'troff', 'name' => 'troff'],
            ['id' => 'ttcn', 'name' => 'TTCN'],
            ['id' => 'ttcn-cfg', 'name' => 'TTCN_CFG'],
            ['id' => 'turtle', 'name' => 'Turtle'],
            ['id' => 'twig', 'name' => 'Twig'],
            ['id' => 'webidl', 'name' => 'Web IDL'],
            ['id' => 'vb', 'name' => 'VB.NET'],
            ['id' => 'vbscript', 'name' => 'VBScript'],
            ['id' => 'velocity', 'name' => 'Velocity'],
            ['id' => 'vhdl', 'name' => 'VHDL'],
            ['id' => 'vue', 'name' => 'Vue.js Component'],
            ['id' => 'xml', 'name' => 'XML'],
            ['id' => 'xquery', 'name' => 'XQuery'],
            ['id' => 'yacas', 'name' => 'Yacas'],
            ['id' => 'yaml', 'name' => 'YAML'],
            ['id' => 'z80', 'name' => 'Z80'],
            ['id' => 'mscgen', 'name' => 'mscgen, xu, msgenny'],
            ['id' => 'wast', 'name' => 'WebAssembly'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function init(): void
    {
        parent::init();

        $this->responsiveConfig = [
            'height < 700' => ['height' => '99%'],
            'width < 550' => ['width' => '99%'],
        ];
        $this->width = 550;
        $this->form->autoScroll = true;
        $this->form->defaults = [
            'labelWidth' => 200,
            'labelAlign' => 'right'
        ];
        $this->form->items = [
            ExtCombo::local('#Theme', 'theme', $this->getThemes(), ['editable' => true, 'value' => 'none']),
            [
                'xtype'  => 'container',
                'layout' => 'column',
                'items'  => [
                    [
                        'columnWidth' => 0.5,
                        'defaults'    => [
                            'labelWidth' => 200,
                            'labelAlign' => 'right'
                        ],
                        'items' => [
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'matchBrackets',
                                'fieldLabel' => '#Match brackets'
                            ],
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'styleActiveLine',
                                'fieldLabel' => '#Active line'
                            ],
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'foldCode',
                                'fieldLabel' => '#Fold code'
                            ]
                        ]
                    ],
                    [
                        'columnWidth' => 0.5,
                        'defaults'    => [
                            'labelWidth' => 200,
                            'labelAlign' => 'right'
                        ],
                        'items' => [
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'lineWrapping',
                                'fieldLabel' => '#Line wrapping'
                            ],
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'lineNumbers',
                                'fieldLabel' => '#Line numbers'
                            ]
                        ]
                    ],
                ]
            ],
            [
                'xtype'  => 'fieldset',
                'title'  => '#Addons',
                'layout' => 'column',
                'items'  => [
                    [
                        'columnWidth' => 0.5,
                        'defaults'    => [
                            'labelWidth' => 170,
                            'labelAlign' => 'right',
                        ],
                        'items' => [
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'xmlFold',
                                'fieldLabel' => '#XML fold'
                            ],
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'markdownFold',
                                'fieldLabel' => '#Markdown fold'
                            ],
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'inddentFold',
                                'fieldLabel' => '#Indent fold'
                            ],
                        ]
                    ],
                    [
                        'columnWidth' => 0.5,
                        'defaults'    => [
                            'labelWidth' => 170,
                            'labelAlign' => 'right',
                        ],
                        'items' => [
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'commentFold',
                                'fieldLabel' => '#Comment fold'
                            ],
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'braceFold',
                                'fieldLabel' => '#Brace fold'
                            ],
                            [
                                'xtype'      => 'checkbox',
                                'ui'         => 'switch',
                                'name'       => 'foldGutter',
                                'fieldLabel' => '#Fold gutter'
                            ]
                        ]
                    ]
                ]
            ],
            [
                'xtype'  => 'fieldset',
                'title'  => '#Modes',
                'items'  => [
                    [
                        'xtype' => 'tagfield',
                        'name'  => 'modes',
                        'width' => '100%',
                        'store' => [],
                        'value' => 'js',
                        'store' => [
                            'fields' => ['id', 'name'],
                            'data'   => $this->getModes()
                        ],
                        'encodeSubmitValue' => true,
                        'displayField'     => 'name',
                        'valueField'       => 'id',
                        'createNewOnEnter' => false,
                        'createNewOnBlur'  => false,
                        'filterPickList'   => true,
                        'queryMode'        => 'local'
                    ]
                ]
            ],
            [
                'xtype' => 'label',
                'ui'    => 'fieldset-comment',
                'html'  => '#for the editor to work correctly, it is necessary that the modes are connected'
            ],
            [
                'xtype'    => 'fieldset',
                'title'    => '#Editor CodeMirror',
                'defaults' => [
                    'xtype'      => 'displayfield',
                    'ui'         => 'parameter',
                    'labelWidth' => 70,
                    'labelAlign' => 'right'
                ],
                'items' => [
                    [
                        'fieldLabel' => '#version',
                        'value'      => '5.59.2'
                    ],
                    [
                        'fieldLabel' => '#site',
                        'value'      => '<a target="_blank" href="https://codemirror.net/">https://codemirror.net/</a>'
                    ]
                ]
            ]
        ];
    }
}