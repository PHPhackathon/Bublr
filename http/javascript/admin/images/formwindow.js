/**
 * Images form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-02
 */
Admin.ImagesFormWindow = {

	singletonInstance: null,

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.ImagesFormWindow.singletonInstance){
			return Admin.ImagesFormWindow.singletonInstance;
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
				url: ApplicationConfig.adminUrl + 'images/images_formwindow_save',
				fileUpload: false,
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
						fieldLabel: 'Titel of omschrijving',
						name: 'alt',
						maxLength: 255,
						allowBlank: true
					}]
				}, submitButton]
			});

			// Form window
			var formWindow = new Ext.Window({
				closeAction: 'hide',
				layout: 'fit',
				border: false,
				autoScroll: true,
				width: 450,
				height: 170,
				minWidth: 300,
				minHeight: 170,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.ImagesActions.saveImagesForm, formPanel);

			// Set singleton instance and objects
			Admin.ImagesFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			return formWindow;
		}
	}

};