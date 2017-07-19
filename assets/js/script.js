jQuery(document).ready(function($) {

	
	checkBitcoin();
	function checkBitcoin(){
		var data = {
			'action': 'bitcoin_ticket_ajax'
		};
		
		if ($(".bitcoin-ticker .bitcoin-rate").html() != ""){
			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			jQuery.post(bitcoin.ajax_url, data, function(newRate) {
	
	
				var currentRate = 0;
				currentRate = $(".bitcoin-ticker .bitcoin-rate").html();
	
				if (currentRate != newRate){
				
					newRate = parseFloat(newRate);
					$(".bitcoin-ticker .bitcoin-rate").addClass("animate").html(newRate.toFixed(2));
	
					console.log('Bitcoin Current: ' + currentRate);
					console.log('Bitcoin Rate: ' + newRate);
				}else{
					$(".bitcoin-ticker .bitcoin-rate").removeClass("animate");
				}
	
				
			});
		}
	}


	setInterval(function(){ 
		checkBitcoin();
	}, 60000);

});