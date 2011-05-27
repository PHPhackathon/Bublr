/**
 * Contact grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-02
 */
Admin.ContactGrid = {

	singletonInstance: null,

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.ContactGrid.singletonInstance){
			return Admin.ContactGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Email
				email: function(value){
					return String.format('<a href="mailto:{0}">{0}</a>', value);
				},
				
				// Message
				message: function(value){
					return AdminManager.collapsibleText(value);
				},

				// Date
				date: function(value){
					return value.format('d/m/Y H:i');
				}
			}

			// Grid store
			var pageSize = 100;
			var store = new Ext.data.JsonStore({
				url: ApplicationConfig.adminUrl + 'contact/contact_grid',
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
					{name: 'phone'},
					{name: 'message'},
					{name: 'created',		type: 'date',	dateFormat: 'Y-m-d H:i:s'}
				]
			});

			// Selection model
			var selectionModel = new Ext.grid.RowSelectionModel({singleSelect:true})

			// Action columns
			var actionColumn = new Ext.grid.ActionColumn({
				width: 50,
				items: [{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.ContactActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Voornaam',	dataIndex: 'firstname',		sortable: true,		width: 150},
				{header: 'Achternaam',	dataIndex: 'lastname',		sortable: true,		width: 150},
				{header: 'Email',		dataIndex: 'email',			sortable: true,		width: 150,	renderer: renderers.email},
				{header: 'Tel / GSM',	dataIndex: 'phone',			sortable: true,		width: 150},
				{header: 'Bericht',		dataIndex: 'message',		sortable: false,	width: 150,	renderer: renderers.message, id: autoExpandColumnId},
				{header: 'Toegevoegd',	dataIndex: 'created',		sortable: true,		width: 120,	renderer: renderers.date},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
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
				title: AdminManager.iconTitle('mails-stack.png', 'Inzendingen contactformulier')
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
					Admin.ContactActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Set singleton instance and objects
			Admin.ContactGrid.singletonInstance = grid;
			return grid;
		}
	}
}