/**
 * Links categories form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-12
 */
Admin.LinksCategoriesFormWindow = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.LinksCategoriesFormWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.LinksCategoriesFormWindow.singletonInstance){
			return Admin.LinksCategoriesFormWindow.singletonInstance;
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
				url: ApplicationConfig.adminUrl + 'links/links_categories_formwindow_save',
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
				height: 250,
				minWidth: 300,
				minHeight: 250,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.LinksCategoriesActions.saveLinksCategoriesForm, formPanel);

			// Set singleton instance and objects
			Admin.LinksCategoriesFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			return formWindow;
		}
	}

}