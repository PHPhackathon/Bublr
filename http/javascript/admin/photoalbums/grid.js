/**
 * Photoalbums grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-02
 */
Admin.PhotoalbumsGrid = {

	singletonInstance: null,

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.PhotoalbumsGrid.singletonInstance){
			return Admin.PhotoalbumsGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Date
				dateFull: function(value){
					if(value) return value.format('j F Y');
					return '';
				},

				// Date
				date: function(value){
					if(value) return value.format('d/m/Y H:i');
					return '';
				},

				// Online
				online: function(value){
					if(value){
						return '<p style="color:#008800">online</p>';
					}else{
						return '<p style="color:#BB0000">offline</p>';
					}
				}
			};

			// Grid store
			var pageSize = 100;
			var store = new Ext.data.JsonStore({
				url: ApplicationConfig.adminUrl + 'photoalbums/photoalbums_grid',
				remoteSort: true,
				root: 'records',
				baseParams: {
					limit: pageSize
				},
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'title'},
					{name: 'date',			type: 'date',	dateFormat: 'Y-m-d'},
					{name: 'online',		type: 'int'},
					{name: 'created',		type: 'date',	dateFormat: 'Y-m-d H:i:s'},
					{name: 'updated',		type: 'date',	dateFormat: 'Y-m-d H:i:s'},
					{name: 'images_count',	type: 'int'}
				]
			});

			// Selection model
			var selectionModel = new Ext.grid.RowSelectionModel({singleSelect:true});

			// Action columns
			var actionColumn = new Ext.grid.ActionColumn({
				width: 100,
				items: [{
					icon: ApplicationConfig.siteUrl + 'images/icons/images.png',
					tooltip: 'Foto\'s',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.PhotoalbumsActions.loadImagesWindow(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/blue-document--pencil.png',
					tooltip: 'Wijzig',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.PhotoalbumsActions.loadPhotoalbumsForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.PhotoalbumsActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Datum',			dataIndex: 'date',				sortable: true,		width: 150,		renderer: renderers.dateFull},
				{header: 'Titel',			dataIndex: 'title',				sortable: true,		width: 250,		id: autoExpandColumnId},
				{header: 'Foto\'s',			dataIndex: 'images_count',		sortable: true,		width: 70},
				{header: 'Toegevoegd',		dataIndex: 'created',			sortable: true,		width: 120,		renderer: renderers.date},
				{header: 'Online',			dataIndex: 'online',			sortable: true,		width: 100,		renderer: renderers.online},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg een nieuw fotoalbum toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.PhotoalbumsActions.newPhotoalbumsForm();
					}
				},
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

			// Create grid
			var gridConfig = Ext.apply({
				loadMask: true,
				autoExpandColumn: autoExpandColumnId,
				store: store,
				sm: selectionModel,
				columns: columns,
				tbar: topBar,
				bbar: bottomBar,
				title: AdminManager.iconTitle('photo-album.png', 'Fotoalbums')
			}, arguments[0] || {});
			var grid = new Ext.grid.GridPanel(gridConfig);

			// Load store on first grid activation
			grid.on('activate', function(){
				if(this.getStore().lastOptions == null) this.getStore().load();
			});

			// Handle delete button
			grid.on('keydown', function(e){
				var record = grid.getSelectionModel().getSelected();
				if(record && e.getKey() == e.DELETE){
					Admin.PhotoalbumsActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.PhotoalbumsActions.loadPhotoalbumsForm(grid, rowIndex);
			});

			// Set singleton instance and objects
			Admin.PhotoalbumsGrid.singletonInstance = grid;
			return grid;
		}
	}
};