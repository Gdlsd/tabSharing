<?php
/*
Plugin Name: tabSharing
Plugin URI: gui.impulsion.fr
Description: Recevoir des tablatures, les valider et les poster sur votre site. IMPORTANT : ajoutez le shortcode [[tabSharing_Post_Tab]] à l'endroit où vous souhaitez voir le plugin				
Author: GuiDLS
Version: 1.1
*/
?>


<?php

//Inclure la feuille de tyle au plugin
add_action('wp_head', 'applyStyle');

function applyStyle()
{
	echo '<link rel="stylesheet" href=' . "\"". plugins_url().'/tabSharing/css/ts-style.css' . "\"" . '/>';
}

add_action('wp_head', 'applyScript');

function applyScript()
{
	echo '<script type="text/javascript" src='."\"".plugins_url().'/tabSharing/js/tab-scripts.js"></script>';
}


require_once plugin_dir_path( __FILE__ ).'includes/ts-admin-backoffice.php';
include_once plugin_dir_path( __FILE__ ).'includes/ts-admin-backoffice.php';
include_once plugin_dir_path( __FILE__ ).'includes/ts-post-form.php';
include_once plugin_dir_path( __FILE__ ).'includes/ts-database-gestion.php';

register_activation_hook( __FILE__ , 'ts_tab_database_install');
register_uninstall_hook( __FILE__ , 'ts_tab_database_delete');
?>
