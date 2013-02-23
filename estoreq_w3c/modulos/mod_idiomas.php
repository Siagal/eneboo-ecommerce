<?php

/***************************************************************************
    begin                : vie sep 29 2006
    copyright            : (C) 2006 by InfoSiAL S.L.
    email                : mail@infosial.com
 ***************************************************************************/
/***************************************************************************
 *                                                                         *
 *   This program is free software; you can redistribute it and/or modify  *
 *   it under the terms of the GNU General Public License as published by  *
 *   the Free Software Foundation; either version 2 of the License, or     *
 *   (at your option) any later version.                                   *
 *                                                                         *
 ***************************************************************************/

/** @class_definition oficial_modIdiomas */
//////////////////////////////////////////////////////////////////
//// OFICIAL /////////////////////////////////////////////////////

class oficial_modIdiomas
{	
	// El estilo indica como se ven los idiomas disponibles
	var $estilo = 'flags'; 

	// Listado de idionas
	function contenidos()
	{
		global $__BD, $__LIB, $CLEAN_GET;
	
		
		$codigoMod = '';
		
		$ordenSQL = "select codidioma, nombre from idiomas where publico = true order by orden";
	
		$result = $__BD->db_query($ordenSQL);
		
		while($row = $__BD->db_fetch_array($result)) {
			$codIdioma = $row["codidioma"]; 
			
	if ($__LIB->esTrue($_SESSION["opciones"]["deeplinking"])) {
			
 			$codIdiomaDL = $codIdioma;
 			//if ($codIdiomaDL == "esp") $codIdiomaDL = '';
			
			// traducir deep link
			$dlOrigen = funSEO::getDL();
			$partesDL = explode("/", $dlOrigen);
			if ($_SESSION["idioma"] == $partesDL[0]) 
				$partesDL[0] ='';
			if (count($partesDL) > 1) {
				if ($partesDL[1] == "catalogo") {
					if (isset($partesDL[2])) {
						$fam = isset($CLEAN_GET["fam"]) ? $CLEAN_GET["fam"] : '';
						$ordenSQL = "select descripciondeeplink from familias where codfamilia ='".$fam."'";
						$familiaDL = $__BD->db_valor($ordenSQL);
						if ($familiaDL)
							$partesDL[2] = $familiaDL;
					}
					if (isset($partesDL[3])) {
						$ref = $CLEAN_GET["ref"];
						$ordenSQL = "select descripciondeeplink from articulos where referencia ='".$ref."'";
						$articuloDL = $__BD->db_valor($ordenSQL);
						if ($articuloDL)
							$partesDL[3] = $articuloDL;
					}
				}
			}
		
			$dl = implode("/", $partesDL);
		}
		
			
			$img = _DOCUMENT_ROOT.'idiomas/'.$codIdioma.'/images/flag.png';
			$imgW = _WEB_ROOT.'idiomas/'.$codIdioma.'/images/flag.png';
			
			if (file_exists($img))
				$codigoLang = '<img alt="'.$codIdioma.'" src="'.$imgW.'"/>';
			else
				$codigoLang = $row["nombre"];
			
			
			
			if ($__LIB->esTrue($_SESSION["opciones"]["deeplinking"])) 
				{			

			if ($codIdioma == $_SESSION["idioma"]) {
				if (substr($dl, 0, 1) != '/')
					$dl = substr($dl, 1);
				
				$link = _WEB_ROOT.$dl;
			}
			else
			{
			if (substr($dl, 0, 1) != '/')
				$dl = '/'.$dl;	
			$link = _WEB_ROOT.$codIdioma.$dl;
			}
				
				}
				else
				{
				// Reconstruir url
				$path_parts = pathinfo($_SERVER["SCRIPT_FILENAME"]);
				$nomPHP = $path_parts["basename"];
				if ($nomPHP == "articulos.php") $nomPHP = 'catalogo/articulos.php';
				if ($nomPHP == "articulo.php") $nomPHP = 'catalogo/articulo.php';
				if ($nomPHP == "login.php") $nomPHP = 'cuenta/login.php';
				if ($nomPHP == "favoritos.php") $nomPHP = 'cuenta/favoritos.php';
				if ($nomPHP == "crear_cuenta.php") $nomPHP = 'cuenta/crear_cuenta.php';
				if ($nomPHP == "olvide_contra.php") $nomPHP = 'cuenta/olvide_contra.php';
				if ($nomPHP == "contactar.php") $nomPHP = 'general/contactar.php';
				if ($nomPHP == "cesta.php") $nomPHP = 'general/cesta.php';
				
				$params = '?newlang='.$codIdioma;
				
				if($CLEAN_GET) {
					foreach ($CLEAN_GET as $key => $value) {
						if ($key == 'newlang')
							continue;
						$params .= '&amp;'.$key.'='.$value;
									      }
						}
			 				
			        $link = $nomPHP.$params;
			        }
	
			
			
	
			
			
	
		  if ($codIdioma == $_SESSION["idioma"])
				$codigoMod .= '<b>'.$codigoLang.'</b>';
			if ($codIdioma != $_SESSION["idioma"])
				$codigoMod .= '<a href="'.$link.'">'.$codigoLang.'</a>';
		
			//if ($codigoMod)
				$codigoMod .= '&nbsp;&nbsp;';
		}
		
		return $codigoMod;
	}
}

//// OFICIAL /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////


/** @main_class_definition oficial_modIdiomas */
class modIdiomas extends oficial_modIdiomas {};

?>
