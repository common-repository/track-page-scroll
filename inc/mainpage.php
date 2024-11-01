<?php
if ( ! defined( 'ABSPATH' ) ) exit;
global $wpdb; 
$alltrackEvents = $wpdb->get_results('select * from '.$this->trackpage_tbl.' order by tid DESC');
$action = !empty($_GET['action']) ? $_GET['action'] : '';
$msg = !empty($_GET['msg']) ? $_GET['msg'] : '';
$curPageUrl = 'admin.php?page=trackpage';
$options = get_option('track_page_scroll_options2');
?>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#trackpage').DataTable();
});
</script>
<div class="wrap">
<h2><?php _e("All Track Shortcodes",track_page)?><a class="add-new-h2" href="admin.php?page=addnewtrackpage"><?php _e("Add New",track_page)?></a></h2>
<?php if($options['isPro'] == 'no'):?>
<div class="error settings-error notice" id="error settings-error notice is-dismissible"> 
<p><?php _e('<strong>Alert! </strong> You are using a Free Version of <strong>Track Page Scroll</strong> with less features. To Upgrade to <strong>Pro Version</strong> Click Here. <a href="admin.php?page=trackpage_upgrade_pro"><button class="button button-primary">Upgrade To Pro</button></a>',track_page)?></p></div>
<?php endif;?>
<?php if($options['isPro'] == 'yes'):?>
<div class="updated settings-error notice" id="setting-error-settings_updated"> 
<p><?php _e("<strong>Congratulations! </strong> You are using a Pro Version.",track_page)?></p></div>
<?php endif;?>
<?php
/* Update Action */ 
if($action == 'update'):
$tid = $_GET['uid'];
if(isset($_POST['updatetrack'])):
_e("Updating Please wait...",track_page);
	$saveData = $wpdb->update(
	                         $this->trackpage_tbl, 
							 array(
									 'acc_num' =>  sanitize_text_field($_POST['gtmnumber']), 
									 't_site_url' =>  sanitize_text_field($_POST['purl']),
									 'track_class' =>  sanitize_text_field($_POST['tclassid'])
							 ),
							 array('tid' => $tid), 
							 array( 
								'%s',
								'%s',
								'%s'
							 ), 
							 array( '%d' ) 
							 );
	if($saveData):
	echo $this->redirectme('admin.php?page=trackpage&msg=4');
	die;
	else:
	echo $this->redirectme('admin.php?page=trackpage&msg=5');
	die;
	endif;

endif; 
$formaction = 'admin.php?page=trackpage&action=update&uid='.$tid.''; 
$oldFormData = $wpdb->get_row("select * from ".$this->trackpage_tbl." where tid= '".$tid."'");
if(count($oldFormData) == 0):
echo _e("Error: Record Not Exists. Redirecting please wait....",track_page);
echo $this->redirectme($curPageUrl.'&msg=3');
die;
endif;
?>
<form action="<?php echo $formaction; ?>" method="post" id="trackform" name="trackform">
<table class="form-table">
<tbody>
<tr>
<th scope="row"><label for="gtmnumber"><?php _e('GTM UA Number *',track_page)?></label></th>
<td><input type="text" class="regular-text" value="<?php echo $oldFormData->acc_num; ?>" id="gtmnumber" name="gtmnumber" maxlength="12">
<span id="gtmnumberE" style="display:none" class="error"></span>
<p id="gtmnumber-description" class="description"><?php _e("Add UA Number as like",track_page)?><strong><?php _e("UA-123456-78",track_page)?></strong></p>
</td>
</tr>
<tr>
<th scope="row"><label for="purl"><?php _e("URL",track_page)?></label></th>
<td><input type="text" class="regular-text" value="<?php echo $oldFormData->t_site_url; ?>" id="purl" name="purl">
<p id="purl-description" class="description"><?php _e("The Page URL where to add this shortocde.",track_page)?>( <strong><?php _e("Leave Empty, It will auto get page URL",track_page)?></strong> )</p></td>
</tr>
<tr>
<th scope="row"><label for="tclassid"><?php _e("Track Page Class / Id *",track_page)?></label></th>
<td><input type="text" class="regular-text" value="<?php echo $oldFormData->track_class; ?>" id="tclassid" name="tclassid">
<span id="tclassidE" style="display:none" class="error"></span>
<p id="tclassid-description" class="description"><?php _e("Add Page main div",track_page)?><strong><?php _e("Class or ID",track_page)?></strong>. <?php _e("It will track whole page events. e.g",track_page)?><strong>.entry-content or #entry-content</strong></p></td>
</tr>
<tr>
<th scope="row"></th>
<td><p class="submit"><input type="submit" value="Update" class="button button-primary" id="updatetrack" name="updatetrack"></p></td>
</tr>
</tbody>
</table>
</form>

