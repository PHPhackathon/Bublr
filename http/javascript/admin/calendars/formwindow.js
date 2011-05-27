/**
 * Calendars form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-01
 */
Admin.CalendarsFormWindow = {

	singletonInstance: null,

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.CalendarsFormWindow.singletonInstance){
			return Admin.CalendarsFormWindow.singletonInstance;
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
				url: ApplicationConfig.adminUrl + 'calendars/calendars_formwindow_save',
				fileUpload: true,
				autoScroll: true,
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
						// month
						xtype: 'datefield',
						fieldLabel: 'Maand*',
						name: 'month',
						format: 'Y-m-d',
						allowBlank: false
					},{
						// description
						xtype: 'ckeditor',
						fieldLabel: 'Omschrijving van activiteiten',
						name: 'description',
						height: 120,
						allowBlank: true
					}]
				},{
					xtype: 'fieldset',
					title: 'Kalender',
					defaults: {
						msgTarget: 'side',
						anchor: '-18'
					},
					items: [{
						xtype: 'displayfield',
						hideLabel: true,
						fieldClass: 'admin-form-information',
						html: 'Selecteer de maandkalender in PDF formaat'
					},{
						// file
						xtype: 'textfield',
						inputType: 'file',
						hideLabel: true,
						name: 'file',
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
						boxLabel: 'Deze kalender is zichtbaar op de site',
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
				width: 650,
				height: 450,
				minWidth: 300,
				minHeight: 300,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.CalendarsActions.saveCalendarsForm, formPanel);

			// Set singleton instance and objects
			Admin.CalendarsFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			return formWindow;
		}
	}

}