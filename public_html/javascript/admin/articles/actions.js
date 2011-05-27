/**
 * Articles actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-20
 */
Admin.ArticlesActions = {

	/**
	 * Load ArticlesForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadArticlesForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var articlesFormWindow = Admin.ArticlesFormWindow.instance();
		articlesFormWindow.setTitle(record.get('title'));
		articlesFormWindow.show();
		articlesFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'articles/articles_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					articlesFormWindow.formPanel.getForm().reset();
					articlesFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				articlesFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset ArticlesForm and show window
	 *
	 * @scope variable
	 */
	newArticlesForm: function(){

		// Create and show window
		var articlesFormWindow = Admin.ArticlesFormWindow.instance();
		articlesFormWindow.setTitle('Nieuwe tekst');
		articlesFormWindow.show();

		// Reset form
		articlesFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save ArticlesForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveArticlesForm: function(button, e){

		// Get instances we will use
		var articlesGrid = Admin.ArticlesGrid.instance();
		var articlesFormWindow = Admin.ArticlesFormWindow.instance();
		articlesFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Get existing record or create new
				var recordIndex = articlesGrid.getStore().findExact('id', parseInt(action.result.record.id));
				if(recordIndex >= 0){
					var record = articlesGrid.getStore().getAt(recordIndex);
				}else{
					recordIndex = 0;
					var recordConstructor = Ext.data.Record.create(articlesGrid.getStore().fields.items);
					var record = new recordConstructor();
					articlesGrid.getStore().insert(recordIndex, record);
				}

				// Update record with new data
				if(record){
					record.beginEdit();
					record.fields.each(function(field){
						value = action.result.record[field.name];
						if(!Ext.isEmpty(value)){
							record.set(field.name, field.convert(value));
						}
					});
					record.endEdit();
					record.commit();
				}

				// Regroup records
				articlesGrid.getStore().groupBy('category_sequence', true);

				// Hide form window
				articlesFormWindow.getEl().unmask();
				articlesFormWindow.hide();

				// Highlight updated row
				Ext.get(articlesGrid.getView().getRow(recordIndex)).highlight();
			},
			failure: function(form, action){

				// Hide form window
				articlesFormWindow.getEl().unmask();
				AdminManager.showFormFailure(action);
			}
		});
	},

	/**
	 * Delete record from ArticlesGrid
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
			msg: String.format('Ben je zeker dat je <b>{0}</b> wil verwijderen?', record.get('title')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'articles/articles_grid_delete',
					params: {
						id: record.get('id')
					},
					callback: function(options, success, response){
						var result = Ext.decode(response.responseText);
						if(success && result.success){
							grid.getStore().remove(record);
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
	},

	/**
	 * Save new record order
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param Ext.data.Record draggedRecord
	 * @scope variable
	 */
	saveOrder: function(grid, draggedRecord){

		// Collect ids from records in same grouping field
		var ids = [];
		groupingField = grid.getStore().groupField;
		grid.getStore().each(function(record){
			if(record.get(groupingField) == draggedRecord.get(groupingField)){
				ids.push(record.get('id'));
			}
		});

		// Save new order
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'articles/articles_grid_order',
			params: {
				ids: Ext.encode(ids)
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(!(success && result.success)){
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
	 * Load and show ImagesWindow
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadImagesWindow: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Show imagesWindow
		Admin.ImagesGridWindow.setRelatedData({
			relatedRecord: record,
			relatedId: record.get('id'),
			relatedTable: 'articles'
		});
		Admin.ImagesGridWindow.instance().show();
	}
}