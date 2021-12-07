<?php
session_start();
	try{
		$bdd = new PDO("mysql:host=127.0.0.1;dbname=pharmacie;charset=utf8","root","");
		$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(Exception $e){
		die('Erreur lors de la connexion: '.$e->getMessage());
	}
	if($_SESSION['id'] < 10){ $_SESSION['id']++; }else{ $_SESSION['id']=1; }
	$requete = $bdd->prepare("SELECT * FROM pharmacie_conseils WHERE id = ?");
	$requete->execute(array($_SESSION['id']));
	$resultat = $requete->fetch();
	echo $resultat['conseils'];
?>