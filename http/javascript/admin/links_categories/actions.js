/**
 * Links categories actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-12
 */
Admin.LinksCategoriesActions = {

	/**
	 * Load LinksCategoriesForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadLinksCategoriesForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var linksCategoriesFormWindow = Admin.LinksCategoriesFormWindow.instance();
		linksCategoriesFormWindow.setTitle(record.get('title'));
		linksCategoriesFormWindow.show();
		linksCategoriesFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'links/links_categories_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					linksCategoriesFormWindow.formPanel.getForm().reset();
					linksCategoriesFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				linksCategoriesFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset LinksCategoriesForm and show window
	 *
	 * @scope variable
	 */
	newLinksCategoriesForm: function(){

		// Create and show window
		var linksCategoriesFormWindow = Admin.LinksCategoriesFormWindow.instance();
		linksCategoriesFormWindow.setTitle('Nieuwe categorie');
		linksCategoriesFormWindow.show();

		// Reset form
		linksCategoriesFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save LinksCategoriesForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveLinksCategoriesForm: function(button, e){

		// Get instances we will use
		var linksCategoriesGrid = Admin.LinksCategoriesGrid.instance();
		var linksCategoriesFormWindow = Admin.LinksCategoriesFormWindow.instance();
		linksCategoriesFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Reload categories grid
				linksCategoriesGrid.getStore().reload();

				// Reload links grid
				if(Admin.LinksGrid.hasInstance()){
					Admin.LinksGrid.instance().getStore().reload();
				}

				// Reload links form categories combobox
				if(Admin.LinksFormWindow.hasInstance()){
					var existingValue = Admin.LinksFormWindow.instance().formPanel.getForm().getFieldValues().category_id;
					Admin.LinksFormWindow.instance().categoriesStore.reload({
						callback: function(){
							if(existingValue){
								Admin.LinksFormWindow.instance().formPanel.getForm().setValues({
									category_id: existingValue
								});
							}
						}
					});
				}

				// Hide form window
				linksCategoriesFormWindow.getEl().unmask();
				linksCategoriesFormWindow.hide();

			},
			failure: function(form, action){

				// Hide form window
				linksCategoriesFormWindow.getEl().unmask();
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
			url: ApplicationConfig.adminUrl + 'links/links_categories_grid_order',
			params: {
				ids: Ext.encode(ids)
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){

					// Reload links grid
					if(Admin.LinksGrid.hasInstance()){
						Admin.LinksGrid.instance().getStore().reload();
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
	 * Delete record from LinksCategoriesGrid
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
			msg: String.format('Ben je zeker dat je <b>{0}</b> en alle onderliggende links wil verwijderen?', record.get('title')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'links/links_categories_grid_delete',
					params: {
						id: record.get('id')
					},
					callback: function(options, success, response){
						var result = Ext.decode(response.responseText);
						if(success && result.success){

							// Remove record from grid
							grid.getStore().remove(record);

							// Hide links form window if record is zombie
							if(Admin.LinksFormWindow.hasInstance()){
								var selectedValue = Admin.LinksFormWindow.instance().formPanel.getForm().getFieldValues().category_id;
								if(selectedValue == record.get('id')){
									Admin.LinksFormWindow.instance().hide();
								}
							}
							
							// Reload links grid
							if(Admin.LinksGrid.hasInstance()){
								Admin.LinksGrid.instance().getStore().reload();
							}

							// Reload links form categories combobox
							if(Admin.LinksFormWindow.hasInstance()){
								Admin.LinksFormWindow.instance().formPanel.getForm().setValues({
									category_id: null
								});
								Admin.LinksFormWindow.instance().categoriesStore.reload();
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