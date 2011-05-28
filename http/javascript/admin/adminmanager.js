/**
 * AdminManager main component
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-11
 */
var Admin = {};
var AdminManager = {

	mainPanel: null,
	headerPanel: null,
	viewPort: null,

	/**
	 * Set main panel contents
	 *
	 * @param Ext.Panel panel
	 */
	setMainPanel: function(panel){
		AdminManager.mainPanel.add(panel);
	},

	/**
	 * Activate tab
	 *
	 * @param int tabIndex
	 */
	activateTab: function(tabIndex){
		AdminManager.mainPanel.activate(tabIndex);
	},

	/**
	 * Show error message with form submit result
	 *
	 * @param Ext.form.Action action
	 */
	showFormFailure: function(action){
		var message;
		switch (action.failureType) {
			case Ext.form.Action.CLIENT_INVALID:
				message = 'Niet alle velden zijn correct ingevuld.';
				break;
			case Ext.form.Action.CONNECT_FAILURE:
				message = 'Er is een verbindingsfout opgetreden.';
				break;
			case Ext.form.Action.SERVER_INVALID:
				message = action.result.msg;
				break;
			default:
				message = 'Er is een onbekende fout opgetreden';
				break;
		}

		Ext.Msg.show({
			title: 'Fout bij opslaan',
			msg: message,
			buttons: Ext.Msg.OK,
			icon: Ext.Msg.ERROR
		});
	},

	/**
	 * Generate string with image tag and title. Used in TabPanel
	 *
	 * @param string icon
	 * @param string title
	 * @return string
	 */
	iconTitle: function(icon, title){
		return String.format(
			'<img src="{0}images/icons/{1}" width="16" height="16" /> {2}',
			ApplicationConfig.siteUrl,
			icon,
			title
		);
	},

	/**
	 * Generate collapsible <p> tag. Used in Grid
	 *
	 * @param string text
	 * @return string
	 */
	collapsibleText: function(text){
		if(!text) return '';
		return String.format(
			'<p class="admin-grid-collapsible-text" onclick="Ext.get(this).toggleClass(\'expanded\');">{0}</p>',
			Ext.util.Format.nl2br(text)
		);
	},

	/**
	 * Convert TreeNode to Record
	 *
	 * @param Ext.tree.TreeNode node
	 * @return Ext.data.Record
	 */
	nodeToRecord: function(node){
		var fields = [];
		for(field in node.attributes){
			fields.push({name: field});
		}
		var RecordConstructor = Ext.data.Record.create(fields);
		return new RecordConstructor(node.attributes);
	}

};

Ext.onReady(function(){

	/**
	 * Initialize QuickTips
	 */
	Ext.QuickTips.init();
	Ext.apply(Ext.QuickTips.getQuickTip(), {
		showDelay: 0,
		hideDelay: 0,
		trackMouse: true
	});

	/**
	 * Initialize viewport and basic panels
	 */
	AdminManager.mainPanel = new Ext.TabPanel({
		region: 'center',
		border: false
	});

	AdminManager.headerPanel = new Ext.Panel({
		region: 'north',
		height: 70,
		contentEl: 'header'
	});

	AdminManager.viewPort = new Ext.Viewport({
		layout: 'border',
		items: [AdminManager.headerPanel, AdminManager.mainPanel]
	});
	AdminManager.viewPort.el.hide();
	
	/**
	 * Save current tab to cookie on change
	 */
	AdminManager.mainPanel.on('tabchange', function(tabPanel, tab){
		var i = null;
		var tabIndex = this.items.each(function(item){
			if(item === tab){
				Ext.util.Cookies.set('admin-active-tab', i, new Date().add(Date.MONTH, 1));
				return;
			}
			i++;
		});
	});

	/**
	 * Catch authorizator errors for all requests
	 */
	Ext.Ajax.on('requestexception', function(conn, response, options){
		Ext.Msg.show({
			title: String.format('{0} - {1}', response.status, response.statusText),
			msg: response.responseText || 'Foutmelding: ' + response.statusText,
			buttons: Ext.Msg.OK,
			icon: Ext.Msg.ERROR
		});
	});

});