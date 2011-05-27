/**
 * Links form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-09
 */
Admin.LinksFormWindow = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.LinksFormWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.LinksFormWindow.singletonInstance){
			return Admin.LinksFormWindow.singletonInstance;
		}else{

			// Submit button
			var submitButton = new Ext.Button({
				icon: ApplicationConfig.siteUrl + 'images/icons/disk-return.png',
				text: 'Opslaan',
				anchor: null,
				style: 'float:right',
				type: 'submit'
			});
			
			// Categories store
			var categoriesStore = new Ext.data.JsonStore({
				url: ApplicationConfig.adminUrl + 'links/links_categories_combobox',
				autoLoad: true,
				remoteSort: true,
				root: 'records',
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'title'}
				]
			})

			// Formpanel
			var formPanel = new Ext.form.FormPanel({
				url: ApplicationConfig.adminUrl + 'links/links_formwindow_save',
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
						// url
						xtype: 'textfield',
						fieldLabel: 'Url*',
						name: 'url',
						maxLength: 255,
						emptyText: 'http://...',
						vtype: 'url',
						allowBlank: false
					},{
						// category_id
						xtype: 'comboaddeditdeletefield',
						fieldLabel: 'Categorie*',
						comboBox: {
							store: categoriesStore,
							name: 'category_id',
							hiddenName: 'category_id',
							displayField: 'title',
							valueField: 'id',
							allowBlank: false
						},
						deleteButton: {
							hidden: true
						},
						editButton: {
							handler: function(){
								Admin.LinksCategoriesGridWindow.instance().show();
							}
						},
						addButton: {
							tooltip: 'Voeg nieuwe categorie toe',
							handler: Admin.LinksCategoriesActions.newLinksCategoriesForm
						}
					}]
				},{
					xtype: 'fieldset',
					title: 'Logo',
					defaults: {
						msgTarget: 'side',
						anchor: '-18'
					},
					items: [{
						xtype: 'displayfield',
						hideLabel: true,
						fieldClass: 'admin-form-information',
						html: 'Selecteer een afbeelding in het formaat jpg, png of gif.'
					},{
						// image
						xtype: 'imagefield',
						hideLabel: true,
						name: 'image',
						relatedTable: 'links',
						previewField: 'image_filename',
						idField: 'image_id',
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
						boxLabel: 'Deze link is zichtbaar op de site',
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
				height: 500,
				minWidth: 300,
				minHeight: 350,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.LinksActions.saveLinksForm, formPanel);

			// Set singleton instance and objects
			Admin.LinksFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			formWindow.categoriesStore = categoriesStore;
			return formWindow;
		}
	}

}