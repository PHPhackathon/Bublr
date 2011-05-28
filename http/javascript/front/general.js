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
	
	
	/* NiceForm select and file upload */
    if($('.uniformSelect').length){
        $('.uniformSelect').uniform();
    }
    
    
    
    // Show modal and settings on toggle-instructions click
    $('#toggle-instructions').click(function(){
        
        // Fade the modal and the instructions panel (main panel)
        $('#fullScreenModal').fadeIn();
        $('.main-selection').fadeIn();
        
        
    });
    
    // Handle click on a product
    $('.bubble-wrap').click(function(){
        $('#fullScreenModal').fadeIn();
        $('.product-detail').fadeIn();
    });
    

});


function showSite()
{
    // Fade the modal and the instructions panel (main panel)
    $('.main-selection').fadeOut();
    $('#fullScreenModal').fadeOut();
}


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
function addBubble( bubl )
{
	var buble = new Buble( bubl['score'], bubl['tweet_count'], bubl['score'] >= 0, bubl['title'] );
	buble.appendTo( '.slider-content' );
    /*$('.slider-content').append(bluePrintBubble.format('10%', '4%', '10%', 'very-positive', 'Een wat langere titel voor dit product'));
    $('.slider-content').append(bluePrintBubble.format('5%', '52%', '15%', 'very-negative', 'Nokia X6'));
    $('.slider-content').append(bluePrintBubble.format('15%', '16%', '33%', 'very-positive', 'iPhone 3GS'));
    $('.slider-content').append(bluePrintBubble.format('20%', '60%', '19%', 'very-negative', 'HP Deskjet 615'));
    $('.slider-content').append(bluePrintBubble.format('10%', '10%', '70%', 'very-positive', 'iPhone 4'));
    $('.slider-content').append(bluePrintBubble.format('7%', '34%', '44%', 'very-positive', 'iPad 2'));
    $('.slider-content').append(bluePrintBubble.format('18%', '68%', '42%', 'very-negative', 'Macintosh'));
    $('.slider-content').append(bluePrintBubble.format('14%', '56%', '63%', 'very-negative', 'Powerball'));
    */
}

function loadBubls(){
	$.ajax('/bubls/' + category, {
		dataType: 'json',
		method: 'GET',
		success: function(data){
			Bubl.score_max = data.score_range.max;
			Bubl.score_min = data.score_range.min;
			
			for( var i in data.products )
				addBuble( data.products[i] );
		}
	})
}

/**
 * Represents a buble.
 */
function Bubl( score, size, positive, name ){
	this.size = size;
	this.rating = positive ? 'positive' : 'vnegative';
	this.name = name;
	this.left = 15 + Math.floor( Math.random() * 70 );
	this.score = score;
}

Bubl.score_max = 0;
Bubl.score_min = 0;

/**
 * Append the buble to the selector element.
 */
