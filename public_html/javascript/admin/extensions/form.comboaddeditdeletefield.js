/**
 * Form field with combobox and add-, edit and delete button
 * Extends Ext.Panel
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-27
 */
Ext.ns('Ext.ux.form');
Ext.ux.form.ComboAddEditDeleteField = Ext.extend(Ext.Panel, {
	
	/**
	 * Basic panel configuration
	 *
	 */
	layout: 'column',
	
	
	/**
	 * Initialize panel and fields
	 *
	 */
	initComponent: function(){
	
		// ComboBox
		var comboBox = new Ext.form.ComboBox(Ext.apply({
			columnWidth: 1,
			mode: 'local',
			triggerAction: 'all',
			editable: false
		}, this.initialConfig.comboBox));
		
		// Delete button
		var deleteButton = new Ext.Button(Ext.apply({
			xtype: 'button',
			width: 22,
			tooltip: 'Verwijderen...',
			icon: ApplicationConfig.siteUrl + 'images/icons/minus-small.png'
		}, this.initialConfig.deleteButton));

		// Edit button
		var editButton = new Ext.Button(Ext.apply({
			width: 22,
			tooltip: 'Wijzigen',
			icon: ApplicationConfig.siteUrl + 'images/icons/pencil-small.png'
		}, this.initialConfig.editButton));
		
		// Add button
		var addButton = new Ext.Button(Ext.apply({
			xtype: 'button',
			width: 22,
			tooltip: 'Nieuw item toevoegen',
			icon: ApplicationConfig.siteUrl + 'images/icons/plus-small.png'
		}, this.initialConfig.addButton));
		
		// Assign items to panel
		this.items = [
			comboBox,
			deleteButton,
			{width:10, html: '&nbsp;'},
			editButton,
			addButton
		];
		
		Ext.apply(this, this.initialConfig);
		Ext.ux.form.ComboAddEditDeleteField.superclass.initComponent.apply(this, arguments);
	}
	
});
Ext.reg('comboaddeditdeletefield', Ext.ux.form.ComboAddEditDeleteField);