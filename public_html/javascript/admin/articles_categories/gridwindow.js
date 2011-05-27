/**
 * Articles categories grid window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-02-28
 */
Admin.ArticlesCategoriesGridWindow = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.ArticlesCategoriesGridWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.ArticlesCategoriesGridWindow.singletonInstance){
			return Admin.ArticlesCategoriesGridWindow.singletonInstance;
		}else{

			// Grid
			var grid = Admin.ArticlesCategoriesGrid.instance();

			// Grid window
			var gridWindow = new Ext.Window({
				title: 'Tekst categorieën',
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
			Admin.ArticlesCategoriesGridWindow.singletonInstance = gridWindow;
			gridWindow.grid = grid;
			return gridWindow;
		}
	}

}