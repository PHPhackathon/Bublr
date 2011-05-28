<!doctype html>
<html lang="en">
<head>

	{* meta data *}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>{if $pageTitle}{' - '|implode:$pageTitle} - {/if}{$config.siteName|escape}</title>
	<meta name="keywords" content="{$config.metaKeywords|escape}" />
	<meta name="description" content="{$metaDescription|strip_tags|truncate:200|default:$config.metaDescription|escape}" />
	<meta name="author" content="Bytelogic.be" />
	<meta name="language" content="nl" />
	<link rel="shortcut icon" href="{$config.siteUrl}images/front/favicon.ico?v2" />
	{* meta data *}

	{* stylesheets *}
	<link rel="stylesheet" type="text/css" media="screen" href="{$config.siteUrl}css/front/screen.css" />
	{* stylesheets *}

	{* scripts *}
	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
	<script>!window.jQuery && document.write(unescape('%3Cscript src="{$config.siteUrl}javascript/libraries/jquery-1.4.4/jquery-1.4.4.min.js"%3E%3C/script%3E'))</script>
	{* scripts *}

	{* google analytics *}
	<script type="text/javascript">
		var _gaq = _gaq || [];
		_gaq.push(['_setAccount', '{$googleAnalyticsAccount}']);
		_gaq.push(['_setDomainName', location.hostname]);
		_gaq.push(['_trackPageview']);
		(function() {
			var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
			ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
		})();
	</script>
	{* google analytics *}

