/**
 * Newsletters subscribers grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-11-01
 */
Admin.NewslettersSubscribersGrid = {

	singletonInstance: null,

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.NewslettersSubscribersGrid.singletonInstance){
			return Admin.NewslettersSubscribersGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Email
				email: function(value){
					return String.format('<a href="mailto:{0}">{0}</a>', value);
				},

				// Date
				date: function(value){
					return value.format('d/m/Y H:i');
				},

				// Blacklisted
				blacklisted: function(value){
					if(value){
						return '<p style="color:#BB0000">blacklisted</p>';
					}else{
						return '<p style="color:#008800">actief</p>';
					}
				}
			}

			// Grid store
			var pageSize = 100;
			var store = new Ext.data.JsonStore({
				url: ApplicationConfig.adminUrl + 'newsletters/subscribers_grid',
				remoteSort: true,
				root: 'records',
				baseParams: {
					limit: pageSize
				},
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'name'},
					{name: 'email'},
					{name: 'created',		type: 'date',	dateFormat: 'Y-m-d H:i:s'},
					{name: 'blacklisted',	type: 'int'}
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
						Admin.NewslettersActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Naam',		dataIndex: 'name',				sortable: true,		width: 200},
				{header: 'Email',		dataIndex: 'email',				sortable: true,		width: 150,		renderer: renderers.email,	id: autoExpandColumnId},
				{header: 'Toegevoegd',	dataIndex: 'created',			sortable: true,		width: 120,		renderer: renderers.date},
				{header: 'Actief',		dataIndex: 'blacklisted',		sortable: true,		width: 100,		renderer: renderers.blacklisted},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{icon: ApplicationConfig.siteUrl + 'images/icons/document-excel-csv.png', text: 'Exporteer', tooltip: 'Exporteer lijst naar Excel bestand', handler: Admin.NewslettersActions.exportSubscribers},
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
				title: AdminManager.iconTitle('address-book.png', 'Nieuwsbrief inschrijvingen')
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
					Admin.NewslettersActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Set singleton instance and objects
			Admin.NewslettersSubscribersGrid.singletonInstance = grid;
			return grid;
		}
	}
}