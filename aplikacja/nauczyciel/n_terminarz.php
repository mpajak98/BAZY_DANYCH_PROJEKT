<?php
session_start();
?>


<!DOCTYPE HTML>

<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Zarządzanie kontem</title>
	<meta name="description" content="opis w google"/>
	<meta name="keywords" content="słowa po których google szuka"/>

    <link rel="stylesheet" href="../Styles/styleApp.css" type="text/css" />
	
	<link rel="stylesheet" type="text/css" href="../Styles/tooltip.css">
	<link rel="Shortcut icon" href="favicon.ico" />

	<meta http-equiv="X-UA_Compatible" content="IE=edge,chrome=1" />
	<meta name="author" content="Kowalski, Mielniczek, Pająk" />

</head>


<body>
		
		<?php 
	unset($_SESSION['blad']);
	
	require_once "../connect.php";
	
	$conn=@new mysqli($IP, $username, $password, $DB_name); 
	
	if ($conn->connect_errno!=0)
	{
		echo "Error: ".$conn->connect_errno;
	}
	else
	{

		$login=$_SESSION['uzytkownik_login'];
		
		$haslo = $_SESSION['haslo'];
		
		
		$sql="SELECT * FROM uzytkownik WHERE uzytkownik_login='$login' AND haslo='$haslo'";
		$result = @$conn->query($sql);
	
		$sql2="SELECT * FROM nauczyciel WHERE uzytkownik_login='$login' ";
		$result2 = @$conn->query($sql2);
		
		$dane_uzytkowanika=@mysqli_fetch_assoc($result);
		$dane_nauczyciela=@mysqli_fetch_assoc($result2);
		
		$result3 = $conn->query("CALL terminarz_nauczyciela($dane_nauczyciela[nauczyciel_ID])");
		
		$conn->close();
			
	}

	?>

	<div id="container">
	
		<div id="logo">
		
			<h1>Zalogowano jako nauczyciel</h1>
		
		</div>
		
		<a href="n_konto.php">
		<div id="inne">
		Zarządzanie kontem
		</div>
		</a>
				
		<a href="n_lekcje.php">
		<div id="inne">
		Lekcje
		</div> </a>
		
		<a href="n_oceny.php">
		<div id="inne">
		Oceny 
		</div></a>
		
		<a href="n_oceny_koncowe.php">
		<div id="inne">
		Oceny końcowe 
		</div></a>
		
		<a href="n_terminarz.php">
		<div id="teraz">
		Terminarz
		</div></a>
		
		<a href="n_uwagi.php">
		<div id="inne">
		Uwagi 
		</div></a>
		<?php
		if($dane_nauczyciela['czy_wych']=="Y")
		
		echo '<a href="n_klasa_wych.php">
		<div id="inne">
		Klasa wychowawcza
		</div></a>';	
		
		?>


	<div >
		
		

		<div id="tresc">
			<div id="lewy">
			
			
			<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
			<script language="javascript" type="text/javascript" >
			  function sprawdzObecnosc(id_klasy_in, id_lekcji_in, oddzial){
				  
				$.ajax({
					url: "./wybrana_klasa.php",
					data: {
						id_klasy: id_klasy_in,
						id_lekcji: id_lekcji_in
						}, 
				});
				window.open('./sprawdz_obecnosc.php?obecnosc=' + oddzial);
				
				
			  }
			</script>
			
			
			
			<?php
			
			
			
			
			?>
			
			
			<?php
			date_default_timezone_set('Europe/Warsaw');
			$date = date('Y-m-d', time());
			#$date = "2020-10-10"; //data do testowania
		    if($result3->num_rows > 0) {
				
				echo "<table border=2 style='font-size:15px;' >";
					echo "<tr><td style:'width:100px;'>";
						echo "Data";
						echo "</td><td style:'width:100px;'>";
						echo "Godzina";
						echo "</td><td style:'width:100px;'>";
						echo "Typ";
						echo "</td><td style:'width:100px;'>";
						echo "Przemiot";
						echo "</td><td style:'width:100px;'>";
						echo "Klasa";
						echo "</td><td style:'width:100px;'>";
						echo "Opis";
						echo '</td></tr>';
		        while($row3 = $result3->fetch_assoc()) {
					
					if ( $row3['data'] > $date || ( $row3['data'] == $date  && $row3['godz_start'] > time()))
					{
						echo "<tr><td style:'width:100px;'>";
						echo $row3['data'];
						echo "</td><td style:'width:100px;'>";
						echo $row3['godz'];
						echo "</td><td style:'width:100px;'>";
						echo $row3['typ'];
						echo "</td><td style:'width:100px;'>";
						echo $row3['przedmiot'];
						echo "</td><td style:'width:100px;'>";
						echo $row3['oddzial'];
						echo "</td><td style:'width:100px;'>";
						echo '<textarea style="resize: vertical; width:200px;hight:20px; max-height: 300px; min-height: 20px;">' .$row3['opis'].'</textarea>';
						echo '</td></tr>';
					}
					
		        }
				echo '</table>';
		    }
			
			
			?>
			

			</div>
		</div>
		
		<form action="../wyloguj.php" >
		<button type="submit">wyloguj</button>
		
		</form>
		

		
		<div id="footer">
		e-dziennik
		</div>

	
	
	</div>
	
</body>

</html>