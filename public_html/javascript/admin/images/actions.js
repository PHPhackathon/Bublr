/**
 * Images actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-03
 */
Admin.ImagesActions = {

	/**
	 * Load ImagesForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadImagesForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var imagesFormWindow = Admin.ImagesFormWindow.instance();
		imagesFormWindow.setTitle(record.get('alt') || record.get('filename'));
		imagesFormWindow.show();
		imagesFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'images/images_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					imagesFormWindow.formPanel.getForm().reset();
					imagesFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				imagesFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Show Awesome Uploader window to upload new images
	 *
	 * @param string relatedTable
	 * @param int relatedId 
	 * @scope variable
	 */
	showAwesomeUploader: function(relatedTable, relatedId){
	
		// Create awesome uploader
		var awesomeUploader = new AwesomeUploader({
			awesomeUploaderRoot: ApplicationConfig.siteUrl + 'javascript/libraries/awesome-uploader-1/',
				maxFileSizeBytes: 5000000,
				flashSwfUploadFileTypes: '*.jpg;*.jpeg;*.png;*.gif',
				flashSwfUploadFileTypesDescription: 'Afbeeldingen',
				flashUploadUrl: ApplicationConfig.adminUrl + 'images/images_awesomeuploader_save?session_id=' + window.PHPSessionId,
				standardUploadUrl: ApplicationConfig.adminUrl + 'images/images_awesomeuploader_save?session_id=' + window.PHPSessionId,
				xhrUploadUrl: ApplicationConfig.adminUrl + 'images/images_awesomeuploader_save?session_id=' + window.PHPSessionId,
				standardUploadFilePostName: 'image',
				flashUploadFilePostName: 'image',
				xhrFilePostName: 'image',
				xhrSendMultiPartFormData: true,
				extraPostData: {
					relatedTable: relatedTable,
					relatedId: relatedId
				},
				listeners:{
					fileupload:function(uploader, success, result){
						if(uploader.getQueuedItems() === 0){
							// Reload ImagesGrid and update related record
							Admin.ImagesGrid.instance().getStore().load({
								callback: function(){
									Admin.ImagesActions.updateRelatedImagesCount(
										Admin.ImagesGridWindow.relatedRecord,
										Admin.ImagesGrid.instance()
									);
								}
							});
							
							// Close Awesome Uploader window
							uploadWindow.close();
						}
					},
					scope: this
				}
		});
	
		// Create upload window
		var uploadWindow = new Ext.Window({
			title:'Upload nieuwe afbeeldingen',
			modal: true,
			frame: true,
			width: 500,
			height: 320,
			items: [awesomeUploader]
		});
		
		// Disable window hide when images in queue
		uploadWindow.on('beforeclose', function(){
			if(awesomeUploader.getQueuedItems() > 0){
				Ext.Msg.show({
					title: 'Bezig met uploaden',
					msg: 'Nadat alle afbeeldingen verwerkt zijn zal dit venster automatisch sluiten. Gelieve even te wachten.',
					buttons: Ext.Msg.OK,
					icon: Ext.Msg.INFO
				});
				return false;
			}
		});
		
		// Show upload window
		uploadWindow.show();
	},

	/**
	 * Validate and save ImagesForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveImagesForm: function(button, e){

		// Get instances we will use
		var imagesGrid = Admin.ImagesGrid.instance();
		var imagesFormWindow = Admin.ImagesFormWindow.instance();
		imagesFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Get existing record or create new
				var recordIndex = imagesGrid.getStore().findExact('id', parseInt(action.result.record.id));
				var record = imagesGrid.getStore().getAt(recordIndex);

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
				imagesFormWindow.getEl().unmask();
				imagesFormWindow.hide();

				// Highlight updated row
				Ext.get(imagesGrid.getView().getRow(recordIndex)).highlight();

			},
			failure: function(form, action){

				// Hide form window
				imagesFormWindow.getEl().unmask();
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
			url: ApplicationConfig.adminUrl + 'images/images_grid_order',
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
	 * Delete record from ImagestwGrid
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
			msg: String.format('Ben je zeker dat je <b>{0}</b> wil verwijderen?', (record.get('alt') || record.get('filename'))),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'images/images_grid_delete',
					params: {
						id: record.get('id')
					},
					callback: function(options, success, response){
						var result = Ext.decode(response.responseText);
						if(success && result.success){
							grid.getStore().remove(record);
							Admin.ImagesActions.updateRelatedImagesCount(
								Admin.ImagesGridWindow.relatedRecord,
								grid
							);
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
	 * Update 'images_count' field for related record
	 *
	 * @param Ext.data.Record relatedRecord
	 * @param Ext.grid.GridPanel grid
	 * @scope variable
	 */
	updateRelatedImagesCount: function(relatedRecord, grid){
		if(relatedRecord.get('images_count') !== undefined){
			relatedRecord.set('images_count', grid.getStore().getCount());
			relatedRecord.commit();
		}		
	}
}