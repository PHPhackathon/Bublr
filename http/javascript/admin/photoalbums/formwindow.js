/**
 * Photoalbums form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-02
 */
Admin.PhotoalbumsFormWindow = {

	singletonInstance: null,

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.PhotoalbumsFormWindow.singletonInstance){
			return Admin.PhotoalbumsFormWindow.singletonInstance;
		}else{

			// Submit button
			var submitButton = new Ext.Button({
				icon: ApplicationConfig.siteUrl + 'images/icons/disk-return.png',
				text: 'Opslaan',
				anchor: null,
				style: 'float:right',
				type: 'submit'
			});

			// Formpanel
			var formPanel = new Ext.form.FormPanel({
				url: ApplicationConfig.adminUrl + 'photoalbums/photoalbums_formwindow_save',
				fileUpload: true,
				labelAlign: 'top',
				frame: true,
				defaults: {
					labelSeparator: ''
				},
				items: [{
					xtype: 'fieldset',
					title: 'Algemene gegevens',
					defaults: {
						msgTarget: 'side',
						anchor: '-18'
					},
					items: [{
						// id
						xtype: 'hidden',
						name: 'id'
					},{
						// title
						xtype: 'textfield',
						fieldLabel: 'Titel*',
						name: 'title',
						maxLength: 255,
						allowBlank: false
					},{
						// date
						xtype: 'datefield',
						fieldLabel: 'Datum*',
						name: 'date',
						format: 'Y-m-d',
						allowBlank: false
					},{
						// description
						xtype: 'textarea',
						fieldLabel: 'Korte omschrijving',
						name: 'description',
						height: 100,
						allowBlank: true
					}]
				},{
					xtype: 'fieldset',
					title: 'Status',
					defaults: {
						msgTarget: 'side',
						anchor: '-18'
					},
					items: [{
						// online
						xtype: 'checkbox',
						boxLabel: 'Dit fotoalbum is zichtbaar op de site',
						name: 'online',
						inputValue: 1,
						checked: true,
						hideLabel: true
					}]
				}, submitButton]
			});

			// Form window
			var formWindow = new Ext.Window({
				closeAction: 'hide',
				layout: 'fit',
				border: false,
				autoScroll: true,
				width: 650,
				height: 420,
				minWidth: 300,
				minHeight: 300,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.PhotoalbumsActions.savePhotoalbumsForm, formPanel);

			// Set singleton instance and objects
			Admin.PhotoalbumsFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			return formWindow;
		}
	}

};