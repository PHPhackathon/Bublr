/**
 * Members categories form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-30
 */
Admin.MembersCategoriesFormWindow = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.MembersCategoriesFormWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.MembersCategoriesFormWindow.singletonInstance){
			return Admin.MembersCategoriesFormWindow.singletonInstance;
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
				url: ApplicationConfig.adminUrl + 'members/members_categories_formwindow_save',
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
						fieldLabel: 'Naam*',
						name: 'title',
						maxLength: 255,
						allowBlank: false
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
						boxLabel: 'Deze categorie is zichtbaar op de site',
						name: 'online',
						inputValue: 1,
						checked: true,
						hideLabel: true
					},{
						// sidebar
						xtype: 'checkbox',
						boxLabel: 'Toon leden van deze categorie in zijbalk',
						name: 'sidebar',
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
				width: 450,
				height: 280,
				minWidth: 300,
				minHeight: 250,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.MembersCategoriesActions.saveMembersCategoriesForm, formPanel);

			// Set singleton instance and objects
			Admin.MembersCategoriesFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			return formWindow;
		}
	}

}