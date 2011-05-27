/**
 * Members form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-30
 */
Admin.MembersFormWindow = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.MembersFormWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.MembersFormWindow.singletonInstance){
			return Admin.MembersFormWindow.singletonInstance;
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
				url: ApplicationConfig.adminUrl + 'members/members_categories_combobox',
				autoLoad: true,
				remoteSort: true,
				root: 'records',
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'title'}
				]
			});

			// Formpanel
			var formPanel = new Ext.form.FormPanel({
				url: ApplicationConfig.adminUrl + 'members/members_formwindow_save',
				fileUpload: true,
				labelAlign: 'top',
				autoScroll: true,
				frame: true,
				defaults: {
					labelSeparator: ''
				},
				items: [{
					xtype: 'fieldset',
					title: 'Persoonlijke gegevens',
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
						// gender
						xtype: 'radiogroup',
						fieldLabel: 'Geslacht',
						columns: 5,
						items: [{
							inputValue: 'm',
							boxLabel: 'Man',
							name: 'gender'
						},{
							inputValue: 'f',
							boxLabel: 'Vrouw',
							name: 'gender'
						}]
					},{
						// birthdate
						xtype: 'datefield',
						fieldLabel: 'Geboortedatum',
						name: 'birthdate',
						format: 'Y-m-d'
					},{
						// about
						xtype: 'textfield',
						fieldLabel: 'Over / uitspraak / weetje',
						name: 'about',
						maxLength: 255,
						allowBlank: true
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
								Admin.MembersCategoriesGridWindow.instance().show();
							}
						},
						addButton: {
							tooltip: 'Voeg nieuwe categorie toe',
							handler: Admin.MembersCategoriesActions.newMembersCategoriesForm
						}
					}]
				},{
					xtype: 'fieldset',
					title: 'Contactgegevens',
					defaults: {
						msgTarget: 'side',
						anchor: '-18'
					},
					items: [{
						// street
						xtype: 'textfield',
						fieldLabel: 'Straat + huisnummer',
						name: 'street',
						maxLength: 50,
						allowBlank: true
					},{
						// postal_code
						xtype: 'textfield',
						fieldLabel: 'Postcode',
						name: 'postal_code',
						maxLength: 10,
						allowBlank: true,
						anchor: null,
						width: 100
					},{
						// city
						xtype: 'textfield',
						fieldLabel: 'Gemeente',
						name: 'city',
						maxLength: 50,
						allowBlank: true
					},{
						// phone
						xtype: 'textfield',
						fieldLabel: 'Telefoon / GSM',
						name: 'phone',
						maxLength: 50,
						allowBlank: true
					},{
						// email
						xtype: 'textfield',
						fieldLabel: 'E-mailadres',
						name: 'email',
						maxLength: 50,
						vtype: 'email',
						allowBlank: true
					}]
				},{
					xtype: 'fieldset',
					title: 'Foto',
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
						relatedTable: 'members',
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
						// payed
						xtype: 'checkbox',
						boxLabel: 'Dit lid heeft huidig werkjaar betaald',
						name: 'payed',
						inputValue: 1,
						hideLabel: true
					},{
						// online
						xtype: 'checkbox',
						boxLabel: 'Dit lid is zichtbaar op de site (indien leider)',
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
				height: 500,
				minWidth: 300,
				minHeight: 350,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.MembersActions.saveMembersForm, formPanel);

			// Set singleton instance and objects
			Admin.MembersFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			formWindow.categoriesStore = categoriesStore;
			return formWindow;
		}
	}

};