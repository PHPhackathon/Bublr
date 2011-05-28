/**
 * Members actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-30
 */
Admin.MembersActions = {

	/**
	 * Load MembersForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadMembersForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var membersFormWindow = Admin.MembersFormWindow.instance();
		membersFormWindow.setTitle(String.format('{0} {1}', record.get('firstname'), record.get('lastname')));
		membersFormWindow.show();
		membersFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'members/members_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					membersFormWindow.formPanel.getForm().reset();
					membersFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				membersFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset MembersForm and show window
	 *
	 * @scope variable
	 */
	newMembersForm: function(){

		// Create and show window
		var membersFormWindow = Admin.MembersFormWindow.instance();
		membersFormWindow.setTitle('Nieuw lid');
		membersFormWindow.show();

		// Reset form
		membersFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save MembersForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveMembersForm: function(button, e){

		// Get instances we will use
		var membersGrid = Admin.MembersGrid.instance();
		var membersFormWindow = Admin.MembersFormWindow.instance();
		membersFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Reload grid
				membersGrid.getStore().reload();

				// Hide form window
				membersFormWindow.getEl().unmask();
				membersFormWindow.hide();

			},
			failure: function(form, action){

				// Hide form window
				membersFormWindow.getEl().unmask();
				AdminManager.showFormFailure(action);
			}
		});
	},

	/**
	 * Delete record from MembersGrid
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
					url: ApplicationConfig.adminUrl + 'members/members_grid_delete',
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
			url: ApplicationConfig.adminUrl + 'members/members_grid_order',
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
	 * Export data from MembersGrid to CSV file
	 *
	 * @scope variable
	 */
	exportCSV: function(){

		var grid = Admin.MembersGrid.instance();
		grid.getEl().mask('Even geduld aub...');
		window.location = ApplicationConfig.adminUrl + 'members/members_grid_export';
		grid.getEl().unmask.defer(5000, grid.getEl());
	}

};