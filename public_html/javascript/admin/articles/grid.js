/**
 * Articles grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-18
 */
Admin.ArticlesGrid = {

	singletonInstance: null,

	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.ArticlesGrid.singletonInstance? true : false;
	},

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.ArticlesGrid.singletonInstance){
			return Admin.ArticlesGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Category
				category: function(value, meta, record){
					return record.get('category_title');
				},

				// Description
				description: function(value){
					return String.format('<p style="white-space:normal;">{0}</p>', Ext.util.Format.ellipsis(value || '', 350));
				},

				// Date
				date: function(value){
					if(value) return value.format('d/m/Y H:i');
					return '';
				},

				// Online
				online: function(value){
					if(value){
						return '<p style="color:#008800">ja</p>';
					}else{
						return '<p style="color:#BB0000">nee</p>';
					}
				}
			}

			// Grid store
			var pageSize = 9999;
			var store = new Ext.data.GroupingStore({
				url: ApplicationConfig.adminUrl + 'articles/articles_grid',
				reader: new Ext.data.JsonReader({
					root: 'records',
					fields: [
						{name: 'id',				type: 'int'},
						{name: 'title'},
						{name: 'description'},
						{name: 'content'},
						{name: 'online',			type: 'int'},
						{name: 'created',			type: 'date',	dateFormat: 'Y-m-d H:i:s'},
						{name: 'updated',			type: 'date',	dateFormat: 'Y-m-d H:i:s'},
						{name: 'sequence',			type: 'int'},
						{name: 'category_id',		type: 'int'},
						{name: 'category_title'},
						{name: 'category_sequence',	type: 'int'}
					]
				}),
				groupField: 'category_sequence',
				remoteSort: false,
				sortInfo: {
					field: 'sequence',
					dir: 'ASC'
				},
				baseParams: {
					limit: pageSize
				}
			});

			// Selection model
			var selectionModel = new Ext.grid.RowSelectionModel({singleSelect:true})

			// Action columns
			var actionColumn = new Ext.grid.ActionColumn({
				width: 100,
				items: [{
					icon: ApplicationConfig.siteUrl + 'images/icons/images.png',
					tooltip: 'Foto\'s',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ArticlesActions.loadImagesWindow(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/blue-document--pencil.png',
					tooltip: 'Wijzig',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ArticlesActions.loadArticlesForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ArticlesActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Categorie',		dataIndex: 'category_sequence',	menuDisabled: true,		sortable: false,	width: 250,		renderer: renderers.category},
				{header: 'Titel',			dataIndex: 'title',				menuDisabled: true,		sortable: false,	width: 300},
				{header: 'Omschrijving',	dataIndex: 'description',		menuDisabled: true,		sortable: false,	width: 400,		renderer: renderers.description,	id: autoExpandColumnId},
				{header: 'Gewijzigd',		dataIndex: 'updated',			menuDisabled: true,		sortable: false,	width: 120,		renderer: renderers.date},
				{header: 'Online',			dataIndex: 'online',			menuDisabled: true,		sortable: false,	width: 60,		renderer: renderers.online},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg een nieuwe tekst toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ArticlesActions.newArticlesForm();
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
				title: AdminManager.iconTitle('blue-folder-open-document-text.png', 'Teksten')
			}, arguments[0] || {});
			var grid = new Ext.grid.GridPanel(gridConfig);

			// Load store on first grid activation
			grid.on('activate', function(){
				if(this.getStore().lastOptions == null) this.getStore().load();
				Admin.ArticlesFormWindow.instance();
			});

			// Handle delete button
			grid.on('keydown', function(e){
				var record = grid.getSelectionModel().getSelected();
				if(record && e.getKey() == e.DELETE){
					Admin.ArticlesActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.ArticlesActions.loadArticlesForm(grid, rowIndex);
			});

			// Handle drag & drop
			grid.on('render', function(grid){
				new Ext.ux.dd.GridReorderDropTarget(grid, {
					listeners: {
						afterrowmove: Admin.ArticlesActions.saveOrder
					}
				});
			});

			// Set singleton instance and objects
			Admin.ArticlesGrid.singletonInstance = grid;
			return grid;
		}
	}
}