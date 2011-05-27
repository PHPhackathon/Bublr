/**
 * Links categories grid window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-12
 */
Admin.LinksCategoriesGridWindow = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.LinksCategoriesGridWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.LinksCategoriesGridWindow.singletonInstance){
			return Admin.LinksCategoriesGridWindow.singletonInstance;
		}else{

			// Grid
			var grid = Admin.LinksCategoriesGrid.instance();

			// Grid window
			var gridWindow = new Ext.Window({
				title: 'Link categorieën',
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
			Admin.LinksCategoriesGridWindow.singletonInstance = gridWindow;
			gridWindow.grid = grid;
			return gridWindow;
		}
	}

}