/**
 * Links actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-09
 */
Admin.LinksActions = {

	/**
	 * Load LinksForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadLinksForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var linksFormWindow = Admin.LinksFormWindow.instance();
		linksFormWindow.setTitle(record.get('title'));
		linksFormWindow.show();
		linksFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'links/links_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					linksFormWindow.formPanel.getForm().reset();
					linksFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				linksFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset LinksForm and show window
	 *
	 * @scope variable
	 */
	newLinksForm: function(){

		// Create and show window
		var linksFormWindow = Admin.LinksFormWindow.instance();
		linksFormWindow.setTitle('Nieuwe link');
		linksFormWindow.show();

		// Reset form
		linksFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save LinksForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveLinksForm: function(button, e){

		// Get instances we will use
		var linksGrid = Admin.LinksGrid.instance();
		var linksFormWindow = Admin.LinksFormWindow.instance();
		linksFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Reload grid
				linksGrid.getStore().reload();

				// Hide form window
				linksFormWindow.getEl().unmask();
				linksFormWindow.hide();

			},
			failure: function(form, action){

				// Hide form window
				linksFormWindow.getEl().unmask();
				AdminManager.showFormFailure(action);
			}
		});
	},

	/**
	 * Delete record from LinksGrid
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
					url: ApplicationConfig.adminUrl + 'links/links_grid_delete',
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
			url: ApplicationConfig.adminUrl + 'links/links_grid_order',
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
	}

}