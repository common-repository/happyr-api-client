<?php

/**
 * Register an element in the admin menu
 */
function happyr_menu() {
	$page=array();
	
  	//parent, title, link, rights, url, function
  	$page[]=add_submenu_page('options-general.php',"HappyR API", "HappyR API",'manage_options','happyr-api', 'happyr_admin');
  	
  	foreach($page as $p)
  		add_action('admin_print_styles-' . $p, 'happyr_admin_styles');
}

add_action('admin_menu', 'happyr_menu');
add_action('admin_init', 'happyr_admin_init');

/**
 * Init the admin page. Register styles and stuff
 */
function happyr_admin_init(){
	wp_register_style('happyrStyleAdmin', WP_PLUGIN_URL . '/happyr-api-client/assets/admin.css');
}

/**
 * Enqueue the styles
 */
function happyr_admin_styles(){
	wp_enqueue_style('happyrStyleAdmin');
}

/**
 * This is the admin page
 */
function happyr_admin(){

	?><div id='happyr_admin'><h1>HappyR API client</h1><?php 
	

	if(isset($_POST['do'])){
		if($_POST['do']=='updateSettings'){
		if (! wp_verify_nonce($_POST['nonce'], 'ejamju03fxfg') ) die('Security check failed'); 
		
		$username=$_POST['username'];
		$token=$_POST['token'];
		$endpoint=$_POST['endpoint'];
		$version=$_POST['version'];
		
		//make sure there is a slash at the end
		$endpoint=rtrim($endpoint,'/');
		$endpoint.='/';
		
		//remove any 'v' in the beginning and any '.' at the end of the version
		$version=ltrim($version,'v');
		$version=rtrim($version,'.');
				
		update_option('happyr-api-username',$username);
		update_option('happyr-api-token',$token);
		update_option('happyr-api-endpoint',$endpoint);
		update_option('happyr-api-version',$version);
			
			
		//print message
		?>
		<div class="updated">
			<p>Settings updated!</p>
		</div>
		<?php 
		}
		elseif($_POST['do']=='installVendors'){
			//install the vendors
			exec('export COMPOSER_HOME=/tmp && cd "'.__DIR__.'" && php composer.phar update',$result, $output);
			$message=$output==0?'Vendors successfully installed.':'Vendors could not be installed.';
			
  			//if error
  			if($output==1){
				//try to figure out why.. 
				if(!is_writable()){
					$message='The directory "'.__DIR__.'/vendor" is not writable by the webserver. Please set the correct permissions on that directory.';
				}
				else{
					$message.='<br>Error code: '.$output;
				}
			}
		
			?>
			<div class="updated">
				<p><?php echo $message; ?></p>
			</div>
			<?php 
		}
	}
	
	if(!isset($username)){//if we dont got any content in the varialbes
		$username=get_option('happyr-api-username');
		$token=get_option('happyr-api-token');
		$endpoint=get_option('happyr-api-endpoint');
		$version=get_option('happyr-api-version');
	}
	
	/*
	 * write html
	 */	
	
	?>
	
	<form action="" method="POST" id="happyr_form">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("ejamju03fxfg"); ?>" />
		<input type="hidden" name="do" value="updateSettings" />
		<table>
			<tr>
				<td>Username:</td>
				<td><input type="text" name="username" value="<?php echo stripslashes($username);?>" /></td>
			</tr>
			<tr>
				<td>Api Token:</td>
				<td><input type="text" name="token" value="<?php echo stripslashes($token);?>" /></td>
			</tr>
			<tr>
				<td>Endpoint:</td>
				<td><input type="text" name="endpoint" value="<?php echo stripslashes($endpoint);?>" /> <small>(http://happyrecruiting.se/api/)</small></td>
			</tr>
			<tr>
				<td>Version:</td>
				<td><input type="text" name="version" value="<?php echo stripslashes($version);?>" /> <small>(Leave blank for latest version)</small></td>
			</tr>
			
		</table>
		
		<input type="submit" value="Save" />
	</form>
	
	<div class="box">
		<h3>Documentation</h3>
		<p>To use the library you need to install the vendors. This action may take several minutes.</p>
		<form action="" method="POST" id="happyr_form_vendors">
		<input type="hidden" name="nonce" value="<?php echo wp_create_nonce("ejamju03fxfg"); ?>" />
		<input type="hidden" name="do" value="installVendors" />
		
		<input type="submit" value="Install vendors" />
	</form>
		<p>To get an instance of the API class: call the <i>happyr_getApi()</i>.</p>
		<p>The completedocumentation of the api will soon be hosted 
		at <a href="http://developer.happyrecruiting.se">http://developer.happyrecruiting.se</a>.
		</p>
	</div>
	
	<div class="box">
		<h3>How to get API credentials?</h3>
		<p>To get API credentails contact the folkes 
		at <a href="http://happyrecruiting.se">http://happyrecruiting.se</a>.</p>
	</div>
	
	<div class="clear"></div>
	</div> <!-- End: happyr_admin -->
	
<?php 

	
}