Bubl.prototype.appendTo = function( selector ){
	var bluePrintBubble = '<div class="bubble-wrap" style="width: {0}; height: {0}; position: absolute; top: {1}; left: {2}">' +
	    '<div class="outer-bubble {3}">' +
	        '<div class="inner-bubble"></div>' +
	        '<div class="highlight"></div>' +
	        '<div class="highlight highlight-small"></div>' +
	    '</div>' +
	    '<div class="caption">{4}</div>' +
	'</div>';
	
	var width = this.size/10;
	var top = this.score > 0 ? this.score/Bubl.score_max : this.score/Bubl.score_min;
	var left = this.left;
	$( selector ).append( bluePrintBubble.format( width, top, left, 'very-' + this.rating, name ) );
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

/**
 * Uniform html select
 */
(function(a){a.uniform={options:{selectClass:"selector",radioClass:"radio",checkboxClass:"checker",fileClass:"uploader",filenameClass:"filename",fileBtnClass:"action",fileDefaultText:"No file selected",fileBtnText:"Choose File",checkedClass:"checked",focusClass:"focus",disabledClass:"disabled",buttonClass:"button",activeClass:"active",hoverClass:"hover",useID:true,idPrefix:"uniform",resetSelector:false},elements:[]};if(a.browser.msie&&a.browser.version<7){a.support.selectOpacity=false}else{a.support.selectOpacity=true}a.fn.uniform=function(k){k=a.extend(a.uniform.options,k);var d=this;if(k.resetSelector!=false){a(k.resetSelector).mouseup(function(){function l(){a.uniform.update(d)}setTimeout(l,10)})}function j(l){$el=a(l);$el.addClass($el.attr("type"));b(l)}function g(l){a(l).addClass("uniform");b(l)}function i(n){$el=n;var o=a("<div>"),l=a("<span>");o.addClass(k.buttonClass);if(k.useID&&$el.attr("id")!=""){o.attr("id",k.idPrefix+"-"+$el.attr("id"))}var m;if($el.is("a")){m=$el.text()}else{if($el.is("button")){m=$el.text()}else{if($el.is(":submit")||$el.is("input[type=button]")){m=$el.attr("value")}}}if(m==""){m="Submit"}l.html(m);$el.hide();$el.wrap(o);$el.wrap(l);o=$el.closest("div");l=$el.closest("span");if($el.is(":disabled")){o.addClass(k.disabledClass)}o.bind({"mouseenter.uniform":function(){o.addClass(k.hoverClass)},"mouseleave.uniform":function(){o.removeClass(k.hoverClass)},"mousedown.uniform touchbegin.uniform":function(){o.addClass(k.activeClass)},"mouseup.uniform touchend.uniform":function(){o.removeClass(k.activeClass)},"click.uniform touchend.uniform":function(q){if(a(q.target).is("span")||a(q.target).is("div")){if(n[0].dispatchEvent){var p=document.createEvent("MouseEvents");p.initEvent("click",true,true);n[0].dispatchEvent(p)}else{n[0].click()}}}});n.bind({"focus.uniform":function(){o.addClass(k.focusClass)},"blur.uniform":function(){o.removeClass(k.focusClass)}});a.uniform.noSelect(o);b(n)}function e(n){var o=a("<div />"),l=a("<span />");o.addClass(k.selectClass);if(k.useID&&n.attr("id")!=""){o.attr("id",k.idPrefix+"-"+n.attr("id"))}var m=n.find(":selected:first");if(m.length==0){m=n.find("option:first")}l.html(m.text());n.css("opacity",0);n.wrap(o);n.before(l);o=n.parent("div");l=n.siblings("span");n.bind({"change.uniform":function(){l.text(n.find(":selected").text());o.removeClass(k.activeClass)},"focus.uniform":function(){o.addClass(k.focusClass)},"blur.uniform":function(){o.removeClass(k.focusClass);o.removeClass(k.activeClass)},"mousedown.uniform touchbegin.uniform":function(){o.addClass(k.activeClass)},"mouseup.uniform touchend.uniform":function(){o.removeClass(k.activeClass)},"click.uniform touchend.uniform":function(){o.removeClass(k.activeClass)},"mouseenter.uniform":function(){o.addClass(k.hoverClass)},"mouseleave.uniform":function(){o.removeClass(k.hoverClass)},"keyup.uniform":function(){l.text(n.find(":selected").text())}});if(a(n).attr("disabled")){o.addClass(k.disabledClass)}a.uniform.noSelect(l);b(n)}function f(m){var n=a("<div />"),l=a("<span />");n.addClass(k.checkboxClass);if(k.useID&&m.attr("id")!=""){n.attr("id",k.idPrefix+"-"+m.attr("id"))}a(m).wrap(n);a(m).wrap(l);l=m.parent();n=l.parent();a(m).css("opacity",0).bind({"focus.uniform":function(){n.addClass(k.focusClass)},"blur.uniform":function(){n.removeClass(k.focusClass)},"click.uniform touchend.uniform":function(){if(!a(m).attr("checked")){l.removeClass(k.checkedClass)}else{l.addClass(k.checkedClass)}},"mousedown.uniform touchbegin.uniform":function(){n.addClass(k.activeClass)},"mouseup.uniform touchend.uniform":function(){n.removeClass(k.activeClass)},"mouseenter.uniform":function(){n.addClass(k.hoverClass)},"mouseleave.uniform":function(){n.removeClass(k.hoverClass)}});if(a(m).attr("checked")){l.addClass(k.checkedClass)}if(a(m).attr("disabled")){n.addClass(k.disabledClass)}b(m)}function c(m){var n=a("<div />"),l=a("<span />");n.addClass(k.radioClass);if(k.useID&&m.attr("id")!=""){n.attr("id",k.idPrefix+"-"+m.attr("id"))}a(m).wrap(n);a(m).wrap(l);l=m.parent();n=l.parent();a(m).css("opacity",0).bind({"focus.uniform":function(){n.addClass(k.focusClass)},"blur.uniform":function(){n.removeClass(k.focusClass)},"click.uniform touchend.uniform":function(){if(!a(m).attr("checked")){l.removeClass(k.checkedClass)}else{a("."+k.radioClass+" span."+k.checkedClass+":has([name='"+a(m).attr("name")+"'])").removeClass(k.checkedClass);l.addClass(k.checkedClass)}},"mousedown.uniform touchend.uniform":function(){if(!a(m).is(":disabled")){n.addClass(k.activeClass)}},"mouseup.uniform touchbegin.uniform":function(){n.removeClass(k.activeClass)},"mouseenter.uniform touchend.uniform":function(){n.addClass(k.hoverClass)},"mouseleave.uniform":function(){n.removeClass(k.hoverClass)}});if(a(m).attr("checked")){l.addClass(k.checkedClass)}if(a(m).attr("disabled")){n.addClass(k.disabledClass)}b(m)}function h(q){var o=a(q);var r=a("<div />"),p=a("<span>"+k.fileDefaultText+"</span>"),m=a("<span>"+k.fileBtnText+"</span>");r.addClass(k.fileClass);p.addClass(k.filenameClass);m.addClass(k.fileBtnClass);if(k.useID&&o.attr("id")!=""){r.attr("id",k.idPrefix+"-"+o.attr("id"))}o.wrap(r);o.after(m);o.after(p);r=o.closest("div");p=o.siblings("."+k.filenameClass);m=o.siblings("."+k.fileBtnClass);if(!o.attr("size")){var l=r.width();o.attr("size",l/10)}var n=function(){var s=o.val();if(s===""){s=k.fileDefaultText}else{s=s.split(/[\/\\]+/);s=s[(s.length-1)]}p.text(s)};n();o.css("opacity",0).bind({"focus.uniform":function(){r.addClass(k.focusClass)},"blur.uniform":function(){r.removeClass(k.focusClass)},"mousedown.uniform":function(){if(!a(q).is(":disabled")){r.addClass(k.activeClass)}},"mouseup.uniform":function(){r.removeClass(k.activeClass)},"mouseenter.uniform":function(){r.addClass(k.hoverClass)},"mouseleave.uniform":function(){r.removeClass(k.hoverClass)}});if(a.browser.msie){o.bind("click.uniform.ie7",function(){setTimeout(n,0)})}else{o.bind("change.uniform",n)}if(o.attr("disabled")){r.addClass(k.disabledClass)}a.uniform.noSelect(p);a.uniform.noSelect(m);b(q)}a.uniform.restore=function(l){if(l==undefined){l=a(a.uniform.elements)}a(l).each(function(){if(a(this).is(":checkbox")){a(this).unwrap().unwrap()}else{if(a(this).is("select")){a(this).siblings("span").remove();a(this).unwrap()}else{if(a(this).is(":radio")){a(this).unwrap().unwrap()}else{if(a(this).is(":file")){a(this).siblings("span").remove();a(this).unwrap()}else{if(a(this).is("button, :submit, a, input[type='button']")){a(this).unwrap().unwrap()}}}}}a(this).unbind(".uniform");a(this).css("opacity","1");var m=a.inArray(a(l),a.uniform.elements);a.uniform.elements.splice(m,1)})};function b(l){l=a(l).get();if(l.length>1){a.each(l,function(m,n){a.uniform.elements.push(n)})}else{a.uniform.elements.push(l)}}a.uniform.noSelect=function(l){function m(){return false}a(l).each(function(){this.onselectstart=this.ondragstart=m;a(this).mousedown(m).css({MozUserSelect:"none"})})};a.uniform.update=function(l){if(l==undefined){l=a(a.uniform.elements)}l=a(l);l.each(function(){var n=a(this);if(n.is("select")){var m=n.siblings("span");var p=n.parent("div");p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);m.html(n.find(":selected").text());if(n.is(":disabled")){p.addClass(k.disabledClass)}else{p.removeClass(k.disabledClass)}}else{if(n.is(":checkbox")){var m=n.closest("span");var p=n.closest("div");p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);m.removeClass(k.checkedClass);if(n.is(":checked")){m.addClass(k.checkedClass)}if(n.is(":disabled")){p.addClass(k.disabledClass)}else{p.removeClass(k.disabledClass)}}else{if(n.is(":radio")){var m=n.closest("span");var p=n.closest("div");p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);m.removeClass(k.checkedClass);if(n.is(":checked")){m.addClass(k.checkedClass)}if(n.is(":disabled")){p.addClass(k.disabledClass)}else{p.removeClass(k.disabledClass)}}else{if(n.is(":file")){var p=n.parent("div");var o=n.siblings(k.filenameClass);btnTag=n.siblings(k.fileBtnClass);p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);o.text(n.val());if(n.is(":disabled")){p.addClass(k.disabledClass)}else{p.removeClass(k.disabledClass)}}else{if(n.is(":submit")||n.is("button")||n.is("a")||l.is("input[type=button]")){var p=n.closest("div");p.removeClass(k.hoverClass+" "+k.focusClass+" "+k.activeClass);if(n.is(":disabled")){p.addClass(k.disabledClass)}else{p.removeClass(k.disabledClass)}}}}}}})};return this.each(function(){if(a.support.selectOpacity){var l=a(this);if(l.is("select")){if(l.attr("multiple")!=true){if(l.attr("size")==undefined||l.attr("size")<=1){e(l)}}}else{if(l.is(":checkbox")){f(l)}else{if(l.is(":radio")){c(l)}else{if(l.is(":file")){h(l)}else{if(l.is(":text, :password, input[type='email']")){j(l)}else{if(l.is("textarea")){g(l)}else{if(l.is("a")||l.is(":submit")||l.is("button")||l.is("input[type=button]")){i(l)}}}}}}}}})}})(jQuery);