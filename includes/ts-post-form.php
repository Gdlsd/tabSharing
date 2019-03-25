<?php
/*
	Création shortcode
*/
add_shortcode('tabSharing_Post_Tab', 'ts_post_form');

//Includes
include'ts-post-control.php';



////////////////////////////////////////////////////////

/*
	Ajout du texte et de la zone de texte
*/

function ts_post_form()
{
	?>
	<section id="ts_post_tab">
		<fieldset>
			<legend>Poster une tablature</legend> 
			<p>Vous voulez partager vos meilleures tablatures ?
			N'hésitez pas à les envoyer via le formulaire suivant. Elles seront postées aprés avoir été soumises à des vérifications.</p>

				<form id="ts_form_post" method="post" action="" enctype="multipart/form-data" />
					<label for="title"> Titre du morceau</label>								<br />
					<input type="text" name="tab_title" id="tab_title" />						<br /><br />
					<label for="title"> Auteur du morceau</label>								<br />
					<input type="text" name="tab_author" id="tab_author" />						<br /><br />
					<label for="tab_pdf">Tablature (PDF uniquement)</label>						<br />
					<input type="file" name="tab_pdf" id="tab_pdf" value="votre fichier ici" />	<br /><br />
					<div class="g-recaptcha" data-sitekey="6LdlHWIUAAAAADq-TzWTnmcJrbpBLUkXLDqT8yHl"></div>
					<input type="submit" name="send" value="Envoyer" />
				</form>
		</fieldset>
	</section>
	<br />
	<br />

	<?php 
	if(isset($_POST['tab_title']) && isset($_POST['tab_author']))
	{
		ts_general_pdf_control($_FILES['tab_pdf'], $_POST['tab_title'], $_POST['tab_author']); 
	}

	 ts_display_tab(false);
}

