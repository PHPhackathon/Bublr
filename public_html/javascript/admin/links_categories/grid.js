/**
 * Links categories grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-12
 */
Admin.LinksCategoriesGrid = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.LinksCategoriesGrid.singletonInstance? true : false;
	},

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.LinksCategoriesGrid.singletonInstance){
			return Admin.LinksCategoriesGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Online
				online: function(value){
					if(value){
						return '<p style="color:#008800">online</p>';
					}else{
						return '<p style="color:#BB0000">offline</p>';
					}
				}

			}

			// Grid store
			var pageSize = 9999;
			var store = new Ext.data.JsonStore({
				url: ApplicationConfig.adminUrl + 'links/links_categories_grid',
				remoteSort: true,
				root: 'records',
				baseParams: {
					limit: pageSize
				},
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'title'},
					{name: 'online',		type: 'int'}
				]
			});

			// Selection model
			var selectionModel = new Ext.grid.RowSelectionModel({singleSelect:true})

			// Action columns
			var actionColumn = new Ext.grid.ActionColumn({
				width: 70,
				items: [{
					icon: ApplicationConfig.siteUrl + 'images/icons/blue-document--pencil.png',
					tooltip: 'Wijzig',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.LinksCategoriesActions.loadLinksCategoriesForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.LinksCategoriesActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Naam',			dataIndex: 'title',			menuDisabled: true,		sortable: false,	width: 250,		id: autoExpandColumnId},
				{header: 'Online',			dataIndex: 'online',		menuDisabled: true,		sortable: false,	width: 100,		renderer: renderers.online},
				actionColumn
			];
			
			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg een nieuwe categorie toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.LinksCategoriesActions.newLinksCategoriesForm();
					}
				},
				'-',
				'Versleep een rij om de volgorde te wijzigen'
			]);

			// Bottom bar
			var bottomBar = new Ext.PagingToolbar({
				store: store,
				displayInfo: true,
				pageSize: pageSize
			});

			// Create grid
			var gridConfig = Ext.apply({
				loadMask: true,
				autoExpandColumn: autoExpandColumnId,
				store: store,
				sm: selectionModel,
				columns: columns,
				tbar: topBar,
				bbar: bottomBar,
				enableDragDrop: true
			}, arguments[0] || {});
			var grid = new Ext.grid.GridPanel(gridConfig);

			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.LinksCategoriesActions.loadLinksCategoriesForm(grid, rowIndex);
			});

			// Handle drag & drop
			grid.on('render', function(grid){
				new Ext.ux.dd.GridReorderDropTarget(grid, {
					listeners: {
						afterrowmove: Admin.LinksCategoriesActions.saveOrder
					}
				});
			});

			// Set singleton instance and objects
			Admin.LinksCategoriesGrid.singletonInstance = grid;
			return grid;
		}
	}
}