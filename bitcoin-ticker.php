<?php
/*
Plugin Name: Bitcoin Ticker
Plugin URI: http://www.renzramos.com/wordpress/plugins/bitcoin-ticker
Description: Simple bitcoin ticker using CoinDesk API
Version: 1.0
Author: Renz Ramos
Author URI: http://www.renzramos.com
License: GPL2
*/

$GLOBALS['source'] = "http://api.coindesk.com/v1/bpi/currentprice/usd.json";


function bitcoin_ticker_scripts(){
	wp_enqueue_style( 'bitcoin-ticker-style', plugin_dir_url(__FILE__) . 'assets/css/style.css', array(), null  );
	wp_enqueue_script('bitcoin-ticker-script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), "3.0", true);


	$url = array( 
		'ajax_url' => admin_url( 'admin-ajax.php' ), 
		'we_value' => 1234 
	);
	wp_localize_script( 'bitcoin-ticker-script', 'bitcoin', $url );
}

if (!is_front_page()){
   add_action( 'wp_enqueue_scripts','bitcoin_ticker_scripts');
}


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
    	
	return "<div class='bitcoin-ticker' title='From " . $from . "'><img alt='Bitcoin Logo' src='" . plugin_dir_url(__FILE__) . 'assets/images/bitcoin-logo.png' . "' class='bitcoin-logo'/><span class='bitcoin-rate'>" . number_format($rate,2, '.', '') . "</span></div>";
}
add_shortcode( 'bitcoin_ticker', 'bitcoin_ticker_function' );

// ajax


add_action( 'wp_ajax_bitcoin_ticket_ajax', 'bitcoin_ticket_ajax' );
add_action( 'wp_ajax_nopriv_bitcoin_ticket_ajax', 'bitcoin_ticket_ajax' );

function bitcoin_ticket_ajax() {
    
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
    
	$rate = number_format($rate,2, '.', '');
	echo $rate;
	wp_die(0); // this is required to terminate immediately and return a proper response
}


?>