/**
 * Search field for toolbars
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-15
 */
Admin.SearchField = {

	/**
	 * Create new instance
	 */
	instance: function(){
	
		// Create field
		var fieldConfig = Ext.apply({		
			width: 250,
			triggerClass: 'admin-search-field',
			emptyText: 'Geef uw zoekterm in',
			paramName: 'search',
			minChars: 2,
			queryDelay: 300,
			enableKeyEvents: true
		}, arguments[0] || {});
		var field = new Ext.form.TriggerField(fieldConfig);
		
		// Create search function
		field.search = function(){
			var value = this.getValue();
			this.store.setBaseParam(this.paramName, value);
			this.store.reload({
				start: 0
			});
		};
		
		// Handle trigger click
		field.onTriggerClick = function(e){
			this.setValue('');
			this.search();
			this.focus();
		}

		// Handle live search
		field.on('keyup', function(field, e){

			// Cancel previous deferred call
			if(this.deferId){
				clearTimeout(this.deferId);
				this.deferId = null;
			}
			
			// Validate value length and search after delay
			var value = this.getValue();
			if((e.getKey() == e.BACKSPACE) || !(e.isSpecialKey() || e.isNavKeyPress())){
				if(value.length == 0){
					this.search();
				}else if(value.length >= this.minChars){
					this.deferId = this.search.defer(this.queryDelay, this);
				}
			}
		});
		
		return field;
	}

}