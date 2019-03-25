<?php 

/*
	Placer la tablature postée dans la base de donnée avant validation d'un modérateur
*/
function ts_send_database($author, $title, $pdfname)
{
	global $wpdb;

	try{
			$wpdb->insert("{$wpdb->prefix}tabSharing_tablatures",
					array('auteur' => $author,
					'morceau' => $title,
					'adresse_pdf' => '.\wp-content\plugins\tabSharing\pdf\pdf-checking\\'. $pdfname,
					'validation' => false
				));
	}
	catch(Exception $e)
	{
		echo 'Probleme dans la fonction ts_send_database()';
	}

}

/*
	Création de la base de donnée à l'installation du plugin
*/
function ts_tab_database_install()
{
	global $wpdb;

	//Creation de la table si elle n'existe pas
	$wpdb->query("CREATE TABLE IF NOT EXISTS {$wpdb->prefix}tabSharing_tablatures 
			(
				id INT AUTO_INCREMENT PRIMARY KEY, 
				auteur VARCHAR(255) NOT NULL,
				morceau VARCHAR(255) NOT NULL,
				complement VARCHAR(255),
				adresse_pdf VARCHAR(255) NOT NULL,
				validation BOOLEAN
			);"
		);
}


/*
	Destruction de la base de donnée à la désinstallation du plugin
*/

function ts_tab_database_delete()
{
	global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}tabSharing_tablatures;");
}

function ts_recup_database_data($isValidate){
	if($isValidate){
		global $wpdb;

		$tab_to_validate = $wpdb->get_results (
			"SELECT * 
			FROM {$wpdb->prefix}tabSharing_tablatures
			WHERE validation = 1
			ORDER BY auteur"
			);
		return $tab_to_validate;
	}else{
		global $wpdb;

		$tab_to_validate = $wpdb->get_results (
			"SELECT * 
			FROM {$wpdb->prefix}tabSharing_tablatures
			WHERE validation = 0
			ORDER BY id"
			);
		return $tab_to_validate;
	}
}



/*
	Affichage tablatures sur le site
*/
function ts_display_tab($toDashboard)
{
	$tab_to_validate = ts_recup_database_data(true);

	if($toDashboard)
	{
		foreach ($tab_to_validate as $tabSharing_tablatures)
		{
		echo $tabSharing_tablatures->auteur . 
		" - " . $tabSharing_tablatures->morceau . 
		" -  <a href=\"" . "../" . $tabSharing_tablatures->adresse_pdf . "\" target=\"_blank\">" . "Voir pdf" . "</a></br>";



		echo 
		'<form method="post" action="">
			<input type="submit" value="Supprimer" name="del_tab_'. $tabSharing_tablatures->id .'" id="del_tab_'. $tabSharing_tablatures->id .'">
		</form></br></br>';
			del_tab($tabSharing_tablatures->id);
		}
	}
	else
	{
		foreach ($tab_to_validate as $tabSharing_tablatures)
		{
		echo  '<div class="displayed_tab">'. '<div id="author">'. $tabSharing_tablatures->auteur . '</div>' .
		" - " . '<div id="title">' . $tabSharing_tablatures->morceau . '</div>' .
		" - " . '<div id="pdf_link">' . "<a href=\"" . "../" . $tabSharing_tablatures->adresse_pdf . "\" target=\"_blank\">" . "Voir pdf" . "</a></br></div></div>";
		}
	}
}



/*
	Affichage des tablatures sur le back office
*/
function ts_display_backoffice_tab()
{

	$tab_to_validate = ts_recup_database_data(false);
		
	foreach ($tab_to_validate as $tabSharing_tablatures)
	{

		echo $tabSharing_tablatures->id . 
		" - " . $tabSharing_tablatures->auteur . 
		" - " . $tabSharing_tablatures->morceau . 
		" -  <a href=\"" . " ../" . $tabSharing_tablatures->adresse_pdf . "\" target=\"_blank\">" . "Voir pdf" . "</a></br>";
		echo 
		'<form method="post" action="">
			<input type="submit" value="Valider" name="add_tab_'. $tabSharing_tablatures->id .'" id="add_tab_'. $tabSharing_tablatures->id .'">
			<input type="submit" value="Supprimer" name="del_tab_'. $tabSharing_tablatures->id .'" id="del_tab_'. $tabSharing_tablatures->id .'">
		</form></br></br>';

		if(isset($_POST['add_tab_'. $tabSharing_tablatures->id]))
		{
			?>
			<script>
				window.location.reload();
			</script>
			<?php
			global $wpdb;

			$wpdb->query(
					"UPDATE {$wpdb->prefix}tabSharing_tablatures
					SET validation = 1
					WHERE id= $tabSharing_tablatures->id"
				);
			refresh();
		}

		if(isset($_POST['del_tab_'. $tabSharing_tablatures->id]))
		{

			global $wpdb;
			//Récupération de l'adresse du fichier
			$file = $wpdb->query(
						"SELECT adresse_pdf
						FROM {$wpdb->prefix}tabSharing_tablatures
						WHERE id= $tabSharing_tablatures->id"
					);

			$complete_path = realpath($file);

			if(file_exists($complete_path))
			{
				unlink($complete_path);
			}

			$wpdb->query(
					"DELETE FROM {$wpdb->prefix}tabSharing_tablatures
					WHERE id= $tabSharing_tablatures->id"
				);		
			refresh();
		}
	}
}
/*
	Supprimer une tablature
*/
function del_tab($tabnumber){
	if(isset($_POST['del_tab_'. $tabnumber]))
		{
			global $wpdb;
			//Récupération de l'adresse du fichier
			$file = $wpdb->query(
						"SELECT adresse_pdf
						FROM {$wpdb->prefix}tabSharing_tablatures
						WHERE id= $tabnumber"
					);
			
			
			$complete_path = realpath($file);

			if(file_exists($complete_path))
			{
				unlink($complete_path);
			}

			$wpdb->query(
					"DELETE FROM {$wpdb->prefix}tabSharing_tablatures
					WHERE id= $tabnumber"
				);
			refresh();
		}
}

function refresh(){
	echo
	'<script>
	window.location.reload();
	</script>';
			
}