/**
 * Members categories grid window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-30
 */
Admin.MembersCategoriesGridWindow = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.MembersCategoriesGridWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.MembersCategoriesGridWindow.singletonInstance){
			return Admin.MembersCategoriesGridWindow.singletonInstance;
		}else{

			// Grid
			var grid = Admin.MembersCategoriesGrid.instance();

			// Grid window
			var gridWindow = new Ext.Window({
				title: 'Leden categorieën',
				closeAction: 'hide',
				layout: 'fit',
				border: false,
				autoScroll: true,
				width: 650,
				height: 400,
				minWidth: 500,
				minHeight: 300,
				modal: true,
				items: [grid]
			});

			// Load grid store on first window activate
			gridWindow.on('activate', function(){
				if(grid.getStore().lastOptions == null){
					grid.getStore().load();
				}
			});

			// Set singleton instance and objects
			Admin.MembersCategoriesGridWindow.singletonInstance = gridWindow;
			gridWindow.grid = grid;
			return gridWindow;
		}
	}

}