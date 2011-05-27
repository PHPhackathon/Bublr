/**
 * Images grid window
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-03
 */
Admin.ImagesGridWindow = {

	singletonInstance: null,

	relatedRecord: null,
	relatedTable: null,
	relatedId: null,

	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.ImagesGridWindow.singletonInstance? true : false;
	},

	/**
	 * Return instance or create new instance
	 */
	instance: function(){

		if(Admin.ImagesGridWindow.singletonInstance){
			return Admin.ImagesGridWindow.singletonInstance;
		}else{

			// Grid
			var grid = Admin.ImagesGrid.instance();

			// Grid window
			var gridWindow = new Ext.Window({
				title: 'Afbeeldingen',
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
			
			// Clear grid before window is shown
			gridWindow.on('beforeshow', function(){
				grid.getStore().removeAll();
			});

			// Load grid on window show
			gridWindow.on('show', function(){
				var store = grid.getStore();
				store.baseParams.relatedTable = Admin.ImagesGridWindow.relatedTable;
				store.baseParams.relatedId = Admin.ImagesGridWindow.relatedId;
				store.load();
			});

			// Set singleton instance and objects
			Admin.ImagesGridWindow.singletonInstance = gridWindow;
			gridWindow.grid = grid;
			return gridWindow;
		}
	},

	/**
	 * Set related data
	 *
	 * @param Objects options
	 *		- relatedRecord
	 *		- relatedTable
	 *		- relatedId
	 */
	setRelatedData: function(options){
		Admin.ImagesGridWindow.relatedRecord	= options.relatedRecord;
		Admin.ImagesGridWindow.relatedTable		= options.relatedTable;
		Admin.ImagesGridWindow.relatedId		= options.relatedId;
	}

}