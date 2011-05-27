/**
 * Members categories actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-30
 */
Admin.MembersCategoriesActions = {

	/**
	 * Load MembersCategoriesForm with data and show window
	 *
	 * @param Ext.grid.GridPanel grid
	 * @param int rowIndex
	 * @scope variable
	 */
	loadMembersCategoriesForm: function(grid, rowIndex){

		// Get selected record
		var record = grid.getStore().getAt(rowIndex);

		// Create and show window
		var membersCategoriesFormWindow = Admin.MembersCategoriesFormWindow.instance();
		membersCategoriesFormWindow.setTitle(record.get('title'));
		membersCategoriesFormWindow.show();
		membersCategoriesFormWindow.el.mask('Even geduld aub...');

		// Load form data
		Ext.Ajax.request({
			url: ApplicationConfig.adminUrl + 'members/members_categories_formwindow_load',
			params: {
				id: record.get('id')
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){
					membersCategoriesFormWindow.formPanel.getForm().reset();
					membersCategoriesFormWindow.formPanel.getForm().setValues(result.record);
				}else{
					Ext.Msg.show({
						title: 'Fout',
						msg: result.message || 'Er is een onbekende fout opgetreden',
						buttons: Ext.Msg.OK,
						icon: Ext.Msg.ERROR
					});
				}
				membersCategoriesFormWindow.el.unmask();
			}
		});
	},

	/**
	 * Reset MembersCategoriesForm and show window
	 *
	 * @scope variable
	 */
	newMembersCategoriesForm: function(){

		// Create and show window
		var membersCategoriesFormWindow = Admin.MembersCategoriesFormWindow.instance();
		membersCategoriesFormWindow.setTitle('Nieuwe categorie');
		membersCategoriesFormWindow.show();

		// Reset form
		membersCategoriesFormWindow.formPanel.getForm().reset();

	},

	/**
	 * Validate and save MembersCategoriesForm values
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope Ext.form.FormPanel
	 */
	saveMembersCategoriesForm: function(button, e){

		// Get instances we will use
		var membersCategoriesGrid = Admin.MembersCategoriesGrid.instance();
		var membersCategoriesFormWindow = Admin.MembersCategoriesFormWindow.instance();
		membersCategoriesFormWindow.getEl().mask('Even geduld aub...');

		// Submit form
		this.getForm().submit({
			success: function(form, action){

				// Reload categories grid
				membersCategoriesGrid.getStore().reload();

				// Reload members grid
				if(Admin.MembersGrid.hasInstance()){
					Admin.MembersGrid.instance().getStore().reload();
				}

				// Reload members form categories combobox
				if(Admin.MembersFormWindow.hasInstance()){
					var existingValue = Admin.MembersFormWindow.instance().formPanel.getForm().getFieldValues().category_id;
					Admin.MembersFormWindow.instance().categoriesStore.reload({
						callback: function(){
							if(existingValue){
								Admin.MembersFormWindow.instance().formPanel.getForm().setValues({
									category_id: existingValue
								});
							}
						}
					});
				}

				// Hide form window
				membersCategoriesFormWindow.getEl().unmask();
				membersCategoriesFormWindow.hide();

			},
			failure: function(form, action){

				// Hide form window
				membersCategoriesFormWindow.getEl().unmask();
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
			url: ApplicationConfig.adminUrl + 'members/members_categories_grid_order',
			params: {
				ids: Ext.encode(ids)
			},
			callback: function(options, success, response){
				var result = Ext.decode(response.responseText);
				if(success && result.success){

					// Reload members grid
					if(Admin.MembersGrid.hasInstance()){
						Admin.MembersGrid.instance().getStore().reload();
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
	 * Delete record from MembersCategoriesGrid
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
			msg: String.format('Ben je zeker dat je <b>{0}</b> en alle onderliggende leden wil verwijderen?', record.get('title')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;

				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'members/members_categories_grid_delete',
					params: {
						id: record.get('id')
					},
					callback: function(options, success, response){
						var result = Ext.decode(response.responseText);
						if(success && result.success){

							// Remove record from grid
							grid.getStore().remove(record);

							// Hide members form window if record is zombie
							if(Admin.MembersFormWindow.hasInstance()){
								var selectedValue = Admin.MembersFormWindow.instance().formPanel.getForm().getFieldValues().category_id;
								if(selectedValue == record.get('id')){
									Admin.MembersFormWindow.instance().hide();
								}
							}
							
							// Reload members grid
							if(Admin.MembersGrid.hasInstance()){
								Admin.MembersGrid.instance().getStore().reload();
							}

							// Reload members form categories combobox
							if(Admin.MembersFormWindow.hasInstance()){
								Admin.MembersFormWindow.instance().formPanel.getForm().setValues({
									category_id: null
								});
								Admin.MembersFormWindow.instance().categoriesStore.reload();
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