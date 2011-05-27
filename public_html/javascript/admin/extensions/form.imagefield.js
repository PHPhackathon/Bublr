/**
 * Form field with file field, image preview and delete button
 * Extends Ext.Panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-01
 */
Ext.ns('Ext.ux.form');
Ext.ux.form.ImageField = Ext.extend(Ext.Panel, {

	/**
	 * Basic panel configuration
	 *
	 */
	layout: 'column',
	formPanel: null,
	fileField: null,
	deleteButton: null,
	previewImage: null,
	previewField: null,
	idField: null,
	relatedTable: null,

	/**
	 * Initialize panel and fields
	 *
	 */
	initComponent: function(){

		// Find form panel
		this.formPanel = this.findParentByType('form');

		// File field
		var fileField = new Ext.form.TextField({
			inputType: 'file',
			name: this.initialConfig.name,
			columnWidth: 1
		});

		// Delete button
		var deleteButton = new Ext.Button({
			width: 22,
			hideMode: 'visibility',
			tooltip: 'Verwijderen...',
			icon: ApplicationConfig.siteUrl + 'images/icons/minus-small.png',
			handler: this.deleteImage,
			scope: this
		});

		// Preview image
		var previewImage = new Ext.Panel({
			xtype: 'panel',
			width: 50,
			height: 50,
			html: '<img src="" width="50" height="50" />'
		});

		// Hidden fields
		var previewField = new Ext.form.Hidden({name: this.initialConfig.previewField});
		var idField = new Ext.form.Hidden({name: this.initialConfig.idField});

		// Assign items to panel
		this.items = [
			fileField,
			deleteButton,
			{width:10, html: '&nbsp;'},
			previewImage,
			previewField,
			idField
		];

		// Parent initialization
		Ext.apply(this, this.initialConfig);
		Ext.ux.form.ImageField.superclass.initComponent.apply(this, arguments);

		// Attach fields to panel
		this.fileField = fileField;
		this.deleteButton = deleteButton;
		this.previewImage = previewImage;
		this.previewField = previewField;
		this.idField = idField;

		// Set preview image
		previewField.setValue = function(value){

			// Set preview image
			var image = this.previewImage.body.query('img')[0];
			image.src = ApplicationConfig.siteUrl + 'images/admin_imagefield_thumbnail/' + this.relatedTable + '/' + value;
			image.qtip = String.format('<img src=\'{0}images/admin_imagefield_preview/{1}/{2}\' width=\'150\' height=\'200\' />', ApplicationConfig.siteUrl, this.relatedTable, value);

			// Show or hide delete button
			this.deleteButton.setVisible(!!value);

			// Call parent function
			return Ext.form.Hidden.superclass.setValue.apply(this.previewfield, [value]);
		}.createDelegate(this);

	},

	/**
	 * Ask for confirmation and delete current image
	 */
	deleteImage: function(){
		if(!this.idField.getValue()) return;
		Ext.Msg.show({
			title: 'Bevestiging',
			msg: 'Ben je zeker dat je deze afbeelding wil verwijderen?',
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				this.formPanel.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'images/image_field_delete',
					params: {
						id: this.idField.getValue()
					},
					callback: function(options, success, response){
						var result = Ext.decode(response.responseText);
						if(success && result.success){
							this.fileField.setValue(null);
							this.previewField.setValue(null);
							this.idField.setValue(null);
						}else{
							Ext.Msg.show({
								title: 'Fout',
								msg: result.message || 'Er is een onbekende fout opgetreden',
								buttons: Ext.Msg.OK,
								icon: Ext.Msg.ERROR
							});
						}
						this.formPanel.el.unmask();
					},
					scope: this
				});
			},
			icon: Ext.MessageBox.WARNING,
			scope: this
		});
	}

});
Ext.reg('imagefield', Ext.ux.form.ImageField);