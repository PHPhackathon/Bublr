$(document).ready(function(){

    /**
	 * Set viewport sizes
	 */
    $(window).resize(function(){
    
        var newHeight = $(window).height() - $('header').height() + 'px';
    
        // Resize the content area
        $('.content').css({
            'width': '100%',
            'height': newHeight
        });
        
        // Resize the slider (moveable) area
        $('.slider-content').css({
            'width': '100%',
            'height': newHeight
        });
        
        $.each($('.bubble-wrap'), function(index, value){
        
            // Fix the bubble-wrap layer's width
            $(this).width($(this).height());
        
            // Make sure the circle stays round :)
            $(this).find('.outer-bubble').css({'width': $(this).height() + 'px'});
            
            // Make sure the caption stays dead center!
            $(this).find('.caption').css({ 'margin-left': (($(this).find('.caption').outerWidth()/2)*-1) + $(this).find('.outer-bubble').width()/2 + 'px' });
            
        });
        
        // Resize the rank bar
        $('.rank-bar').css({
            'height': newHeight
        });
        
    });
    
    addBubble();
    
    $(window).trigger('resize');
    


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


// Blueprint of a bubble
var bluePrintBubble = '<div class="bubble-wrap" style="width: {0}; height: {0}; position: absolute; top: {1}; left: {2}">' +
    '<div class="outer-bubble {3}">' +
        '<div class="inner-bubble"></div>' +
        '<div class="highlight"></div>' +
        '<div class="highlight highlight-small"></div>' +
    '</div>' +
    '<div class="caption">{4}</div>' +
'</div>';

/**
 * Function to create a bubble
 */
function addBubble()
{
    $('.slider-content').append(bluePrintBubble.format('10%', '4%', '10%', 'very-positive', 'Een wat langere titel voor dit product'));
    $('.slider-content').append(bluePrintBubble.format('5%', '52%', '15%', 'very-negative', 'Nokia X6'));
    $('.slider-content').append(bluePrintBubble.format('15%', '16%', '33%', 'very-positive', 'iPhone 3GS'));
    $('.slider-content').append(bluePrintBubble.format('20%', '60%', '19%', 'very-negative', 'HP Deskjet 615'));
    $('.slider-content').append(bluePrintBubble.format('10%', '10%', '70%', 'very-positive', 'iPhone 4'));
    $('.slider-content').append(bluePrintBubble.format('7%', '34%', '44%', 'very-positive', 'iPad 2'));
    $('.slider-content').append(bluePrintBubble.format('18%', '68%', '42%', 'very-negative', 'Macintosh'));
    $('.slider-content').append(bluePrintBubble.format('14%', '56%', '63%', 'very-negative', 'Powerball'));
}


/**
 * Simple JavaScript formatting
 * @link http://stackoverflow.com/questions/610406/javascript-printf-string-format
 */
String.prototype.format = function() {
    var formatted = this;
    for (var i = 0; i < arguments.length; i++) {
        var regexp = new RegExp('\\{'+i+'\\}', 'gi');
        formatted = formatted.replace(regexp, arguments[i]);
    }
    return formatted;
};