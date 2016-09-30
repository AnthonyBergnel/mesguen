<?php
session_start();
if($_SESSION['emplCat']=="Exploitant" OR $_SESSION['emplCat']=="Chauffeur")
{
	?>
	<!DOCTYPE html>
	<html>
		<head>
			<title>PPE</title>
			<link rel='stylesheet' type='text/css' href='style/styleacceuil.css'/>
		</head>
		<body>
			<div class='content'>
				<div class='titre'>
					<p>Bonjour <?php echo $_SESSION['emplNom']." ".$_SESSION['emplPrenom']; ?></p>
				</div>
				<h1>Vos pages</h1>
				<?php
				if($_SESSION['emplCat']=='Exploitant')
				{
					?>
					<a href="AC11.php" id='orgat'>Organiser les tourn&eacutees</a>
					<a href='AC11.php'><div class='lienimg'></div></a><img src='images/tournee.png' class='tournee'/>
					<?php
				}
				else
				{
					?>
					<a href="" id='orgat'>Liste des tourn&eacutees</a>
					<?php
				}
				?>
				<a href="deco.php" id='deconnexion'>D&eacuteconnexion</a>
			</div>
		</body>
</html>
<?php
}
else
{
	header("location:index.php");
}
?>
