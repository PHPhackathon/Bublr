/**
 * Links grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-09
 */
Admin.LinksGrid = {

	singletonInstance: null,
	
	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.LinksGrid.singletonInstance? true : false;
	},

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.LinksGrid.singletonInstance){
			return Admin.LinksGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Category
				category: function(value, meta, record){
					return record.get('category_title');
				},

				// Url
				url: function(value){
					return String.format('<a href="{0}" target="_blank">{0}</a>', value);
				},

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
			var store = new Ext.data.GroupingStore({
				url: ApplicationConfig.adminUrl + 'links/links_grid',
				reader: new Ext.data.JsonReader({
					root: 'records',
					fields: [
						{name: 'id',				type: 'int'},
						{name: 'title'},
						{name: 'url'},
						{name: 'online',			type: 'int'},
						{name: 'category_id',		type: 'int'},
						{name: 'category_title'}
					]
				}),
				groupField: 'category_id',
				remoteSort: false,
				baseParams: {
					limit: pageSize
				}
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
						Admin.LinksActions.loadLinksForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.LinksActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Categorie',		dataIndex: 'category_id',	menuDisabled: true,		sortable: false,	width: 250,		renderer: renderers.category},
				{header: 'Titel',			dataIndex: 'title',			menuDisabled: true,		sortable: false,	width: 250,		id: autoExpandColumnId},
				{header: 'Url',				dataIndex: 'url',			menuDisabled: true,		sortable: false,	width: 250,		renderer: renderers.url},
				{header: 'Online',			dataIndex: 'online',		menuDisabled: true,		sortable: false,	width: 100,		renderer: renderers.online},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg een nieuwe link toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.LinksActions.newLinksForm();
					}
				},
				'-',
				'Versleep een rij om de volgorde te wijzigen',
				'->',
				Admin.SearchField.instance({
					store: store
				})
			]);

			// Bottom bar
			var bottomBar = new Ext.PagingToolbar({
				store: store,
				displayInfo: true,
				pageSize: pageSize
			});

			// Grouping view
			var groupingView = new Ext.grid.GroupingView({
				hideGroupedColumn: true,
				showGroupName: false
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
				enableDragDrop: true,
				view: groupingView,
				title: AdminManager.iconTitle('anchor.png', 'Links')
			}, arguments[0] || {});
			var grid = new Ext.grid.GridPanel(gridConfig);

			// Load stores on first grid activation
			grid.on('activate', function(){
				if(this.getStore().lastOptions == null) this.getStore().load();
				Admin.LinksFormWindow.instance();
			});
			// Handle delete button
			grid.on('keydown', function(e){
				var record = grid.getSelectionModel().getSelected();
				if(record && e.getKey() == e.DELETE){
					Admin.LinksActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.LinksActions.loadLinksForm(grid, rowIndex);
			});

			// Handle drag & drop
			grid.on('render', function(grid){
				new Ext.ux.dd.GridReorderDropTarget(grid, {
					listeners: {
						afterrowmove: Admin.LinksActions.saveOrder
					}
				});
			});

			// Set singleton instance and objects
			Admin.LinksGrid.singletonInstance = grid;
			return grid;
		}
	}
}