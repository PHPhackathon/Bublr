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
                    <select name="category" id="frm_main-selection_category">
                        <option value="">- Kies uw categorie -</option>
                    </select>
                </div>
                {* col one: category *}
                
                {* col two: price *}
                <div class="col">
                    <label for="frm_main-selection_price" class="fruitcake">2. Prijs</label>
                    <select name="price" id="frm_main-selection_price">
                        <option value="">- Kies binnen prijsbereik -</option>
                    </select>
                </div>
                {* col two: price *}
                
                {* col three: submit button *}
                <div class="col">
                    <label class="fruitcake">3. Zoek</label>
                    <button type="submit">Zoeken</button>
                </div>
                {* col three: submit button *}
            
            </fieldset>
        </form>
        {* selection form *}
        
        {* instruction boxes *}
        <div class="three-col-layout instruction-buddies">
        
            {* col one: category instruction *}
            <div class="col markup buddy-bottom-right">
                <span class="heading">Kies een categorie</span>
                <p>
                    Adipisicing elit, sed do eiusmod tempor incididunt ut labore 
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo 
                    consequat. Duis aute irure dolor in reprehenderit in voluptate 
                    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur 
                    sint occaecat cupidatat non proident, sunt in culpa qui officia 
                    deserunt mollit anim id est laborum.
                </p>
            </div>
            {* col one: category instruction *}
            
            {* col two: price instruction *}
            <div class="col markup buddy-top-left">
                <span class="heading">Kies een categorie</span>
                <p>
                    Adipisicing elit, sed do eiusmod tempor incididunt ut labore 
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo 
                    consequat. Duis aute irure dolor in reprehenderit in voluptate 
                    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur 
                    sint occaecat cupidatat non proident, sunt in culpa qui officia 
                    deserunt mollit anim id est laborum.
                </p>
            </div>
            {* col two: price instruction *}
            
            {* col three: results instruction *}
            <div class="col markup buddy-top-right">
                <span class="heading">Kies een categorie</span>
                <p>
                    Adipisicing elit, sed do eiusmod tempor incididunt ut labore 
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo 
                    consequat. Duis aute irure dolor in reprehenderit in voluptate 
                    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur 
                    sint occaecat cupidatat non proident, sunt in culpa qui officia 
                    deserunt mollit anim id est laborum.
                </p>
            </div>
            {* col three: results instruction *}
        
        </div>
        {* instruction boxes *}
        
        {* about boxes *}
        <div class="two-col-layout credits">
        
            {* col one: about the application *}
            <div class="col markup">
                <span class="heading">Over Bublr</span>
                <p>
                    Adipisicing elit, sed do eiusmod tempor incididunt ut labore 
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo 
                    consequat. Duis aute irure dolor in reprehenderit in voluptate 
                    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur 
                    sint occaecat cupidatat non proident, sunt in culpa qui officia 
                    deserunt mollit anim id est laborum.
                </p>
            </div>
            {* col one: about the application *}
            
            {* col two: about the team *}
            <div class="col markup">
                <span class="heading">Over het Bublr team</span>
                <p>
                    Adipisicing elit, sed do eiusmod tempor incididunt ut labore 
                    et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud 
                    exercitation ullamco laboris nisi ut aliquip ex ea commodo 
                    consequat. Duis aute irure dolor in reprehenderit in voluptate 
                    velit esse cillum dolore eu fugiat nulla pariatur. Excepteur 
                    sint occaecat cupidatat non proident, sunt in culpa qui officia 
                    deserunt mollit anim id est laborum.
                </p>
            </div>
            {* col two: about the team *}
        
        </div>
        {* about boxes *}
        
    </section>
    {* modal window *}
    
    
    <div style="width: 100%; height: 100%; background: rgba(0, 0, 0, 0.8); position: absolute; top: 0; left: 0; z-index: 999;"></div>
    
    
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
            
            <select name="product_id" id="product_id">
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