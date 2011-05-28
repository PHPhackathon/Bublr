$(document).ready(function(){

	// Request price range on category select
	$('#searchpage select[name=category_id]').change(function(){
		if(!this.value) return;
		
		$('#searchpage select[name=price_range]').empty();
		$('#searchpage select[name=price_range]').append('<option>Even geduld...</option>');
		$('#tweets').empty();
		
		jQuery.getJSON(
			'/bubls/price_range/' + parseInt(this.value),
			function(data){
			
				// Populate price range
				$('#searchpage select[name=price_range]').empty();
				var range;
				var optionTemplate = '<option value="{0}-{1}">€{2} - €{3}</option>';
				for(var i=0; i < data.steps.length; i++){
					range = data.steps[i].split(' - ');
					$('#searchpage select[name=price_range]').append(
						optionTemplate.format(range[0], range[1], Math.round(range[0]), Math.round(range[1]))
					);
				}
				
				// Select first range and activate search button
				$('#searchpage select[name=price_range] option:first').attr('selected', 'selected');
				$('#searchpage select[name=price_range]').trigger('change')
				$('#searchbutton').removeClass('disabled');
			}
		);
	});

	// Request bubls on search
	$('#searchbutton').click(function(){

		// Validate selection
		var categoryId = $('#searchpage select[name=category_id]').get(0).value;
		var priceRange = $('#searchpage select[name=price_range]').get(0).value;
		if(!categoryId){
			alert('Gelieve een categorie te selecteren');
			return;
		}else if(!priceRange){
			alert('Gelieve een prijsklasse te selecteren');
			return;
		}
		
		// Set search button to waiting
		$('#searchbutton').removeClass('disabled');
		$('#searchbutton').addClass('waiting');

		// Request bubls
		jQuery.getJSON(
			'/bubls/mobile_list/' + parseInt(categoryId) + '/' + priceRange,
			function(data){

				// Validate data
				if(!data){
					alert('Helaas, er zijn geen resultaten');
					return;
				}

				// Fill list with bubls
				$('#results select[name=bubl_id]').empty();
				var bubl;
				var optionTemplate = '<option value="{0}">{1}</option>';
				for(var i=0; i < data.length; i++){
					bubl = data[i];
					$('#resultspage select[name=bubl_id]').append(
						optionTemplate.format(bubl.id, bubl.title)
					);
				}
				
				// Select first range and activate search button
				$('#resultspage select[name=bubl_id] option:first').attr('selected', 'selected');
				$('#resultspage select[name=bubl_id]').trigger('change');

				// Show results page
				$.mobile.changePage($('#resultspage'), 'slideup');
			}
		);

	});

	// Request details + tweets on bubl select
	$('#resultspage select[name=bubl_id]').change(function(){
		if(!this.value) return;
		jQuery.getJSON(
			'/bubls/mobile_details/' + parseInt(this.value),
			function(data){
				
				// Validate data
				if(!data){
					alert('Helaas, er zijn geen details voor dit item');
					return;
				}
				
				// Set score
				$('#indicatie').html(data.average_score + '%').css('left', parseInt(data.average_score || 0) + '%');
				
				// Set tweets
				var tweetTemplate = '<div class="blokje" onclick="location=\'http://twitter.com/#!/{1}/status/{3}\'">' +
										'<img src="{0}"/><div><b>{1}</b><p style="font-size:12px;">{2}</p></div>' + 
									'</div>';
				$('#tweets').empty();
				var tweet;
				for(var i=0; i < data.tweets.length; i++){
					tweet = data.tweets[i];
					$('#tweets').append(
						tweetTemplate.format(tweet.profile_image_url, tweet.from_user, tweet.text, tweet.tweet_id)
					);
				}
				
			}
		);
	});

	// Return to search page
	$('#returnbutton').click(function(){
		$('#searchbutton').removeClass('disabled');
		$('#searchbutton').removeClass('waiting');
		$.mobile.changePage($('#searchpage'), 'slideup');
	});

});

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