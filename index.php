<html>
  <head>
		<title>2 Analyze Sample & Registration Form</title>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
		<style type="text/css">
		   body { background-color: #fff; border-top: solid 10px #000;
			   color: #333; font-size: .85em; margin: 20; padding: 20;
			   font-family: "Segoe UI", Verdana, Helvetica, Sans-Serif;
		   }
		   h1, h2, h3,{ color: #000; margin-bottom: 0; padding-bottom: 0; }
		   h1 { font-size: 2em; }
		   h2 { font-size: 1.75em; }
		   h3 { font-size: 1.2em; }
		   table { margin-top: 0.75em; }
		   th { font-size: 1.2em; text-align: left; border: none; padding-left: 0; }
		   td { padding: 0.25em 2em 0.25em 0em; border: 0 none; }
		</style>
	</head>
	
	<body>
		
		<script type="text/javascript">
			//Azure Process Image Start
			function processImage() {
				
				var subscriptionKey = "7e63320b3a6048e0a810b1d5142e6a6f";
				var uriBase = "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";
				var params = 
				{
					 "visualFeatures": "Categories,Description,Color",
					 "details": "",
					 "language": "en"
				};
				var sourceImageUrl = document.getElementById("inputImage").value;
				document.querySelector("#sourceImage").src = sourceImageUrl;

				$.ajax({
					 url: uriBase + "?" + $.param(params),
					 beforeSend: function(xhrObj){
						 xhrObj.setRequestHeader("Content-Type","application/json");
						 xhrObj.setRequestHeader(
							 "Ocp-Apim-Subscription-Key", subscriptionKey);
					 },
					 type: "POST",
					 data: '{"url": ' + '"' + sourceImageUrl + '"}',
				 })

				 .done(function(data) {
					 $("#responseTextArea").val(JSON.stringify(data, null, 2));
				 })

				 .fail(function(jqXHR, textStatus, errorThrown) {
					 var errorString = (errorThrown === "") ? "Error. " :
						 errorThrown + " (" + jqXHR.status + "): ";
					 errorString += (jqXHR.responseText === "") ? "" :
						 jQuery.parseJSON(jqXHR.responseText).message;
					 alert(errorString);
				});
			};
			//Azure Process Image End
		</script>
		
		<?php
			$host = "yas-dicodingappserver.database.windows.net";
			$user = "ifnyas";
			$pass = "D1coding";
			$db = "dicodingdb";

			try {
				$conn = new PDO("sqlsrv:server = $host; Database = $db", $user, $pass);
				$conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			} catch(Exception $e) {
				echo "Failed: " . $e;
			}

			if (isset($_POST['submit'])) {
				try {
					$name = $_POST['name'];
					$email = $_POST['email'];
					$job = $_POST['job'];
					$date = date("Y-m-d");
					// Insert data
					$sql_insert = "INSERT INTO Registration (name, email, job, date) 
								VALUES (?,?,?,?)";
					$stmt = $conn->prepare($sql_insert);
					$stmt->bindValue(1, $name);
					$stmt->bindValue(2, $email);
					$stmt->bindValue(3, $job);
					$stmt->bindValue(4, $date);
					$stmt->execute();
				} catch(Exception $e) {
					echo "Failed: " . $e;
				}

				echo "<h3>Your're registered!</h3>";
			} else if (isset($_POST['load_data'])) {
				try {
					$sql_select = "SELECT * FROM Registration";
					$stmt = $conn->query($sql_select);
					$registrants = $stmt->fetchAll(); 
					if(count($registrants) > 0) {
						echo "<h2>People who are registered:</h2>";
						echo "<table>";
						echo "<tr><th>Name</th>";
						echo "<th>Email</th>";
						echo "<th>Job</th>";
						echo "<th>Date</th></tr>";
						foreach($registrants as $registrant) {
							echo "<tr><td>".$registrant['name']."</td>";
							echo "<td>".$registrant['email']."</td>";
							echo "<td>".$registrant['job']."</td>";
							echo "<td>".$registrant['date']."</td></tr>";
						}
						echo "</table>";
					} else {
						echo "<h3>No one is currently registered.</h3>";
					}
				} catch(Exception $e) {
					echo "Failed: " . $e;
				}
			}
		?>
		
		 <h1>Analyze image:</h1>
		 Enter the URL to an image, then click the <strong>Analyze image</strong> button.
		 <br><br>
		 Image to analyze:
		 <input type="text" name="inputImage" id="inputImage"
			 value="https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Pierre-Auguste_Renoir_-_Paris%2C_le_quai_Malaquais.jpg/727px-Pierre-Auguste_Renoir_-_Paris%2C_le_quai_Malaquais.jpg" />
		 <button onclick="processImage()">Analyze image</button>
		 <br><br>
		 <div id="wrapper" style="width:1020px; display:table;">
			 <div id="jsonOutput" style="width:600px; display:table-cell;">
				 Response:
				 <br><br>
				 <textarea id="responseTextArea" class="UIInput"
						   style="width:580px; height:400px;"></textarea>
			 </div>
			 <div id="imageDiv" style="width:420px; display:table-cell;">
				 Source image:
				 <br><br>
				 <img id="sourceImage" width="400" />
			 </div>
		 </div>
	 
		<br><br><br>

		 <h1>Register here!</h1>
		 <p>Fill in your name and email address, then click <strong>Submit</strong> to register.</p>
		 <form method="post" action="index.php" enctype="multipart/form-data" >
			   Name  <input type="text" name="name" id="name"/></br></br>
			   Email <input type="text" name="email" id="email"/></br></br>
			   Job <input type="text" name="job" id="job"/></br></br>
			   <input type="submit" name="submit" value="Submit" />
			   <input type="submit" name="load_data" value="Load Data" />
		 </form>
		 
	 </body>
 </html>
