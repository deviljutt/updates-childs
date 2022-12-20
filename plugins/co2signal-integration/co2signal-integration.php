<?php
/**
 * Plugin Name: CO2signal Integration
 * Description: CO2signal Integration
 * Author: web_codex
 * Version: 1.0
 */


add_action( 'admin_menu', 'wpse_91693_bbcposts' );

function wpse_91693_bbcposts()
{
    add_menu_page(
        'CO2signal',     
        'CO2signal',    
        'manage_options',   
        'co2signals-api',    
        'wpse_settings_render' ,
		'dashicons-editor-unlink'
    );
}

add_action( 'admin_init', 'register_my_cool_plugin_settings' );
function register_my_cool_plugin_settings() {
	register_setting( 'my-cool-plugin-settings-group', 'notifcationemail' );
}


function wpse_settings_render(){
 ?>
<div class="wrap">


<form method="post" action="options.php">
    <?php settings_fields( 'my-cool-plugin-settings-group' ); ?>
    <?php do_settings_sections( 'my-cool-plugin-settings-group' ); ?>
	
	<h1>Email Notifcation</h1>
	<table class="form-table">
	
        <tr valign="top">
        <th scope="row">API Token</th>
        <td><input type="text" name="notifcationemail" value="<?php echo esc_attr( get_option('notifcationemail') ); ?>" /></td>
        </tr>
    </table>
	

    
    <?php submit_button(); ?>

</form>
</div>
<?php
}





add_shortcode( 'co2signals', 'co2signals_func' );
function co2signals_func( $atts ) {
    $value = "{$atts['region']}";
	$toeken =  esc_attr( get_option('notifcationemail') );
	
	
	
	
	$curl = curl_init();
	curl_setopt_array($curl, array(
	CURLOPT_URL => 'https://api.co2signal.com/v1/latest?countryCode='.$value,
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_ENCODING => '',
	CURLOPT_MAXREDIRS => 10,
	CURLOPT_TIMEOUT => 0,
	CURLOPT_FOLLOWLOCATION => true,
	CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
	CURLOPT_CUSTOMREQUEST => 'GET',
	CURLOPT_HTTPHEADER => array(
		'auth-token: '.$toeken,
		),
	));
	$response = curl_exec($curl);
	curl_close($curl);
	$response = json_decode($response,true)["data"];
	
	
	
	if($atts['type'] == '1'){
		return round($response['carbonIntensity']);
	}
	elseif($atts['type'] == '2'){
		return round($response['fossilFuelPercentage']);
	}
	else{
		return 'please set type';
	}

}