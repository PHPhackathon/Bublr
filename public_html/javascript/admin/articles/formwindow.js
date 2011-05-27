/**
 * Articles form window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-20
 */
Admin.ArticlesFormWindow = {

	singletonInstance: null,

	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.ArticlesFormWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.ArticlesFormWindow.singletonInstance){
			return Admin.ArticlesFormWindow.singletonInstance;
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
				url: ApplicationConfig.adminUrl + 'articles/articles_categories_combobox',
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
				url: ApplicationConfig.adminUrl + 'articles/articles_formwindow_save',
				fileUpload: false,
				labelAlign: 'top',
				autoScroll: true,
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
								Admin.ArticlesCategoriesGridWindow.instance().show();
							}
						},
						addButton: {
							hidden: true,
							tooltip: 'Voeg nieuwe categorie toe',
							handler: Admin.ArticlesCategoriesActions.newArticlesCategoriesForm
						}
					},{
						xtype: 'displayfield',
						hideLabel: true,
						fieldClass: 'admin-form-information',
						html: 'De omschrijving wordt gebruikt in de meta-description tag en overzichten binnen een categorie en bevat best een korte samenvatting van de tekst'
					},{
						// description
						xtype: 'textarea',
						fieldLabel: 'Omschrijving',
						name: 'description',
						height: 80,
						allowBlank: true
					},{
						// content
						xtype: 'ckeditor',
						fieldLabel: 'Inhoud*',
						name: 'content',
						height: 150
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
						boxLabel: 'Deze tekst is zichtbaar op de site',
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
				width: 750,
				height: 620,
				minWidth: 500,
				minHeight: 590,
				modal: true,
				items: [formPanel]
			});

			// Handle submit button click
			submitButton.on('click', Admin.ArticlesActions.saveArticlesForm, formPanel);

			// Set singleton instance and objects
			Admin.ArticlesFormWindow.singletonInstance = formWindow;
			formWindow.formPanel = formPanel;
			formWindow.categoriesStore = categoriesStore;
			return formWindow;
		}
	}

}