<?php else:
/* Delete Action */
if($action == 'delete'):
$did = $_GET['did'];
echo '<span style="color: red;">';_e("Deleting Please Wait...",track_page); echo'</span>';
if(!empty($did))
{
	$deleting = $wpdb->delete($this->trackpage_tbl,array('tid' => $did), array( '%d' ));
	if($deleting)
	{
		echo $this->redirectme($curPageUrl.'&msg=1');
	}
	else
	{
		echo $this->redirectme($curPageUrl.'&msg=2');
	}
}
else
{
	echo $this->redirectme($curPageUrl.'&msg=2');
}
endif;?>
<?php if($msg == 1):?>
<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e("Record Deleted Successfully.",track_page)?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.",track_page)?></span></button></div>
<?php elseif($msg == 2):?>
<div class="error settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e("Error: Record Not Deleted.",track_page)?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.",track_page)?></span></button></div>
<?php elseif($msg == 3):?>
<div class="error settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e("Error: Record Not Exists.",track_page)?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.",track_page)?></span></button></div>
<?php elseif($msg == 4):?>
<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e("Record Updated Successfully.",track_page)?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.",track_page)?></span></button></div>
<?php elseif($msg == 5):?>
<div class="error settings-error notice is-dismissible" id="setting-error-settings_updated"> 
<p><strong><?php _e("Error: Record Not Updated.",track_page)?></strong></p><button class="notice-dismiss" type="button"><span class="screen-reader-text"><?php _e("Dismiss this notice.",track_page)?></span></button></div>
<?php endif; ?>
<table id="trackpage" class="table table-striped table-bordered" cellspacing="0" width="100%">
        <thead>
            <tr>
                <th><?php _e("Sr.",track_page)?></th>
                <th><?php _e("GTM UA Number",track_page)?></th>
                <th><?php _e("Page Link",track_page)?></th>
                <th><?php _e("Track Page Class / Id",track_page)?></th>
                <th><?php _e("Shortcode",track_page)?></th>
                <th></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th><?php _e("Sr.",track_page)?></th>
                <th><?php _e("GTM UA Number",track_page)?></th>
                <th><?php _e("Page Link",track_page)?></th>
                <th><?php _e("Track Page Class / Id",track_page)?></th>
                <th><?php _e("Shortcode",track_page)?></th>
                <th></th>
            </tr>
        </tfoot>
        <tbody>
        <?php if(count($alltrackEvents) != 0 && !empty($alltrackEvents) && is_array($alltrackEvents)):
		$count = 1;
		foreach($alltrackEvents as $alltrackEvent):?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $alltrackEvent->acc_num; ?></td>
                <td>
                <?php if ((strpos($alltrackEvent->t_site_url, 'http://') !== false) || strpos($alltrackEvent->t_site_url, 'https://') !== false) { ?>
                <a href="<?php echo $alltrackEvent->t_site_url; ?>" target="_blank"><?php echo $alltrackEvent->t_site_url; ?></a>
                <?php } else {
                echo $alltrackEvent->t_site_url;
                 }?>
                </td>
                <td><?php echo $alltrackEvent->track_class; ?></td>
                <td><code><?php echo '[trackpage id='.$alltrackEvent->tid.']'; ?></code></td>
                <td><a href="admin.php?page=trackpage&action=update&uid=<?php echo $alltrackEvent->tid; ?>" title="<?php _e("Edit",track_page)?>" ><img src="<?php echo plugins_url( 'img/edit.png', __FILE__ ); ?>" width="20px"/></a> |
                
                <a href="admin.php?page=trackpage&action=delete&did=<?php echo $alltrackEvent->tid; ?>" title="<?php _e("Delete Shortcode",track_page)?>" onclick="return confirm('<?php _e("Do You Really Want To Delete?",track_page)?>')"><img src="<?php echo plugins_url( 'img/del.png', __FILE__ ); ?>" width="20px"/></a></td>
            </tr>
       <?php $count++; endforeach; endif;?>     
        </tbody>
    </table>
<strong style="color:#F00"><?php _e("Note:",track_page)?></strong> <?php _e("Use Shortcode in Php Files as like ",track_page)?><code><?php echo htmlspecialchars("<?php echo do_shortcode('[trackpage id= YOUR GENERATED ID]')?>");?></code>
 <?php endif;?>   
<p class="creditme"><strong><?php _e("Developed By:",track_page)?></strong><a href="http://mysenseinc.com/" target="_blank"> <?php _e("Mysense Inc.",track_page)?></a></p>
</div>