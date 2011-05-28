$(document).ready(function(){

	/**
	 * Initialize fancybox
	 */
	$('a.fancybox').fancybox({
		'transitionIn':			'elastic',
		'transitionOut':		'elastic',
		'titlePosition':		'over',
		'hideOnContentClick':	true
	});

	/**
	 * Make external links point to blank
	 */
	$('a[rel=external]').each(function(){
		this.target = '_blank';
	});

});