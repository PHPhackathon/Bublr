/**
 * Articles categories actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-02-28
 */
Admin.ArticlesCategoriesActions = {

	/**
	 * Load ArticlesCategoriesForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadArticlesCategoriesForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var articlesCategoriesFormWindow = Admin.ArticlesCategoriesFormWindow.instance();
		articlesCategoriesFormWindow.setTitle(record.get('title'));
		articlesCategoriesFormWindow.show();
		articlesCategoriesFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'articles/articles_categories_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					articlesCategoriesFormWindow.formPanel.getForm().reset();
					articlesCategoriesFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				articlesCategoriesFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset ArticlesCategoriesForm and show window
	 *
	 * @scope variable
	 */
	newArticlesCategoriesForm: function(){

		// Create and show window
		var articlesCategoriesFormWindow = Admin.ArticlesCategoriesFormWindow.instance();
		articlesCategoriesFormWindow.setTitle('Nieuwe categorie');
		articlesCategoriesFormWindow.show();

		// Reset form
		articlesCategoriesFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save ArticlesCategoriesForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveArticlesCategoriesForm: function(button, e){

		// Get instances we will use
		var articlesCategoriesGrid = Admin.ArticlesCategoriesGrid.instance();
		var articlesCategoriesFormWindow = Admin.ArticlesCategoriesFormWindow.instance();
		articlesCategoriesFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Reload categories grid
				articlesCategoriesGrid.getStore().reload();

				// Reload articles grid
				if(Admin.ArticlesGrid.hasInstance()){
					Admin.ArticlesGrid.instance().getStore().reload();
				}

				// Reload articles form categories combobox
				if(Admin.ArticlesFormWindow.hasInstance()){
					var existingValue = Admin.ArticlesFormWindow.instance().formPanel.getForm().getFieldValues().category_id;
					Admin.ArticlesFormWindow.instance().categoriesStore.reload({
						callback: function(){
							if(existingValue){
								Admin.ArticlesFormWindow.instance().formPanel.getForm().setValues({
									category_id: existingValue
								});
							}
						}
					});
				}

				// Hide form window
				articlesCategoriesFormWindow.getEl().unmask();
				articlesCategoriesFormWindow.hide();

			},
			failure: function(form, action){

				// Hide form window
				articlesCategoriesFormWindow.getEl().unmask();
				AdminManager.showFormFailure(action);
			}
		});
	},

	/**
	 * Save new record order
	 *
	 * @param Ext.grid.GridPanel grid
	 * @scope variable
	 */
	saveOrder: function(grid){

		// Collect ids
		var ids = [];
		grid.getStore().each(function(record){
			ids.push(record.get('id'));
		});

		// Save new order
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'articles/articles_categories_grid_order',
			params: {
				ids: Ext.encode(ids)
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){

					// Reload articles grid
					if(Admin.ArticlesGrid.hasInstance()){
						Admin.ArticlesGrid.instance().getStore().reload();
					}

				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
			}
		});
	},

	/**
	 * Delete record from ArticlesCategoriesGrid
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	deleteRecord: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Show confirmation
		Ext.Msg.show({
			title: 'Bevestiging',
			msg: String.format('Ben je zeker dat je <b>{0}</b> en alle onderliggende teksten wil verwijderen?', record.get('title')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'articles/articles_categories_grid_delete',
					params: {
						id: record.get('id')
					},
					callback: function(options, success, response){
						var result = Ext.decode(response.responseText);
						if(success && result.success){

							// Remove record from grid
							grid.getStore().remove(record);

							// Hide articles form window if record is zombie
							if(Admin.ArticlesFormWindow.hasInstance()){
								var selectedValue = Admin.ArticlesFormWindow.instance().formPanel.getForm().getFieldValues().category_id;
								if(selectedValue == record.get('id')){
									Admin.ArticlesFormWindow.instance().hide();
								}
							}
							
							// Reload articles grid
							if(Admin.ArticlesGrid.hasInstance()){
								Admin.ArticlesGrid.instance().getStore().reload();
							}

							// Reload articles form categories combobox
							if(Admin.ArticlesFormWindow.hasInstance()){
								Admin.ArticlesFormWindow.instance().formPanel.getForm().setValues({
									category_id: null
								});
								Admin.ArticlesFormWindow.instance().categoriesStore.reload();
							}

						}else{
							Ext.Msg.show({
								title: 'Fout',
								msg: result.message || 'Er is een onbekende fout opgetreden',
								buttons: Ext.Msg.OK,
								icon: Ext.Msg.ERROR
							});
						}
						grid.el.unmask();
					}
				});
			},
			icon: Ext.MessageBox.WARNING
		});
	}
}