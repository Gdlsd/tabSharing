<?php


add_action('admin_menu', 'tabSharing_dashboard');
applyStyle();

function tabSharing_dashboard()
{
	add_menu_page('ts_dashboard', 'tabSharing', 'manage_options', 'ts', 'display_backoffice', plugins_url() . '/tabSharing/img/vinyl_icon.png');	
}


function display_backoffice()
{
	?>

	<h1> tabSharing - Tableau de bord</h1>
		<p> C'est ici que vous pourrez faire le tri dans les tablatures à afficher.</p>

	<section id="ts_lists">
			
			<div  class="ts_tab_column">
				<h2>Tablatures en cours de vérification </h2>
			
<?php

ts_display_backoffice_tab();
?>
			</div>
			<div class="ts_tab_column">
				<h2>Tablatures en ligne</h2>
			</br>


<?php

ts_display_tab(true);

?>
			</div>
	</section>
	<?php
}

