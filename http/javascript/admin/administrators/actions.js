/**
 * Administrators actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-17
 */
Admin.AdministratorsActions = {

	/**
	 * Load AdministratorsForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadAdministratorsForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var administratorsFormWindow = Admin.AdministratorsFormWindow.instance();
		administratorsFormWindow.setTitle(String.format('{0} {1}', record.get('firstname'), record.get('lastname')));
		administratorsFormWindow.show();
		administratorsFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'administrators/administrators_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){									
					administratorsFormWindow.formPanel.getForm().reset();
					administratorsFormWindow.formPanel.getForm().setValues(result.record);		
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				administratorsFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset AdministratorsForm and show window
	 *
	 * @scope variable
	 */
	newAdministratorsForm: function(){

		// Create and show window
		var administratorsFormWindow = Admin.AdministratorsFormWindow.instance();
		administratorsFormWindow.setTitle('Nieuwe beheerder');
		administratorsFormWindow.show();

		// Reset form
		administratorsFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save AdministratorsForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveAdministratorsForm: function(button, e){

		// Get instances we will use
		var administratorsGrid = Admin.AdministratorsGrid.instance();
		var administratorsFormWindow = Admin.AdministratorsFormWindow.instance();
		administratorsFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Get existing record or create new
				var recordIndex = administratorsGrid.getStore().findExact('id', parseInt(action.result.record.id));
				if(recordIndex >= 0){
					var record = administratorsGrid.getStore().getAt(recordIndex);
				}else{
					recordIndex = 0;
					var recordConstructor = Ext.data.Record.create(administratorsGrid.getStore().fields.items);
					var record = new recordConstructor();
					administratorsGrid.getStore().insert(recordIndex, record);
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

				// Hide form window
				administratorsFormWindow.getEl().unmask();
				administratorsFormWindow.hide();

				// Highlight updated row
				Ext.get(administratorsGrid.getView().getRow(recordIndex)).highlight();
			},
			failure: function(form, action){

				// Hide form window
				administratorsFormWindow.getEl().unmask();
				AdminManager.showFormFailure(action);
			}
		});
	},

	/**
	 * Delete record from AdministratorsGrid
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
			msg: String.format('Ben je zeker dat je <b>{0} {1}</b> wil verwijderen?', record.get('firstname'), record.get('lastname')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'administrators/administrators_grid_delete',
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
	}

}