<?php
session_save_path('/tmp');
session_start();

$session_id = session_id();

include('config.php');

$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
$mysqli->real_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']);
$link = mysqli_connect($config['db_host'],$config['db_user'],$config['db_password'],$config['db_name']);


?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <style>
            body {
                padding-top: 60px;
                padding-bottom: 40px;
            }
        </style>
        <link rel="stylesheet" href="css/bootstrap-responsive.min.css">
        <link rel="stylesheet" href="css/main.css">
		
		<style>

			body { font-family:'lucida grande', tahoma, verdana, arial, sans-serif; font-size:11px; }

			h1 { font-size: 15px; }

			a { color: #548dc4; text-decoration: none; }

			a:hover { text-decoration: underline; }

			table.testgrid { border-collapse: collapse; border: 1px solid #CCB; width: 800px; }

			table.testgrid td, table.testgrid th { padding: 5px; border: 1px solid #E0E0E0; }

			table.testgrid th { background: #E5E5E5; text-align: left; }

			input.invalid { background: red; color: #FDFDFD; }

		</style>

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
		
			<?php

			   if ( isset($_POST["submitfile"]) ) {
			   

				}
			?>
	 
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- This code is taken from http://twitter.github.com/bootstrap/examples/hero.html -->

        <div class="navbar navbar-inverse navbar-fixed-top">
            <div class="navbar-inner">
                <div class="container">
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </a>
                    <a class="brand" href="#">NJ-HITEC Employee Portal</a>
                    <div class="nav-collapse collapse">
                        <ul class="nav">
                            <li class="active"><a href="#">Home</a></li>
                        </ul>
                        <!--<form class="navbar-form pull-right">
                            <input class="span2" type="text" placeholder="Email">
                            <input class="span2" type="password" placeholder="Password">
                            <button type="submit" class="btn">Sign in</button>
                        </form> -->
                    </div><!--/.nav-collapse -->
                </div>
            </div>
        </div>

        <div class="container">

            <!-- Main hero unit for a primary marketing message or call to action -->
            <div class="hero-unit"<?php if ( isset($_POST["submitfile"]) ) { echo "style=\"display:none;\"";}?>>
                <center><h1>NJ-HITEC Expense Tool</h1>
                <p>Simplify your expenses! Upload your Outlook Calendar export below!</p>
		  </center>
			<center>
			<form class="form" action="index.php" method="POST" enctype="multipart/form-data">
				<input type="text" name="name" placeholder="Your Name" class="input-large"></input>
				<input type="text" name="empID" placeholder="Employee ID" class="input-large"></input>
				<select name="homeOffice"><option value="Newark">Newark Office</option><option value="Lawrenceville">Lawrenceville Office</option><option value="Galloway">Galloway Office</option><option value="Camden">Camden Office</option></select>
				<input type="file" name="upload" class="input-large"></input>
				<input type="hidden" name="ExpenseID" class="input-large" value="<?php echo ($lastID+1);?>"></input>
				<button type="submit" name="submitfile" class="btn btn-primary btn-large">Submit your file for analysis!</button>
			</form>
			</center>

            </div>
			
			<div>
			<?php
			
				
							
			$result = $mysqli->query("SELECT MAX(Expense_ID) FROM Travel_Entries");

			while ($row = mysqli_fetch_array($result)){
				$lastExpenseID = $row[0];	
				//echo $lastExpenseID;
				//echo "<br>";

			}

			   if ( isset($_POST["submitfile"]) ) {

			   if ( isset($_FILES["upload"])) {

						//if there was an error uploading the file
					if ($_FILES["upload"]["error"] > 0) {
						echo "Return Code: " . $_FILES["upload"]["error"] . "<br />";

					}
					else {
							 //Print file details
						 echo "Upload: " . $_FILES["upload"]["name"] . "<br />";
						 echo "Type: " . $_FILES["upload"]["type"] . "<br />";
						 echo "Size: " . ($_FILES["upload"]["size"] / 1024) . " Kb<br />";
						 echo "Temp file: " . $_FILES["upload"]["tmp_name"] . "<br />";

							 //if file already exists
						 if (file_exists("upload/" . $_FILES["upload"]["name"])) {
						echo $_FILES["upload"]["name"] . " already exists. ";
						 }
						 else {
								//Store file in directory "upload" with the name of "uploaded_file.txt"
									
						$storagename = $_POST["name"]."-calupload.csv";
						move_uploaded_file($_FILES["upload"]["tmp_name"], "calendaruploads/" . $storagename);
						echo "Stored in: " . "calendaruploads/" . $_FILES["upload"]["name"] . "<br />";
						$csvFile="calendaruploads/".$storagename;
						}
						
						function readCSV($csvFile){
						$file_handle = fopen($csvFile, 'r');
						while (!feof($file_handle) ) {
							$line_of_text[] = fgetcsv($file_handle, 1024);
						}
						fclose($file_handle);
						return $line_of_text;
						}
						
						
						$csv = readCSV($csvFile);

						//restructure Array to exclude any unauthorized expenses
						$arraycount=1;
						
							while($arraycount < count($csv)) {
							
								if(strpos($csv[$arraycount][14],'Travel')=== false) {
								
								echo strpos($csv[$arraycount][14],'Travel');
								echo $csv[$arraycount][14];
								unset($csv[$arraycount]);
								$csvReindex = array_values($csv);
								$csv = $csvReindex;
								
								//echo $arraycount;
								
								}
								
								else $arraycount++;

							
							}

						
						}

					
					$Name = $_POST['name'];
					$EmpID = $_POST['empID'];

					//echo "<br>";
					//echo $lastExpenseID;
					//echo "<br>";
					
					
					for ($arraycount = 1; $arraycount < count($csv); $arraycount++){
					
						$dateField = (date("d/m/y",strtotime($csv[$arraycount][1])));
						$lastIDField = ($lastExpenseID + 1);
						$SubjectField = $csv[$arraycount][0];
						$DestinationField = $csv[$arraycount][16];
						
						$query = ("INSERT INTO Travel_Entries(Entry_Session_ID, Full_Name, Employee_ID, Email, Date, Time, File_Name, Home_Office, Total_Mileage, Expense_ID, Subject, Origin, Destination, Time_of_Submission, Return_Trip, Remove_Expense, Mileage) 
						VALUES ('$session_id'
						,'$_POST[name]'
						,'$_POST[empID]'
						,''
						,'$dateField'
						,''
						,'$storagename'
						,'$_POST[homeOffice]'
						,''
						,'$lastIDField'
						,'$SubjectField'
						,'$_POST[homeOffice]'
						,'$DestinationField'
						,''
						,'0'
						,'1'
						,'')");
						

						$mysqli->query($query);

						//echo "1 record added";
						//printf("New Record ", $mysqli->insert_id);
						//echo "<br>";
						//echo $query;
						//echo "<br>";
						//printf("Error", $mysqli->error);
					}


					}
				 } else {
						 //echo "No file selected <br />";
				 }
				 

			


			?>
			
			</div>
		
			<!-- Feedback message zone -->
			<center><div id="message"></div></center>

			<!-- Grid contents -->
			<center><div id="tablecontent"></div></center>
		
			<!-- Paginator control -->
			<div id="paginator"></div>
			
			
			
			
			
		</div>  
		
		<script src="js/editablegrid-2.0.1.js"></script>   
		<!-- I use jQuery for the Ajax methods -->
		<script src="js/jquery-1.7.2.min.js" ></script>
		<script src="js/demo.js" ></script>

		<script type="text/javascript">
			window.onload = function() { 
				datagrid = new DatabaseGrid();
			}; 
		</script>
            <footer>
                <p>&copy; NJ-HITEC Ops 2013</p>
            </footer>

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.9.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
    </body>
</html>
