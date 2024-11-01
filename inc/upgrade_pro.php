<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
$options = get_option('track_page_scroll_options2');
$currentDoc = str_replace('wp-admin','wp-content\plugins\track-page-scroll2',getcwd());
$newDoc = str_replace('\\','/',$currentDoc); 
$err = '';
if( wp_verify_nonce($_POST['_wpnonce']) && isset($_POST['upgradeTopro'])):
$licenceKey = $_POST['licence_key_track_page_scroll'];
$API = base64_decode("aHR0cDovL3d3dy53ZWJkZXNpOS5jb20vVXBncmFkZVByb0FwaS5waHAv");
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $API);
    curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // save to returning 1
    curl_setopt($curl, CURLOPT_POSTFIELDS, "plugin=track-page-scroll&licence_key=".$licenceKey."&securitykey=eXFpeDVjdjFpdTdlMml4ZTZj");
	$result = curl_exec($curl); 
	$data = json_decode($result,true);
    curl_close ($curl);
	//print_r($data);
	if($data['valid'] == 'true' && empty($data['ERR']))
	{
		$master = base64_decode($data['filesToreplace']);
		$needToUnset = array('_wpnonce','_wp_http_referer','upgradeTopro');//no need to save in Database
		foreach($_POST as $key => $val):
			if(in_array($key, $needToUnset)):
			 unset($key); //unseting unwanted
			endif;
		//saving	
		$this->trackpageoptions2[$key] = $val;
		endforeach;
		$saveSettings = update_option('track_page_scroll_options2', $this->trackpageoptions2 );	
		?>
        <script>
	       window.open('<?php echo $master ?>');
        </script>
        <?php	
		echo $this->redirectme('admin.php?page=trackpage_upgrade_pro&saved=1');	        
	}
	else
	{
	 $err = $data['ERR'];
	}
endif;
?>
<div class="wrap">
<h2><?php _e("Upgrade to Pro",track_page)?></h2>
<?php if($options['isPro'] == 'yes'):?>
<div class="updated settings-error notice" id="setting-error-settings_updated"> 
<p><?php _e("<strong>Congratulations! </strong> You are using a Pro Version.",track_page)?></p></div>
<?php endif;
 if(!empty($err)):?>
<div class="error settings-error notice" id="setting-error-settings_updated"> 
<p><?php echo $err; ?></p></div>
<?php endif;?>
<form id="trackpagepro" name="trackpagepro" method="post" action="">
<?php wp_nonce_field();  ?>
<input type="hidden" name="isPro" value="<?php echo ($options['isPro'] == 'no') ? 'yes' : ''  ?>"/>
<table cellpadding="20px">
<tr><th><?php _e('Enter Your <a href="http://www.webdesi9.com/pro-plugin/track-page-scroll/" title="You can get from here" target="_blank">Licence Key</a> Here * :',track_page)?> </th><td><input type="text" value="<?php echo $options['licence_key_track_page_scroll']?>" id="licence_key_track_page_scroll" name="licence_key_track_page_scroll" class="regular-text" required="required"></td><td><input type="submit" name="upgradeTopro" value="Upgrade To Pro" class="button button-primary"></td></tr>
</table>
</form>
<p class="creditme"><strong><?php _e("Developed By:",track_page)?></strong><a href="http://mysenseinc.com/" target="_blank"> <?php _e("Mysense Inc.",track_page)?></a></p>
</div>