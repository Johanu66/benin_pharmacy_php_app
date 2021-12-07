<?php
session_start();
$_SESSION['id'] = 0;
?>
<!DOCTYPE html>
<html>
<head>
	<title>FAST MEDECINE FINDER</title>
	<meta charset="utf-8">
	<script type="text/javascript" src="Jquery.js"></script>
	<link rel="stylesheet" type="text/css" href="Accueil.css">
</head>
<body>
	<?php 	include("Entete.php");	?>
	<section>
		<aside id="diaporama"><h2 id="conseil">

			<?php
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

			</h2></aside>
		<aside>
			<form method="POST" action="">
				<div>
					<h2>Veuillez taper le nom du médicament que vous cherchez pour trouver les pharmacies les plus proches dans lesquelles il est disponible.</h2>
				</div>
				<div>
					<label for="recherche">Nom du médicament:</label><br><br>
					<table>
						<tr>
							<td>
								<input type="search" name="recherche" id="recherche" placeholder="Recherche...">
							</td>
							<td>
								<input type="submit" name="rechercher" value="Rechercher">
							</td>
						</tr>
					</table>
				</div>
				<div>
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
							echo "<h1 style='color:red'>Aucune pharmacie ne dispose le medicament que vous cherchez.</h1>";
						else
							for ($i=0; $i < count($pharmaDisposantMedi) ; $i++){ 
								echo '<h1>'.$pharmaDisposantMedi[$i].'</h1>';
							}
						}
						?>
				</div>
			</form>
		</aside>
	</section>
	<script type="text/javascript">
		var timer=2000;
		setInterval("changerconseil()",timer);
		function changerconseil(){
			$('#conseil').load('Conseil.php');
		}
	</script>
</body>
</html>