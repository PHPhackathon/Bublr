<?php

	class ThemesFrontController extends FrontController {

		/**
		 * Load coolblue sources.
		 */
		public function coolblue(){
			
			$themes = $this->_get_themes();
			
			foreach( $themes as $item ){
				$theme = $item[0];
				$url = $item[1];
				model('ThemeModel')->frontAddThemeSource( $theme, $url, 'coolblue' );
			}
			
			$this->setPageTitle( 'Import complete.' );
			$this->dislay( 'import/compleet.tpl' );
		}
		
		private function _get_themes(){
			$urls = '<option value="shopid:361">Autoradiostore.be assortiment</option> 
						<option value="shopid:324">Babyfoonstore.be assortiment</option> 
						<option value="shopid:365">Beamercenter.be assortiment</option> 
						<option value="shopid:375">Blurayspelershop.be assortiment</option> 
						<option value="shopid:371">Boormachinestore.be assortiment</option> 
						<option value="shopid:364">Computerstore.be assortiment</option> 
						<option value="shopid:161">Consoleshop.be assortiment</option> 
						<option value="shopid:8">Digicamshop.be assortiment</option> 
						<option value="shopid:379">Epilatorshop.be assortiment</option> 
						<option value="shopid:320">eReaderstore.be assortiment</option> 
						<option value="shopid:395">Fietscomputerstore.be assortiment</option> 
						<option value="shopid:393">Friteusecenter.be assortiment</option> 
						<option value="shopid:241">GPSshop.be assortiment</option> 
						<option value="shopid:350">GSMstore.be assortiment</option> 
						<option value="shopid:356">Hartslagmetercenter.be assortiment</option> 
						<option value="shopid:349">Headsetshop.be assortiment</option> 
						<option value="shopid:391">Homecinemacenter.be assortiment</option> 
						<option value="shopid:347">Hoofdtelefoonstore.be assortiment</option> 
						<option value="shopid:341">Koffiecenter.be assortiment</option> 
						<option value="shopid:306">Ladyphoneshop.be assortiment</option> 
						<option value="shopid:309">Laptopshop.be assortiment</option> 
						<option value="shopid:323">Mediacentershop.be assortiment</option> 
						<option value="shopid:14">Memoryshop.be assortiment</option> 
						<option value="shopid:383">Monitorstore.be assortiment</option> 
						<option value="shopid:3">MP3shop.be assortiment</option> 
						<option value="shopid:7">PDAshop.be assortiment</option> 
						<option value="shopid:318">Powertoolshop.be assortiment</option> 
						<option value="shopid:311">Printershop.be assortiment</option> 
						<option value="shopid:354">Routercenter.be assortiment</option> 
						<option value="shopid:308">Shavershop.be assortiment</option> 
						<option value="shopid:16">Smartphoneshop.be assortiment</option> 
						<option value="shopid:381">Stijltangstore.be assortiment</option> 
						<option value="shopid:339">Stofzuigerstore.be assortiment</option> 
						<option value="shopid:373">Strijkijzerstore.be assortiment</option> 
						<option value="shopid:377">Tabletcenter.be assortiment</option> 
						<option value="shopid:327">Tandenborstelstore.be assortiment</option> 
						<option value="shopid:389">Telefooncenter.be assortiment</option> 
						<option value="shopid:343">Tondeusestore.be assortiment</option> 
						<option value="shopid:352">Videocamerashop.be assortiment</option> 
						<option value="shopid:345">Voicerecordershop.be assortiment</option>';
			
			$pattern = '#">((.+)(store|center|shop).be) assortiment#mUis';
			preg_match_all( $pattern, $urls, $matches );
			
			return array(
				$matches[1],
				$matches[3]
			);
		}
	

	
	
}
	
	
	
