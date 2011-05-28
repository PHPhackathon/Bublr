/**
 * Calendars actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-01
 */
Admin.CalendarsActions = {

	/**
	 * Load CalendarsForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadCalendarsForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var calendarsFormWindow = Admin.CalendarsFormWindow.instance();
		calendarsFormWindow.setTitle(record.get('month').format('F Y'));
		calendarsFormWindow.show();
		calendarsFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'calendars/calendars_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					calendarsFormWindow.formPanel.getForm().reset();
					calendarsFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				calendarsFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset CalendarsForm and show window
	 *
	 * @scope variable
	 */
	newCalendarsForm: function(){

		// Create and show window
		var calendarsFormWindow = Admin.CalendarsFormWindow.instance();
		calendarsFormWindow.setTitle('Nieuwe kalender');
		calendarsFormWindow.show();

		// Reset form
		calendarsFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save CalendarsForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveCalendarsForm: function(button, e){

		// Get instances we will use
		var calendarsGrid = Admin.CalendarsGrid.instance();
		var calendarsFormWindow = Admin.CalendarsFormWindow.instance();
		calendarsFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Get existing record or create new
				var recordIndex = calendarsGrid.getStore().findExact('id', parseInt(action.result.record.id));
				if(recordIndex >= 0){
					var record = calendarsGrid.getStore().getAt(recordIndex);
				}else{
					recordIndex = 0;
					var recordConstructor = Ext.data.Record.create(calendarsGrid.getStore().fields.items);
					var record = new recordConstructor();
					calendarsGrid.getStore().insert(recordIndex, record);
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
				calendarsFormWindow.getEl().unmask();
				calendarsFormWindow.hide();

				// Highlight updated row
				Ext.get(calendarsGrid.getView().getRow(recordIndex)).highlight();
			},
			failure: function(form, action){

				// Hide form window
				calendarsFormWindow.getEl().unmask();
				AdminManager.showFormFailure(action);
			}
		});
	},

	/**
	 * Delete record from CalendarsGrid
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
			msg: String.format('Ben je zeker dat je de kalender van <b>{0}</b> wil verwijderen?', record.get('month').format('F Y')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'calendars/calendars_grid_delete',
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