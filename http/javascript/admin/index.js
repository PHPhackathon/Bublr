/**
 * Admin main component
 *
 * @author Dirk Bonhomme <dirk@bytelogic.be>
 * @created 2010-10-11
 */
Ext.onReady(function(){

	/**
	 * Show load mask while we create all components
	 */
	var pageLoadMask = new Ext.LoadMask(Ext.getBody(), {msg:'Even geduld aub...'});
	pageLoadMask.show();

	/**
	 * Initialize calendars grid
	 */
	var calendarsGrid = Admin.CalendarsGrid.instance();
	AdminManager.setMainPanel(calendarsGrid);
	
	/**
	 * Initialize photoalbums grid
	 */
	var photoalbumsGrid = Admin.PhotoalbumsGrid.instance();
	AdminManager.setMainPanel(photoalbumsGrid);

	/**
	 * Initialize articles grid
	 */
	var articlesGrid = Admin.ArticlesGrid.instance();
	AdminManager.setMainPanel(articlesGrid);

	/**
	 * Initialize members grid
	 */
	var membersGrid = Admin.MembersGrid.instance();
	AdminManager.setMainPanel(membersGrid);

	/**
	 * Initialize links grid
	 */
	var linksGrid = Admin.LinksGrid.instance();
	AdminManager.setMainPanel(linksGrid);

	/**
	 * Initialize newsletters subscribers grid
	 */
	var newslettersSubscribersGrid = Admin.NewslettersSubscribersGrid.instance();
	AdminManager.setMainPanel(newslettersSubscribersGrid);

	/**
	 * Initialize contact grid
	 */
	var contactGrid = Admin.ContactGrid.instance();
	AdminManager.setMainPanel(contactGrid);

	/**
	 * Initialize administrators grid
	 */
	var administratorsGrid = Admin.AdministratorsGrid.instance();
	AdminManager.setMainPanel(administratorsGrid);

	/**
	 * Activate first tab
	 */
	AdminManager.activateTab(parseInt(Ext.util.Cookies.get('admin-active-tab')) || 0);

	/**
	 * Hide load mask
	 */
	pageLoadMask.hide();
	AdminManager.viewPort.el.fadeIn();

});