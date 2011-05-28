/**
 * Calendars grid panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2011-04-01
 */
Admin.CalendarsGrid = {

	singletonInstance: null,

	/**
	 * Create new instance
	 */
	instance: function(){

		if(Admin.CalendarsGrid.singletonInstance){
			return Admin.CalendarsGrid.singletonInstance;
		}else{

			// Grid renderers
			var renderers = {

				// Month
				month: function(value){
					if(value) return String.format('<p style="text-transform:capitalize">{0}</p>', value.format('F Y'));
					return '';
				},

				// Download
				download: function(value){
					if(!value) return 'n.v.t.';
					return String.format('<a href="{0}files/download/calendars/{1}">{1}</a>', ApplicationConfig.siteUrl, value);
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
				url: ApplicationConfig.adminUrl + 'calendars/calendars_grid',
				remoteSort: true,
				root: 'records',
				baseParams: {
					limit: pageSize
				},
				fields: [
					{name: 'id',			type: 'int'},
					{name: 'month',			type: 'date',	dateFormat: 'Y-m-d'},
					{name: 'online',		type: 'int'},
					{name: 'created',		type: 'date',	dateFormat: 'Y-m-d H:i:s'},
					{name: 'updated',		type: 'date',	dateFormat: 'Y-m-d H:i:s'},
					{name: 'file_filename'}
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
						Admin.CalendarsActions.loadCalendarsForm(grid, rowIndex);
					}
				},{
					icon: ApplicationConfig.siteUrl + 'images/icons/minus-circle.png',
					tooltip: 'Verwijder...',
					iconCls: 'admin-actioncolumn-item',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.CalendarsActions.deleteRecord(grid, rowIndex);
					}
				}]
			});

			// Columns
			var autoExpandColumnId = Ext.id();
			var columns = [
				{header: 'Maand',			dataIndex: 'month',				sortable: true,		width: 150,		renderer: renderers.month},
				{header: 'Download',		dataIndex: 'file_filename',		sortable: true,		width: 150,		renderer: renderers.download, 	id: autoExpandColumnId},
				{header: 'Toegevoegd',		dataIndex: 'created',			sortable: true,		width: 120,		renderer: renderers.date},
				{header: 'Online',			dataIndex: 'online',			sortable: true,		width: 100,		renderer: renderers.online},
				actionColumn
			];

			// Top bar
			var topBar = new Ext.Toolbar([
				{
					icon: ApplicationConfig.siteUrl + 'images/icons/plus-circle.png',
					text: 'Nieuw',
					tooltip: 'Voeg een nieuwe kalender toe',
					handler: function(grid, rowIndex, columnIndex, button, e){
						Admin.CalendarsActions.newCalendarsForm();
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
				title: AdminManager.iconTitle('calendar-list.png', 'Maandkalenders')
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
					Admin.CalendarsActions.deleteRecord(grid, grid.getStore().indexOf(record));
				}
			});

			// Handle doubleclick
			grid.on('rowdblclick', function(grid, rowIndex){
				Admin.CalendarsActions.loadCalendarsForm(grid, rowIndex);
			});

			// Set singleton instance and objects
			Admin.CalendarsGrid.singletonInstance = grid;
			return grid;
		}
	}
}