</head>
<body>

    {* modal window *}
    <section class="main-selection">
        
        {* selection form *}
        <form method="post" action="" class="three-col-layout">
            <fieldset>
            
                {* col one: category *}
                <div class="col">
                    <label for="frm_main-selection_category" class="fruitcake">1. Categorie</label>
                    <select name="category" id="frm_main-selection_category" class="uniformSelect">
                        <option value="">- Kies uw categorie -</option>
                        {loop $themes}
    						<option value="{$id}">{$title|escape}</option>
    					{/loop}
                    </select>
                    
                    <div class="buddy buddy-bottom-right">
                        <div class="selection-explaination markup">
                            <span class="heading">Kies een categorie</span>
                            <p>
                                Op Bublr kan je de sociale status van allerlei gadgets vergelijken. Er wordt
                                op Twitter tenslotte heel wat geplaatst over de gadgets die aangekocht werden, 
                                meningen die ook van belang kunnen zijn in jouw aankoopproces. Geef hierboven 
                                een categorie aan en we geven je graag een overzicht van producten in die 
                                categorie. We hebben gegevens van een reeks gadgets, vooral elektronische 
                                hebbedingetjes. Per categorie tonen we een reeks populaire producten die 
                                vaak verkocht worden, je krijgt dan een vluchtig overzicht van de 
                                vooraanstaande en iets minder vooraanstaande producten in die categorie, 
                                besproken door consumenten net als jij.
                                <br/>&nbsp;
                            </p>
                        </div>
                    </div>
                    
                </div>
                {* col one: category *}
                
                {* col two: price *}
                <div class="col">
                    <label for="frm_main-selection_price" class="fruitcake">2. Prijs</label>
                    <select name="price" id="frm_main-selection_price" class="uniformSelect">
                        <option value="">- Kies binnen prijsbereik -</option>
                    </select>
                    
                    <div class="buddy buddy-top-left"></div>
                    <div class="selection-explaination markup">
                        <span class="heading">Kies een prijsklasse</span>
                        <p>
                            Het heeft niet veel zin je de nieuwste, duurste smartphones voor te schotelen 
                            als je op zoek bent naar een goedkoop, duurzaam toestel. Een prijsklasse aanduiden 
                            geeft je dan ook weer een stevige extra filter, zodat we je enkel de producten waar 
                            je in geïnteresseerd bent kunnen voorschotelen. Voor iedere prijsklasse hebben we 
                            gegevens van producten, met de prijs waarvoor je ze ook in de winkel kan terugvinden. 
                            Het toont op deze manier een vlot overzicht van de kwaliteit die je voor je geld 
                            kan krijgen in die categorie.
                        </p>
                    </div>
                </div>
                {* col two: price *}
                
                {* col three: submit button *}
                <div class="col">
                    <label class="fruitcake">3. Zoek</label>
                    <button type="submit" class="search" onclick="showSite(); return false;">Zoeken</button>
                    
                    <div class="buddy buddy-top-right">
                        <div class="selection-explaination markup">
                            <span class="heading">Klik op zoeken</span>
                            <p>
                                Dat waren alle gegevens die we van je nodig hadden. Een klik op bovenstaande 
                                knop is voor ons voldoende om gegevens bij elkaar te zoeken van een reeks 
                                producten en ze grafisch voor je te presenteren. Zometeen vind je een reeks 
                                bellen, waarvan ieder een zeker product vertegenwoordigd. Hoe hoger de bel 
                                staat, hoe positiever ze besproken werd en hoe groter de bel, hoe meer ze 
                                besproken werd. Een grote bel die helemaal vanboven staat is dan ook een 
                                betrouwbare bron van informatie.
                                <br/>&nbsp;
                            </p>
                        </div>
                    </div>
                    
                </div>
                {* col three: submit button *}
            
            </fieldset>
        </form>
        {* selection form *}
        
        {* about boxes *}
        <div class="two-col-layout credits">
        
            {* col one: about the application *}
            <div class="col markup">
                <span class="heading">Over Bublr</span>
                <p>
                    Bublr is een concept voor het vergelijken van producten aan de hand 
                    van de mening van andere consumenten. De gegevens die wij verzamelen 
                    geven een breed beeld van een product, breder dan de reviews die je op 
                    websites kan lezen. Al deze gegevens worden overzichtelijk in bellen 
                    weergegeven, wat het voor jouw gemakkelijker maakt een betrouwbare 
                    mening over een product te vormen. Met Bublr zal je niet meer thuiskomen 
                    met een kat in een zak.
                </p>
            </div>
            {* col one: about the application *}
            
            {* col two: about the team *}
            <div class="col markup">
                <span class="heading">Het team achter Bublr</span>
                <p>
                    Bart Geraerts - <a href="http://twitter.com/geraertsbart">@geraertsbart</a> - Slicing<br/>
                    Dirk Bonhomme - <a href="http://twitter.com/dirkbonhomme">@dirkbonhomme</a> - Development<br/>
                    Stefan Hellings - <a href="http://twitter.com/Hellings_Stefan">@Hellings_Stefan</a> - Design<br/>
                    Jorgen Evers - <a href="http://twitter.com/NoSTaBoNN">@NoSTaBoNN</a> - Development<br/>
                    Dennis Janssen - <a href="http://twitter.com/dennisjanssen">@dennisjanssen</a> - Mobile Design &amp; Copy<br/>
                </p>
            </div>
            {* col two: about the team *}
        
        </div>
        {* about boxes *}
        
    </section>
    {* modal window *}
    
    
    {* modal window *}
    <section class="product-detail">
    
        <div class="product-container">
        
            {* product data container *}
            <div class="product-data">
            
                <h1 class="fruitcake" id="productTitle">Productnaam komt hier</h1>
                
                <img src="" alt="" id="productImage" />
                
                <strong>Beschrijving:</strong>
            
            </div>
            {* product data container *}
            
            {* twitter feed *}
            <div class="twitter-feed">
            
                <span class="twitter-stats">Rating: <strong id="productTwitterRating">Hoog</strong> #Gespreksonderwerp: <strong id="productTwitterRate">Frequent</strong></span>
            
                <div class="twitter-timeline" id="twitterTimeline">
                    <ul>
                        <li>
                            Tweets hier..
                        </li>
                    </ul>
                </div>
            
            </div>
            {* twitter feed *}
            
        </div>
    
    </section>
    {* modal window *}
    
    
    <div id="fullScreenModal" style="width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); position: absolute; top: 0; left: 0; z-index: 999;"></div>
    
    
    {* header *}
    <header>
        
        <a href="" class="logo">Bublr</a>
        
        {* current selection container, will be populated by JavaScript *}
        <div class="current-selection">
        
            <dl>
                <dt class="fruitcake">Categorie:</dt>
                <dd id="current-category">MegaSpiegelreflexcamera's</dd>
                
                <dt class="fruitcake">Prijs:</dt>
                <dd id="current-pricerange">&euro;&nbsp;8.599,-</dd>
            </dl>
            
            <select name="product_id" id="product_id" class="uniformSelect">
                <option value="">- kies een product -</option>
            </select>
            
        </div>
        {* current selection container, will be populated by JavaScript *}
        
        <a href="" id="toggle-instructions">Nieuwe zoekopdracht</a>
        
    </header>
    {* header *}
    
    {* content area *}
    <section class="content slider-wrapper">
    
        {* slider content will be populated by JavaScript *}
        <div class="slider-content"></div>
        
        {* slider rank images *}
        <div class="rank-bar"></div>
        <span class="rank-positive"></span>
        <span class="rank-negative"></span>
        
        {* slider controls *}
        <a href="#" id="slider-prev" class="slider-control control-left">links</a>
        <a href="#" id="slider-next" class="slider-control control-right">rechts</a>
    
    </section>
    {* content area *}

	{* scripts *}
	<script type="text/javascript" src="{$config.siteUrl}javascript/libraries/fancybox-1.3.4/jquery.fancybox-1.3.4.pack.js"></script>
	<script type="text/javascript" src="{$config.siteUrl}javascript/front/general.js"></script>
	{* scripts *}

</body>
</html>