/**
 * Images grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-05-03
 */
Admin.ImagesGrid = {

	singletonInstance: null,

	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.ImagesGrid.singletonInstance? true : false;
	},

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.ImagesGrid.singletonInstance){
			return Admin.ImagesGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {
			
				// Image
				image: function(value, meta, record){
					if(value){
						var toolTipTag = String.format('<img src=\'{0}images/admin_grid_preview/{1}/{2}\' width=\'150\' height=\'200\' />', ApplicationConfig.siteUrl, record.get('related_table'), value);
						return String.format('<img src="{0}images/admin_grid_thumbnail/{1}/{2}" ext:qtip="{3}" width="50" height="50" />', ApplicationConfig.siteUrl, record.get('related_table'), value, toolTipTag);
					}else{
						return String.format('<img src="{0}images/front/error.jpg" width="50" height="50" />', ApplicationConfig.siteUrl);
					}
				},

				// Alt + filename
				alt: function(value, meta, record){
					if(value){
						return value;
					}
					return String.format('<p style="color:#666">{0}</p>', record.get('filename'));
				}

			};

			// Grid store
			var pageSize = 9999;
			var store = new Ext.data.JsonStore({
				url: ApplicationConfig.adminUrl + 'images/images_grid',
				remoteSort: true,
				root: 'records',
				baseParams: {
					limit: pageSize
				},
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'alt'},
					{name: 'filename'},
					{name: 'related_table'},
					{name: 'related_id',	type: 'int'}
				]
			});

			// Selection model
			var selectionModel = new Ext.grid.RowSelectionModel({singleSelect:true});

			// Action columns
			var actionColumn = new Ext.grid.ActionColumn({
				width: 70,
				items: [{
					icon: ApplicationConfig.siteUrl + 'images/icons/blue-document--pencil.png',
					tooltip: 'Wijzig',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ImagesActions.loadImagesForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ImagesActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Afbeelding',		dataIndex: 'filename',		menuDisabled: true,		sortable: false,	width: 70,		renderer: renderers.image},
				{header: 'Titel',			dataIndex: 'alt',			menuDisabled: true,		sortable: false,	width: 250,		id: autoExpandColumnId,	renderer: renderers.alt},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg nieuwe afbeeldingen toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ImagesActions.showAwesomeUploader(
							Admin.ImagesGridWindow.relatedTable,
							Admin.ImagesGridWindow.relatedId
						);
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
			
			// Handle delete button
			grid.on('keydown', function(e){
				var record = grid.getSelectionModel().getSelected();
				if(record && e.getKey() == e.DELETE){
					Admin.ImagesActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.ImagesActions.loadImagesForm(grid, rowIndex);
			});

			// Handle drag & drop
			grid.on('render', function(grid){
				new Ext.ux.dd.GridReorderDropTarget(grid, {
					listeners: {
						afterrowmove: Admin.ImagesActions.saveOrder
					}
				});
			});

			// Set singleton instance and objects
			Admin.ImagesGrid.singletonInstance = grid;
			return grid;
		}
	}
};