<?php

//Variables globales



function ts_general_pdf_control($pdf_file, $tab_title, $tab_author)
{

	if (!empty($_POST['tab_title']) 
		AND !empty($_POST['tab_author']) 
		AND ($pdf_file['size'] != 0))
	{		
			global $checking_file;
			$checking_file = true;

		echo ts_size_control($pdf_file['size']) . '</br>'; 
		echo ts_extension_control($pdf_file['name']) . '</br>';
		echo ts_special_char_control($tab_author, $tab_title) . '</br>';

		if($checking_file)
		{	
			if(ts_archiving_pdf_toChecking($pdf_file))
			{
				ts_send_database($tab_author, $tab_title, $pdf_file['name']);
				echo 'La tablature que vous venez d\'envoyer sera soumise à validation par nos modérateurs. Merci beaucoup pour cet ajout ! </br></br>';
			}
			else
			{
				echo 'Echec du transfert';
			}
		}
		else
		{
			echo 'La tablature n\'a pas pu être envoyée. Vérifiez les informations que vous nous avez transmis.';
		}
	}
	else
	{
		echo 'Erreur, vérifiez si tous les champs sont rempli ainsi que la taille du pdf envoyé '. $pdf_file['size'];
	}
}


/*
	Rangement du fichier dans le dossier "Checking"
*/
function ts_archiving_pdf_toChecking($pdf_file)
{
	$name_file = htmlspecialchars(str_replace(" ", "_", $pdf_file['name'])); //remplacer espaces par underscore
	$tmp_name= $pdf_file['tmp_name'];
	$local_folder = plugin_dir_path(__FILE__) . "..\pdf\pdf-checking\\";
	
	return move_uploaded_file($tmp_name, $local_folder . $name_file);
}


/*
	Contrôle et affichage des vérifications
*/
function ts_special_char_control($tabauthor, $tabtitle) // A corriger : fonctionne mal
{
	$regex = "#^[A-Za-z0-9]#";
	if(preg_match($regex, $tabauthor) AND preg_match($regex, $tabtitle))
	{
		return 'Vérification caractères spéciaux ✔';
	}
	else
	{
		$GLOBALS["checking_file"] = false;
		return 'Vérification caractères spéciaux ✘';
	}
}

function ts_extension_control($pdfname)
{
	if(preg_match('#.pdf$#', $pdfname))
	{
		return 'Fichier PDF ✔';
	}
	else
	{
		$GLOBALS["checking_file"] = false;
		return 'Fichier PDF ✘';
	}
}

function ts_size_control($pdfsize)
{
	if($pdfsize <= 2097152)
	{
		return 'Taille ✔';
	}
	else
	{
		$GLOBALS["checking_file"] = false;
		return 'Taille ✘';
	}		
}