/**
 * Administrators form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-18
 */
Admin.AdministratorsFormWindow = {

	singletonInstance: null,

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.AdministratorsFormWindow.singletonInstance){
			return Admin.AdministratorsFormWindow.singletonInstance;
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
				url: ApplicationConfig.adminUrl + 'administrators/administrators_formwindow_save',
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
						// firstname
						xtype: 'textfield',
						fieldLabel: 'Voornaam*',
						name: 'firstname',
						maxLength: 50,
						allowBlank: false
					},{
						// lastname
						xtype: 'textfield',
						fieldLabel: 'Achternaam*',
						name: 'lastname',
						maxLength: 50,
						allowBlank: false
					},{
						// email
						xtype: 'textfield',
						fieldLabel: 'E-mailadres*',
						name: 'email',
						maxLength: 50,
						vtype: 'email',
						allowBlank: false
					}]
				},{
					xtype: 'fieldset',
					title: 'Toegang',
					defaults: {
						msgTarget: 'side',
						anchor: '-18'
					},
					items: [{
						xtype: 'displayfield',
						hideLabel: true,
						fieldClass: 'admin-form-information',
						html: 'Geef enkel een wachtwoord in indien je deze wil wijzigen of wanneer je een nieuwe beheerder toevoegd'
					},{
						// password
						xtype: 'textfield',
						fieldLabel: 'Wachtwoord',
						name: 'password',
						inputType: 'password',
						minLength: 4,
						maxLength: 50,
						allowBlank: true
					},{
						// password_check
						xtype: 'textfield',
						fieldLabel: 'Wachtwoord <small>(controle)</small>',
						name: 'password_check',
						inputType: 'password',
						maxLength: 50,
						allowBlank: true
					},{
						// online
						xtype: 'checkbox',
						boxLabel: 'Deze beheerder heeft toegang tot het admin panel',
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
				height: 480,
				minWidth: 300,
				minHeight: 300,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.AdministratorsActions.saveAdministratorsForm, formPanel);

			// Set singleton instance and objects
			Admin.AdministratorsFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			return formWindow;
		}
	}

}