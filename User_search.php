<?php
	if(isset($_POST['rechercher']) && isset($_POST['recherche']) && !empty($_POST['recherche'])){
		try{
			$bdd = new PDO("mysql:host=127.0.0.1;dbname=pharmacie;charset=utf8","root","");
			$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(Exception $e){
			die('Erreur lors de la connexion: '.$e->getMessage());
		}
		$requete = $bdd->query("SELECT * FROM les_pharmacies");
		$pharmaDisposantMedi = array();
		while($resultat=$requete->fetch()){
			$pharma = $resultat['pharmacies'];
			$req = "SELECT * FROM $pharma WHERE medicaments = ?";
			$requete1 = $bdd->prepare($req);
			$requete1->execute(array($_POST['recherche']));
			if($resultat1 = $requete1->fetch()){
				array_push($pharmaDisposantMedi, $resultat['pharmacies']);
			}	
		}
		if(count($pharmaDisposantMedi) == 0)
			echo "Aucune pharmacie ne dispose du medicament.";
		else
			for ($i=0; $i < count($pharmaDisposantMedi) ; $i++) { 
				echo $pharmaDisposantMedi[$i];
			}
	}
	header("Location:index.php");
?>