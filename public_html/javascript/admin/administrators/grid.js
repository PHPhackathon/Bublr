/**
 * Administrators grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-18
 */
Admin.AdministratorsGrid = {

	singletonInstance: null,

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.AdministratorsGrid.singletonInstance){
			return Admin.AdministratorsGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {
			
				// Name
				name: function(value, meta, record){
					return String.format('{0} {1}', record.get('firstname'), record.get('lastname'));
				},

				// Email
				email: function(value){
					return String.format('<a href="mailto:{0}">{0}</a>', value);
				},

				// Date
				date: function(value){
					if(value) return value.format('d/m/Y H:i');
					return '';
				},
				
				// Online
				online: function(value){
					if(value){
						return '<p style="color:#008800">toegang</p>';
					}else{
						return '<p style="color:#BB0000">geen toegang</p>';
					}
				}
			}

			// Grid store
			var pageSize = 100;
			var store = new Ext.data.JsonStore({
				url: ApplicationConfig.adminUrl + 'administrators/administrators_grid',
				remoteSort: true,
				root: 'records',
				baseParams: {
					limit: pageSize
				},
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'firstname'},
					{name: 'lastname'},
					{name: 'email'},
					{name: 'online',		type: 'int'},
					{name: 'created',		type: 'date',	dateFormat: 'Y-m-d H:i:s'},
					{name: 'updated',		type: 'date',	dateFormat: 'Y-m-d H:i:s'},
					{name: 'last_login',	type: 'date',	dateFormat: 'Y-m-d H:i:s'}
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
						Admin.AdministratorsActions.loadAdministratorsForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.AdministratorsActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Naam',			dataIndex: 'firstname',			sortable: true,		width: 250,		renderer: renderers.name,	id: autoExpandColumnId},
				{header: 'Email',			dataIndex: 'email',				sortable: true,		width: 150,		renderer: renderers.email},
				{header: 'Toegevoegd',		dataIndex: 'created',			sortable: true,		width: 120,		renderer: renderers.date},
				{header: 'Gewijzigd',		dataIndex: 'updated',			sortable: true,		width: 120,		renderer: renderers.date},
				{header: 'Laatste login',	dataIndex: 'last_login',		sortable: true,		width: 120,		renderer: renderers.date},
				{header: 'Toegang',			dataIndex: 'online',			sortable: true,		width: 100,		renderer: renderers.online},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg een nieuwe beheerder toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.AdministratorsActions.newAdministratorsForm();
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
				title: AdminManager.iconTitle('user-worker-boss.png', 'Beheerders')
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
					Admin.AdministratorsActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});
			
			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.AdministratorsActions.loadAdministratorsForm(grid, rowIndex);
			});

			// Set singleton instance and objects
			Admin.AdministratorsGrid.singletonInstance = grid;
			return grid;
		}
	}
}