<?php
if($_SERVER['HTTP_HOST']=="localhost" or $_SERVER['HTTP_HOST']=="192.168.8.102")
		{	
			//local  

				 DEFINE ('DB_USER', 'root');
				 DEFINE ('DB_PASSWORD', '');
				 DEFINE ('DB_HOST', 'localhost'); //host name depends on server
				 DEFINE ('DB_NAME', 'quantum');
		}
		else
		{
			//local live 

		 	 DEFINE ('DB_USER', 'quanlidj_quantum');
			 DEFINE ('DB_PASSWORD', '}(t+[0v@HRK+');
			 DEFINE ('DB_HOST', 'localhost'); //host name depends on server
			 DEFINE ('DB_NAME', 'quanlidj_quantum');
		}

	
	

	$mysqli =mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	
	mysqli_query($mysqli,"SET NAMES utf8mb4");
	
	?>