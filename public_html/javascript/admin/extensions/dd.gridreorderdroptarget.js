/**
 * Grid drag & drop target extension
 * Extends Ext.util.Observable
 *
 * @author Unknown
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-12-06
 */
Ext.namespace('Ext.ux.dd');
Ext.ux.dd.GridReorderDropTarget = function(grid, config){
	this.target = new Ext.dd.DropTarget(grid.getEl(), {
		ddGroup: grid.ddGroup || 'GridDD',
		grid: grid,
		gridDropTarget: this,
		notifyDrop: function(dd, e, data){

			// Remove drag lines. The 'if' condition prevents null error when drop occurs without dragging out of the selection area
			if (this.currentRowEl){
				this.currentRowEl.removeClass('grid-row-insert-below');
				this.currentRowEl.removeClass('grid-row-insert-above');
			}

			// Determine the row
			var t = Ext.lib.Event.getTarget(e);
			var rindex = this.grid.getView().findRowIndex(t);
			if (rindex === false || rindex == data.rowIndex){
				return false;
			}

			// Fire the before move/copy event
			if (this.gridDropTarget.fireEvent(this.copy ? 'beforerowcopy' : 'beforerowmove', this.gridDropTarget, data.rowIndex, rindex, data.selections, 123) === false){
				return false;
			}

			// Update the store
			var ds = this.grid.getStore();

			// Changes for multiselction by Spirit
			var selections = new Array();
			var keys = ds.data.keys;
			for (var key in keys){
				for (var i = 0; i < data.selections.length; i++){
					if (keys[key] == data.selections[i].id){
						// Exit to prevent drop of selected records on itself.
						if (rindex == key){
							return false;
						}
						selections.push(data.selections[i]);
					}
				}
			}

			// Fix rowindex based on before/after move
			if (rindex > data.rowIndex && this.rowPosition < 0){
				rindex--;
			}
			if (rindex < data.rowIndex && this.rowPosition > 0){
				rindex++;
			}

			// Fix rowindex for multiselection
			if (rindex > data.rowIndex && data.selections.length > 1){
				rindex = rindex - (data.selections.length - 1);
			}

			// We tried to move this node before the next sibling, we stay in place
			if (rindex == data.rowIndex){
				return false;
			}

			if (!this.copy){
				for (var i = 0; i < data.selections.length; i++){
					ds.remove(ds.getById(data.selections[i].id));
				}
			}

			for (var i = selections.length - 1; i >= 0; i--){
				var insertIndex = rindex;
				ds.insert(insertIndex, selections[i]);
			}

			// Re-select the row(s)
			var sm = this.grid.getSelectionModel();
			if (sm){
				sm.selectRecords(data.selections);
			}

			// Fire the after move/copy event
			this.gridDropTarget.fireEvent(this.copy ? 'afterrowcopy' : 'afterrowmove', dd.grid, dd.dragData.selections[0]);
			return true;
		},

		notifyOver: function(dd, e, data){
			var t = Ext.lib.Event.getTarget(e);
			var rindex = this.grid.getView().findRowIndex(t);

			// Similar to the code in notifyDrop. Filters for selected rows and quits function if any one row matches the current selected row.
			var ds = this.grid.getStore();
			var keys = ds.data.keys;
			for (var key in keys){
				for (var i = 0; i < data.selections.length; i++){
					if (keys[key] == data.selections[i].id){
						if (rindex == key){
							if (this.currentRowEl){
								this.currentRowEl.removeClass('grid-row-insert-below');
								this.currentRowEl.removeClass('grid-row-insert-above');
							}
							return this.dropNotAllowed;
						}
					}
				}
			}

			// If on first row, remove upper line. Prevents negative index error as a result of rindex going negative.
			if (rindex < 0 || rindex === false){
				if(this.currentRowEl){
					this.currentRowEl.removeClass('grid-row-insert-above');
				}
				return this.dropNotAllowed;
			}

			try{
				var currentRow = this.grid.getView().getRow(rindex);

				// Find position of row relative to page (adjusting for grid's scroll position)
				var resolvedRow = new Ext.Element(currentRow).getY() - this.grid.getView().scroller.dom.scrollTop;
				var rowHeight = currentRow.offsetHeight;

				// Cursor relative to a row. -ve value implies cursor is above the row's middle and +ve value implues cursor is below the row's middle.
				this.rowPosition = e.getPageY() - resolvedRow - (rowHeight/2);

				// Clear drag line.
				if (this.currentRowEl){
					this.currentRowEl.removeClass('grid-row-insert-below');
					this.currentRowEl.removeClass('grid-row-insert-above');
				}

				if (this.rowPosition > 0){
					// If the pointer is on the bottom half of the row.
					this.currentRowEl = new Ext.Element(currentRow);
					this.currentRowEl.addClass('grid-row-insert-below');
				}else{
					// If the pointer is on the top half of the row.
					if (rindex - 1 >= 0){
						var previousRow = this.grid.getView().getRow(rindex - 1);
						this.currentRowEl = new Ext.Element(previousRow);
						this.currentRowEl.addClass('grid-row-insert-below');
					}else{
						// If the pointer is on the top half of the first row.
						this.currentRowEl.addClass('grid-row-insert-above');
					}
				}
			}catch(err){
				console.warn(err);
				rindex = false;
			}

			// Prevent drop on rows with different grouped field (if datastore is Ext.data.GroupingStore)
			if(dd.grid.getStore() instanceof Ext.data.GroupingStore){
				var groupingField = dd.grid.getStore().groupField;
				var targetRecord = dd.grid.getStore().getAt(rindex);
				var draggedRecord = dd.dragData.selections[0];
				if(targetRecord.get(groupingField) != draggedRecord.get(groupingField)){
					return this.dropNotAllowed;
				}
			}

			return (rindex === false)? this.dropNotAllowed : this.dropAllowed;
		},

		notifyOut: function(dd, e, data){
			// Remove drag lines when pointer leaves the gridView.
			if (this.currentRowEl){
				this.currentRowEl.removeClass('grid-row-insert-above');
				this.currentRowEl.removeClass('grid-row-insert-below');
			}
		}
	});

	if (config){
		Ext.apply(this.target, config);
		if (config.listeners){
			Ext.apply(this,{
				listeners: config.listeners
			});
		}
	}

	this.addEvents({
		'beforerowmove': true,
		'afterrowmove': true,
		'beforerowcopy': true,
		'afterrowcopy': true
	});

	Ext.ux.dd.GridReorderDropTarget.superclass.constructor.call(this);
};

Ext.extend(Ext.ux.dd.GridReorderDropTarget, Ext.util.Observable, {
	getTarget: function(){
		return this.target;
	},

	getGrid: function(){
		return this.target.grid;
	},

	getCopy: function(){
		return this.target.copy ? true : false;
	},

	setCopy: function(b){
		this.target.copy = b ? true : false;
	}
});