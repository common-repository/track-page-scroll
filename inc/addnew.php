<?php 
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb;
$curPageUrl = 'admin.php?page=addnewtrackpage';
if(isset($_POST['savetrack'])):
if(wp_verify_nonce( $_POST['trackpage_nonce_field'], 'trackpage_action' ))
{
	_e("Saving Please wait...",track_page);
	$saveData = $wpdb->query( $wpdb->prepare( 
	"
		INSERT INTO ".$this->trackpage_tbl."
		( acc_num, t_site_url, track_class )
		VALUES ( %s, %s, %s )
	", 
        array(
		sanitize_text_field($_POST['gtmnumber']), 
		sanitize_text_field($_POST['purl']), 
		sanitize_text_field($_POST['tclassid'])
	) 
) );
	if($saveData)
	{
		$lastid = $wpdb->insert_id;
		echo $this->redirectme($curPageUrl.'&msg=1&gsid='.$lastid);
	}
	else
	{
		echo $this->redirectme($curPageUrl.'&msg=2');
	}
	exit;
}
else
{
	//echo 'Sorry, your nonce did not verify';
	_e("Sorry, your nonce did not verify",track_page);
}
 endif; 
$msg = !empty($_GET['msg']) ? $_GET['msg'] : '';
 ?>
 
<div class="wrap">
<h2><?php _e("Add New Track",track_page)?></h2>
<?php if($msg == 1):?>
<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e("Settings Saved",track_page)?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.",track_page)?> </span></button></div>
<?php elseif($msg == 2):?>
<div class="error settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e("Settings Not Saved.",track_page)?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.",track_page)?>.</span></button></div>
<?php endif; ?>
<form action="" method="post" id="trackform" name="trackform">
<table class="form-table">
<tbody>
<?php if(!empty($_GET['gsid'])):?>
<tr>
<th scope="row"><label for="generatedshortcode"><?php _e("Generated Shortcode",track_page)?></label></th>
<td><code>[trackpage id=<?php echo $_GET['gsid']?>]</code>
<p id="generatedshortcode-description" class="description"><strong><?php _e("Copy This Shortcode and Put this in your page for tracking purpose.",track_page)?></strong> <a href="admin.php?page=trackpage" class="button button-primary">&larr; Back</a></p></td>
</tr>
<?php else :
  wp_nonce_field( 'trackpage_action', 'trackpage_nonce_field' ); 
?>
<tr>
<th scope="row"><label for="gtmnumber"><?php _e("GTM UA Number *",track_page)?></label></th>
<td><input type="text" class="regular-text" value="" id="gtmnumber" name="gtmnumber" maxlength="12">
<span id="gtmnumberE" style="display:none" class="error"></span>
<p id="gtmnumber-description" class="description"><?php _e("Add UA Number as like",track_page)?> <strong><?php _e("UA-123456-78",track_page)?></strong></p>
</td>
</tr>
<tr>
<th scope="row"><label for="purl"><?php _e("URL",track_page)?></label></th>
<td><input type="text" class="regular-text" value="" id="purl" name="purl">
<p id="purl-description" class="description"><?php _e("The Page URL where to add this shortocde.",track_page)?>( <strong><?php _e("Leave Empty, It will auto get page URL",track_page)?></strong> )</p></td>
</tr>
<tr>
<th scope="row"><label for="tclassid"><?php _e("Track Page Class / Id *",track_page)?></label></th>
<td><input type="text" class="regular-text" value="" id="tclassid" name="tclassid">
<span id="tclassidE" style="display:none" class="error"></span>
<p id="tclassid-description" class="description"><?php _e("Add Page main div ",track_page)?><strong><?php _e("Class or ID",track_page)?></strong>. <?php _e("It will track whole page events. e.g",track_page)?> <strong><?php _e(".entry-content or #entry-content",track_page)?></strong></p></td>
</tr>
<tr>
<th scope="row"></th>
<td><p class="submit"><input type="submit" value="Save" class="button button-primary" id="savetrack" name="savetrack"></p></td>
</tr>
<?php endif;?>
</tbody>
</table>
</form>
</div>
<script type="text/javascript">
  jQuery('#trackform').submit(function(e) {
	  <?php $ids = array('gtmnumber' => 'Please Enter GTM UA Number','tclassid' => 'Please Enter Class or Id');
	  foreach($ids as $cid => $val):
	  ?>
	  if(jQuery('#<?php echo $cid?>').val() == '')
	  {
		  jQuery('#<?php echo $cid?>E').show().html('&larr; <?php echo $val?>');
		  e.preventDefault();
	  }
	  else
	  {
		  jQuery('#<?php echo $cid?>E').hide().text('');
	  }
	  <?php endforeach; ?>
  });
</script>