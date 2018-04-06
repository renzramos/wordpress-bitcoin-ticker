<?php
/*
Plugin Name: Bitcoin Ticker
Plugin URI: http://www.renzramos.com/wordpress/plugins/bitcoin-ticker
Description: Simple bitcoin ticker using CoinDesk API
Version: 1.1
Author: Renz Ramos
Author URI: http://www.renzramos.com
License: GPL2
*/

$GLOBALS['source'] = "https://api.coindesk.com/v1/bpi/currentprice/usd.json";

function bitcoin_ticker_scripts(){
	wp_enqueue_style( 'bitcoin-ticker-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), null  );
	wp_enqueue_script('bitcoin-ticker-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array(), "3.0", true);


	$url = array( 
		'ajax_url' => admin_url( 'admin-ajax.php' ), 
		'we_value' => 1234 
	);
	wp_localize_script( 'bitcoin-ticker-script', 'bitcoin', $url );
}

add_action( 'wp_enqueue_scripts','bitcoin_ticker_scripts');



// shortcode
function bitcoin_ticker_function( $atts ){
	
	$content = @file_get_contents($GLOBALS['source']);
    $from  = 'CoinDesk';
    
    if ($content) {
        
        $data = json_decode($content);
        $rate = $data->bpi->USD->rate_float;
         
    } else {
        // get fallback
        $content = file_get_contents('https://blockchain.info/ticker');
        $content = json_decode(trim($content));
        $rate = $content->USD->last;
        $from  = 'BlockChain';
    }
	
	$rate = $data->bpi->USD->rate_float;
	return "<div class='bitcoin-ticker' title='Bitcoin Price From " . $from . "'><img alt='Bitcoin Logo' src='" . plugin_dir_url(__FILE__) . 'assets/images/bitcoin-logo.png' . "' class='bitcoin-logo'/><span class='bitcoin-rate'>" . number_format($rate,2, '.', '') . "</span></div>";
}
add_shortcode( 'bitcoin_ticker', 'bitcoin_ticker_function' );

// ajax
add_action( 'wp_ajax_bitcoin_ticket_ajax', 'bitcoin_ticket_ajax' );
add_action( 'wp_ajax_nopriv_bitcoin_ticket_ajax', 'bitcoin_ticket_ajax' );

function bitcoin_ticket_ajax() {
	
	$ch = curl_init();
    curl_setopt ($ch, CURLOPT_URL, $GLOBALS['source']);
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    $contents = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($contents);
	$rate = number_format($data->bpi->USD->rate_float,2, '.', '');
	echo $rate;
	wp_die(); // this is required to terminate immediately and return a proper response
}


?>
