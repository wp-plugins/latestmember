<?php
/*
Plugin Name: latestmember 
Plugin URI: http://wordpress.org/extend/plugins/latestmember/
Description: نمایش آخرین کاربران عضو شده با استفاده از تکنولوژی جی کوئری 
Version: 1.0
Author: مهدی خاکسار
Author URI: http://www.progpars.com
License: progpars.com
*/
function latestmember_active()
{
	add_option("latestmember", 10);	
}
register_activation_hook(__FILE__,'latestmember_active');

function latestmember_deactive()
{
	delete_option('latestmember');	
}
register_deactivation_hook(__FILE__,'latestmember_deactive');

function latestmember()
{ 	
	$total = get_option('latestmember');
	$latestmember_users = get_users('orderby=registered&order=DESC&number='.$total.'');
        foreach($latestmember_users as $user) {
		$get_date_registered =  $user->user_registered;
		$jdate_registdate = jdate(" d / m / Y",strtotime($get_date_registered));
		$email = $user->user_email;
		$get_usermail = get_user_by('email',$email);
		$user_largimg = get_avatar($user->user_email, 150 );	
		$user_uselargimg = "<img style='float: left; margin-right: 20px;'$user_largimg ";
		echo 
		'
		<div class="members"> 
		<a class="tip_trigger"> 
		 '.get_avatar($user->user_email , 50 ).' 
		<span style="display: none; top: -7px; left: 719px;" class="tip"> 
		'.$user_uselargimg.'
		 <strong>شناسه : </strong>'.$user->user_login.'<br />
		  
		  <strong>نام کامل : </strong>'.$user->display_name.'<br />
		  
		  <strong>تاریخ عضویت : </strong>'.$jdate_registdate.'<br />
		  
		  <strong>درباره من : </strong>'.$get_usermail->user_description.'<br />
		</span></a> 
		</div>
		';	
    }					
}
add_shortcode('latestmember','latestmember');

function admin_options_latestmember()
{
	$value_totaluser = $_POST['usertotal'];
	if(isset($_POST['submit']))
	{
		update_option('latestmember',$value_totaluser);
		
		?>
        <div id="setting-error-settings_updated" class="updated settings-error"><p>تغییر ذخیره شد</strong></p></div>
        <?php
	}
	?>
<div class="wrap">
  <?php screen_icon();?>
  <h2>تنظیم تعداد نمایش</h2>
  <p>نمایش آخرین کاربران عضو شده در سایت</p>
  <form action="" method="post">
  <label>تعداد نمایش </label>
      <input type="text" value="<?php echo get_option('latestmember',10);?>" class="small-text" name="usertotal" />
      <label>مورد</label>
      <p class="submit"><input type="submit" value="ذخیره" class="button-primary" name="submit"/></p>
          
  </form>
</div>
<?php
}
add_action('admin_menu','admin_menu_latestmember');

function latestmemberJs()
{
	wp_enqueue_script('jquery');
	wp_register_script('latestmemberJs',plugins_url('/latestmember-js.js', __FILE__));
	wp_enqueue_script('latestmemberJs');
}
add_action('wp_enqueue_scripts','latestmemberJs');

function latestmemberStyle()
{
	echo '<link rel="stylesheet" href="'.plugins_url('latestmember-style.css',__FILE__).'" type="text/css" media="all" />';
}
add_action('wp_head','latestmemberStyle');

function admin_menu_latestmember()
{
	add_options_page('تنظیمات آخرین کاربران عضو شده','آخرین کاربران عضو شده','manage_options','latestmember','admin_options_latestmember');
}
?>