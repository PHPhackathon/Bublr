/**
 * CKEditor instance
 * Extends Ext.form.TextArea
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @author https://gist.github.com/642171
 * @created 2010-11-25
 */
Ext.ns('Ext.ux.form');
Ext.ux.form.CKEditor = function(config){
	this.config = config;

	this.config.CKConfig = Ext.apply({
		language: 'nl',
		customConfig: '',
		height: config.height? config.height + 'px' : null,
		toolbar_Basic: [['Format', 'Bold', 'Italic', 'NumberedList', 'BulletedList', '-', 'Link', '-', 'Maximize', 'Source']],
		toolbar: 'Basic',
		forcePasteAsPlainText: true,
		toolbarCanCollapse: false,
		format_tags: 'p;h2;h3;h4',
		uiColor: '#DFE8F6'
	}, this.config.CKConfig);

	Ext.ux.form.CKEditor.superclass.constructor.call(this, this.config);
};

Ext.extend(Ext.ux.form.CKEditor, Ext.form.TextArea, {
	onRender : function(ct, position){
		if(!this.el){
			this.defaultAutoCreate = {
				tag: 'textarea',
				autocomplete: 'off'
			};
		}
		Ext.form.TextArea.superclass.onRender.call(this, ct, position);
		CKEDITOR.replace(this.id, this.config.CKConfig);
	},

	setValue : function(value){
		Ext.form.TextArea.superclass.setValue.call(this,[(value || '')]);
		var ck = CKEDITOR.instances[this.id];
		if (ck){
			ck.setData( value || '' );
		}
	},

	getValue : function(){
		var ck = CKEDITOR.instances[this.id];
		if (ck){
			ck.updateElement();
		}
		return Ext.form.TextArea.superclass.getValue.call(this);
	},

	isDirty: function () {
		if (this.disabled || !this.rendered) {
			return false;
		}
		return String(this.getValue()) !== String(this.originalValue);
	},

	getRawValue : function(){
		var ck = CKEDITOR.instances[this.id];
		if (ck){
			ck.updateElement();
		}
		return Ext.form.TextArea.superclass.getRawValue.call(this);
	},

	destroyInstance: function(){
		var ck = CKEDITOR.instances[this.id];
		if (ck){
			delete ck;
		}
	}
});
Ext.reg('ckeditor', Ext.ux.form.CKEditor);