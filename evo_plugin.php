<?php
/*
Plugin Name: MXit EVO
Plugin URI: http://evo.mxit.com
Description: A plugin that adds the MXit EVO Webchat widget to Wordpress.
Author: MXit Lifestyle
Version: 1.1
Author URI: http://evo.mxit.com/widget
*/

/*
Software Copyright Notice

Copyright © 2004-2009 MXit Lifestyle Development Company (Pty) Ltd.
All rights are reserved

Copyright exists in this computer program and it is protected by
copyright law and by international treaties. The unauthorised use,
reproduction or distribution of this computer program constitute
acts of copyright infringement and may result in civil and criminal
penalties. Any infringement will be prosecuted to the maximum extent
possible.

MXit Lifestyle Development Company (Pty) Ltd chooses the following
address for delivery of all legal proceedings and notices:
  Riesling House,
  Brandwacht Office Park,
  Trumali Road,
  Stellenbosch,
  7600,
  South Africa.

The following computer programs, or portions thereof, are used in
this computer program under licenses obtained from third parties.
You are obliged to familiarise yourself with the contents of those
licenses.
*/

// render the widget in the sidebar and extract its source code from the database.
function evo_renderwidget($args) {
    extract($args);
    $source = get_option('mxit_evo_source');
    if (strlen($source)>0) {
        echo $before_widget; 
        echo $before_title
             . 'MXit EVO'
             . $after_title; 

         $output = base64_decode($source);
         echo "<br/><div align=\"center\">";
         echo $output;
         echo "</div";
         echo $after_widget;
    }
}

//admin menu
function evo_plugin_admin() {
	if (function_exists('add_options_page')) {
		add_options_page('evo-plugin', 'MXit EVO', 1, basename(__FILE__), 'evo_admin_panel');
  }
}

// admin panel
function evo_admin_panel() {
	//Add options if first time running
	add_option('mxit_evo_source', '', 'MXit EVO Webchat Plugin');

	if (isset($_POST['info_update'])) {
		//update settings
		$source = stripslashes($_POST['source']);
		update_option('mxit_evo_source', base64_encode($source));
	} else {
		//load settings from database
		$source = base64_decode(get_option('mxit_evo_source'));
	}
?>

<div class="wrap">
    <form method="post" name="evoform">
        <h2>MXit EVO Webchat Options</h2>

        <script type="text/javascript">
            function select_all()
            {
            var text_val=eval("document.evoform.source");
            text_val.focus();
            text_val.select();
            }
        </script>
        <fieldset name="set1">
            <h3>Source for widget</h3>
            <p>
                <b>Note: </b>The source is automatically generated for your own custom widget.<br/>You can make your own widget at <a href="http://evo.mxit.com/widget/" target="_BLANK">The MXit Evo Widget page</a> and paste the code in here:<br/><br/>
                <label>
                    <textarea onClick="select_all();" name="source" cols="60" rows="8">
                        <?php echo $source; ?>
                    </textarea>
                </label>
            </p>
        </fieldset>
        <div class="submit">
            <input type="submit" name="info_update" value="Update Options" />
        </div>
    </form>
    <br/>Here is what your current widget looks like:<br/><br/>

<?php
    $source = get_option('mxit_evo_source');
        
    if (strlen($source)>0) {
        $output = base64_decode($source);
		echo $output;
    }
?>

</div>

<?php
}

register_sidebar_widget('MXit EVO','evo_renderwidget');
add_action('admin_menu', 'evo_plugin_admin');
?>