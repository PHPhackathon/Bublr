/**
 * Photoalbums actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-02
 */
Admin.PhotoalbumsActions = {

	/**
	 * Load PhotoalbumsForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadPhotoalbumsForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var photoalbumsFormWindow = Admin.PhotoalbumsFormWindow.instance();
		photoalbumsFormWindow.setTitle(record.get('title'));
		photoalbumsFormWindow.show();
		photoalbumsFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'photoalbums/photoalbums_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					photoalbumsFormWindow.formPanel.getForm().reset();
					photoalbumsFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				photoalbumsFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset PhotoalbumsForm and show window
	 *
	 * @scope variable
	 */
	newPhotoalbumsForm: function(){

		// Create and show window
		var photoalbumsFormWindow = Admin.PhotoalbumsFormWindow.instance();
		photoalbumsFormWindow.setTitle('Nieuw fotoalbum');
		photoalbumsFormWindow.show();

		// Reset form
		photoalbumsFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save PhotoalbumsForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	savePhotoalbumsForm: function(button, e){

		// Get instances we will use
		var photoalbumsGrid = Admin.PhotoalbumsGrid.instance();
		var photoalbumsFormWindow = Admin.PhotoalbumsFormWindow.instance();
		photoalbumsFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Get existing record or create new
				var isNewRecord = false;
				var recordIndex = photoalbumsGrid.getStore().findExact('id', parseInt(action.result.record.id));
				if(recordIndex >= 0){
					var record = photoalbumsGrid.getStore().getAt(recordIndex);
				}else{
					recordIndex = 0;
					isNewRecord = true;
					var recordConstructor = Ext.data.Record.create(photoalbumsGrid.getStore().fields.items);
					var record = new recordConstructor();
					photoalbumsGrid.getStore().insert(recordIndex, record);
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
				photoalbumsFormWindow.getEl().unmask();
				photoalbumsFormWindow.hide();

				// Highlight updated row
				Ext.get(photoalbumsGrid.getView().getRow(recordIndex)).highlight();

				// Show ImagesGridWindow if new photoalbum
				if(isNewRecord){
					Admin.PhotoalbumsActions.loadImagesWindow(photoalbumsGrid, recordIndex);
				}

			},
			failure: function(form, action){

				// Hide form window
				photoalbumsFormWindow.getEl().unmask();
				AdminManager.showFormFailure(action);
			}
		});
	},

	/**
	 * Delete record from PhotoalbumsGrid
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
			msg: String.format('Ben je zeker dat je <b>{0}</b> en alle onderliggende foto\'s wil verwijderen?', record.get('title')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'photoalbums/photoalbums_grid_delete',
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
			relatedTable: 'photoalbums'
		});
		Admin.ImagesGridWindow.instance().show();

	}

};