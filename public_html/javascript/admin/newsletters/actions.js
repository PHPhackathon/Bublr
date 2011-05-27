/**
 * Newsletters actions
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-01
 */
Admin.NewslettersActions = {

	/**
	 * Export subscribers list
	 *
	 * @param Ext.Button button
	 * @param Event e
	 * @scope variable
	 */
	exportSubscribers: function(button, e){
		location = ApplicationConfig.adminUrl + 'newsletters/export_subscribers';
	},


	/**
	 * Delete record from NewslettersSubscribersGrid
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
			msg: String.format('Ben je zeker dat je <b>{0}</b> wil verwijderen?', record.get('email')),
			buttons: Ext.Msg.YESNO,
			fn: function(action){
				if(action == 'no') return false;
				
				// Send delete request
				grid.el.mask('Even geduld aub...');
				Ext.Ajax.request({
					url: ApplicationConfig.adminUrl + 'newsletters/subscribers_grid_delete',
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