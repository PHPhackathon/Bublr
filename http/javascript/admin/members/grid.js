/**
 * Members grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-30
 */
Admin.MembersGrid = {

	singletonInstance: null,

	/**
	 * Check if an instance exists
	 */
	hasInstance: function(){
		return Admin.MembersGrid.singletonInstance? true : false;
	},

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.MembersGrid.singletonInstance){
			return Admin.MembersGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Category
				category: function(value, meta, record){
					if(record.get('category_online')){
						return record.get('category_title');
					}
					return String.format('{0} <em>(offline)</em>', record.get('category_title'));					
				},
				
				// Email
				email: function(value){
					if(value){
						return String.format('<a href="mailto:{0}">{0}</a>', value);
					}
					return '';
				},
				
				// Payed
				payed: function(value){
					if(value){
						return '<p style="color:#008800">ja</p>';
					}else{
						return '<p style="color:#BB0000">nee</p>';
					}
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
			var pageSize = 9999;
			var store = new Ext.data.GroupingStore({
				url: ApplicationConfig.adminUrl + 'members/members_grid',
				reader: new Ext.data.JsonReader({
					root: 'records',
					fields: [
						{name: 'id',				type: 'int'},
						{name: 'firstname'},
						{name: 'lastname'},
						{name: 'phone'},
						{name: 'email'},
						{name: 'about'},
						{name: 'payed',				type: 'int'},
						{name: 'online',			type: 'int'},
						{name: 'category_id',		type: 'int'},
						{name: 'category_title'},
						{name: 'category_online',	type: 'int'}
					]
				}),
				groupField: 'category_id',
				remoteSort: false,
				baseParams: {
					limit: pageSize
				}
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
						Admin.MembersActions.loadMembersForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.MembersActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Categorie',		dataIndex: 'category_id',	menuDisabled: true,		sortable: false,	width: 250,		renderer: renderers.category},
				{header: 'Voornaam',		dataIndex: 'firstname',		menuDisabled: true,		sortable: false,	width: 150},
				{header: 'Achternaam',		dataIndex: 'lastname',		menuDisabled: true,		sortable: false,	width: 150,		id: autoExpandColumnId},
				{header: 'E-mailadres',		dataIndex: 'email',			menuDisabled: true,		sortable: false,	width: 200,		renderer: renderers.email},
				{header: 'Telefoon',		dataIndex: 'phone',			menuDisabled: true,		sortable: false,	width: 100},
				{header: 'Extra info',		dataIndex: 'about',			menuDisabled: true,		sortable: false,	width: 250},
				{header: 'Betaald',			dataIndex: 'payed',			menuDisabled: true,		sortable: false,	width: 70,		renderer: renderers.payed},
				{header: 'Online',			dataIndex: 'online',		menuDisabled: true,		sortable: false,	width: 100,		renderer: renderers.online},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg een nieuw lid toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.MembersActions.newMembersForm();
					}
				},
				'-',
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/blue-document-excel-csv.png',
					text: 'Exporteer',
					tooltip: 'Exporteer ledenlijst naar Excel bestand',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.MembersActions.exportCSV();
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
				title: AdminManager.iconTitle('users.png', 'Leden / Leiders')
			}, arguments[0] || {});
			var grid = new Ext.grid.GridPanel(gridConfig);

			// Load stores on first grid activation
			grid.on('activate', function(){
				if(this.getStore().lastOptions === null) this.getStore().load();
				Admin.MembersFormWindow.instance();
			});
			// Handle delete button
			grid.on('keydown', function(e){
				var record = grid.getSelectionModel().getSelected();
				if(record && e.getKey() == e.DELETE){
					Admin.MembersActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.MembersActions.loadMembersForm(grid, rowIndex);
			});

			// Handle drag & drop
			grid.on('render', function(grid){
				new Ext.ux.dd.GridReorderDropTarget(grid, {
					listeners: {
						afterrowmove: Admin.MembersActions.saveOrder
					}
				});
			});

			// Set singleton instance and objects
			Admin.MembersGrid.singletonInstance = grid;
			return grid;
		}
	}
};