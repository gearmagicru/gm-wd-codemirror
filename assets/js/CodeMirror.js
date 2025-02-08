/**
 *  ������ "CodeMirror".
 
 * ���� ���� �������� ������ GM Panel.
 *
 * Copyright (c) 2015 ���-������ GearMagic
 * 
 * Contact: https://gearmagic.ru
 *
 * @author    Anton Tivonenko
 * @copyright (c) 2015, by Anton Tivonenko, anton.tivonenko@gmail.com
 * @date      Oct 01, 2015
 * @version   $Id: 1.0 $
 *
 * @license Catalog.js is licensed under the terms of the Open Source
 * LGPL 3.0 license. Commercial use is permitted to the extent that the
 * code/component(s) do NOT become part of another Open Source or Commercially 
 * development library or toolkit without explicit permission.
 */

/**
 * @class Gm.widget.CodeMirror
 * ����� ������� ��������� ���� CodeMirror.
 */
Ext.define('Gm.wd.codemirror.CodeMirror', {
	extend: 'Ext.panel.Panel',
	alias: 'widget.codemirror',
	mixins: {
		field: 'Ext.form.field.Field'
	},
    height: '100%',
    width: '100%',
    value: '',
    /**
     * @cfg {Object} options 
     * ����� ������������ ���������.
     */
    options: {
        lineNumbers: true,
        fixedGutter: true,
        defaultTheme: 'default',
        mode: 'javascript'
    },

    /**
     * ���������� ������, ���� ��� ���� ���� �������� � ��������� �������� 
     * (� �� ���������), � ��������� ������ - ����.
     * @return {Boolean}
     */
	isDirty: function () {
		return this.dirty;
	},

	setDirty: function (dirty) {
		this.dirty = dirty;
		this.fireEvent('dirtychange', dirty);
	},

    /**
     * ������������� �������� ���� (����� ���������).
     * @param {String} value ��������.
     */
	setValue: function (value) {
		if (Ext.isFunction(this.editor.setValue)) {
			this.editor.setValue(value);
			this._originalValue = value;
			this.setDirty(false);
		}
	},

    /**
     * ���������� �������� ���� (����� ���������).
     * @return {String}
     */
	getValue: function () {
		if (Ext.isFunction(this.editor.getValue)) {
			return this.editor.getValue();
		}
		return 'Unable to get value';
	},

    /**
     * ���������� ��������� ������� � ������.
     * @param {String} string
     * @param {Number}
     */
	getCursor: function (string) {
		return this.editor.getCursor(string);
	},

    /**
     * ���������� �������� ���� (����� ���������).
     */
	reset: function () {
		this.editor.setValue('');
	},

    /**
     * ������� ������ � ������� ���������� �������.
     * @param {String} data
     * @param {Boolean} newLine ���� true, ������ �� ��������� ������.
     * @param {Boolean} endLine ���� true, ����� �� ��������� ������.
     */
	insertText: function (data, newLine, endLine) {
		var doc = this.editor.getDoc();
		var cursor = doc.getCursor(); // �������� ����� ������ � ������� �������
		var line = doc.getLine(cursor.line); // �������� ���������� ������
		var pos = { // ������� ����� ������, ����� �������� ��������� ��������� ���������
			line: cursor.line,
			ch: line.length - 1 // ���������� ������� ������� � ����� ������
		};
		var text = '';

		if (newLine) {
			text = '\n';
		}
		text = text + data;
		if (endLine) {
			text = text + '\n';
		}
		doc.replaceRange(text, pos);
	},

    // private 
	initComponent: function () {
		var me = this;
        me.editor = Ext.widget('box');

		me._originalValue = me.value;
		Ext.apply(me, { items: [me.editor] });

		me.callParent(arguments);
	},

    /**
     * ���������� ������� ������.
     * @cfg {Object}
     */
	listeners: {
        /**
         * ����������� ����� ���������� ���������� � ���������.
         * @param {Gm.widget.CodeMirror} me
         * @param {Ext.container.Container} container
         * @param {Number} pos
         * @param {Object} eOpts
         */
	    added: function (me, container, pos, eOpts) {
            container.addListener('resize', function (ct, width, height, oldWidth, oldHeight, eOpts) {
                me.setHeight(ct.body.dom.clientHeight);
            });
	    },
        /**
         * ����������� ����� ���������� ���������� � ���������.
         * @param {Gm.widget.CodeMirror} me
         * @param {Object} eOpts
         */
        afterrender: function (me, eOpts) {
          this.initialiseCodeMirror();
	   }
	},

    /**
     * ������������� ���������� ���������.
     */
	initialiseCodeMirror: function () {
		var me = this,
            options = Ext.applyIf(me.options, {
            lineNumbers: true,
            defaultTheme: 'default',
            mode: 'javascript', 
            gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"] // �������� ������������ ����
        });
        options.value = me.value;

		me.editor = new CodeMirror(document.getElementById(me.editor.id), options);
		me.editor.setSize(me.width, me.height);
		me.registerEvents();
		me.editor.refresh();
	},

    // private
	refresh: function () {
		this.editor.refresh();
	},

    // private
	setSize: function (width, height) {
        this.callParent(arguments);

		this.editor.setSize(width, height);
	},

    // private
	registerEvents: function () {
		var me = this;
        me.editor.on('change', function (editor, changedObject) {
			me.updateFieldDirty(editor, me);
			me.fireEvent('change', editor, changedObject);
		});
		me.editor.on('changes', function (editor, changes) {
			me.fireEvent('changes', editor, changes);
		});
		me.editor.on('beforeChange', function (editor, changedObject) {
			me.fireEvent('beforeChange', editor, changedObject);
		});
		me.editor.on('focus', function (editor) {
			me.fireEvent('focus', editor);
		});
	},

    // private
	updateFieldDirty: function (editor, me) {
		me.setDirty(me._originalValue != editor.getValue());
	}
});