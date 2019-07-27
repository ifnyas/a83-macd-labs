<html>
	<head>
		<title>Analyze Sample & Registration Form</title>
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
    	<form action="test.php" method="post" enctype="multipart/form-data">
			 <div data-role='page' id="resFileCont" >

				<input type="file" name="resFile" id="resFile" value="" />
				<!--<input type="text" name="name" />-->
				<input type="submit" value="Submit" data-inline="true"/>
			</div>
		</form>
		<div id="res"></div>
		<script>
			$(document).ready(function () {
			$("form").on('submit', (function (e) {
				e.preventDefault();

				$.ajax({
				url: "test.php", // Url to which the request is send
				type: "POST",             // Type of request to be send, called as method
				data: new FormData(this), // Data sent to server, a set of key/value pairs (i.e. form fields and values)
				contentType: false,       // The content type used when sending data to the server.
				cache: false,             // To unable request pages to be cached
				processData: false,        // To send DOMDocument or non processed data file it is set to false
				success: function (data)   // A function to be called if request succeeds
				{
					alert(data)
					$("#res").html(data)
				}
				});
			}));
			});
		</script>

		<?php
			require_once 'WindowsAzure\WindowsAzure.php';
			 use WindowsAzure\Common\ServicesBuilder;
			 use WindowsAzure\Common\ServiceException;

			 $connectionString = "DefaultEndpointsProtocol=https;AccountName=yasdicodingwebapp;AccountKey=yjbIzS7/7HS8wj1PlxPmw3ut21VogZGDGtEDB2w0p/6Q9EBSGaw7SH6IsCif4295EwFa15tWOWRmtE/YXfsWGQ==;EndpointSuffix=core.windows.net";
			 // Create blob REST proxy.
			 $blobRestProxy = ServicesBuilder::getInstance()->createBlobService($connectionString);

			 //$content = fopen("c:\myfile.txt", "r"); //this works when hard coded like this
			 //$blob_name = "myblob.txt";
			 //get posts
			 //$fpath = $_POST["resFile"];//tried this too - no go
			  $fpath = $_FILES["resFile"];
			  $fname = "hello.txt";

			  $content = fopen($_FILES["resFile"]["tmp_name"], 'r');
			  $blob_name = $fname;

			 try {
				 //Upload blob
				 $blobRestProxy->createBlockBlob("saskcontainer", $blob_name, $content);
			 }
			 catch(ServiceException $e){
				// Handle exception based on error codes and messages.
				// Error codes and messages are here: 
				// http://msdn.microsoft.com/en-us/library/windowsazure/dd179439.aspx
				$code = $e->getCode();
				$error_message = $e->getMessage();
				echo $code.": ".$error_message."<br />";
			}
		?>

		<br><br><br>
	 
		<script type="text/javascript">
			function processImage() {
				 // **********************************************
				 // *** Update or verify the following values. ***
				 // **********************************************

				 // Replace <Subscription Key> with your valid subscription key.
				 var subscriptionKey = "7e63320b3a6048e0a810b1d5142e6a6f";

				 // You must use the same Azure region in your REST API method as you used to
				 // get your subscription keys. For example, if you got your subscription keys
				 // from the West US region, replace "westcentralus" in the URL
				 // below with "westus".
				 //
				 // Free trial subscription keys are generated in the "westus" region.
				 // If you use a free trial subscription key, you shouldn't need to change
				 // this region.
				 var uriBase =
					 "https://southeastasia.api.cognitive.microsoft.com/vision/v2.0/analyze";

				 // Request parameters.
				 var params = {
					 "visualFeatures": "Categories,Description,Color",
					 "details": "",
					 "language": "en",
				 };

				 // Display the image.
				 var sourceImageUrl = document.getElementById("inputImage").value;
				 document.querySelector("#sourceImage").src = sourceImageUrl;

				 // Make the REST API call.
				 $.ajax({
					 url: uriBase + "?" + $.param(params),

					 // Request headers.
					 beforeSend: function(xhrObj){
						 xhrObj.setRequestHeader("Content-Type","application/json");
						 xhrObj.setRequestHeader(
							 "Ocp-Apim-Subscription-Key", subscriptionKey);
					 },

					 type: "POST",

					 // Request body.
					 data: '{"url": ' + '"' + sourceImageUrl + '"}',
				 })

				 .done(function(data) {
					 // Show formatted JSON on webpage.
					 $("#responseTextArea").val(JSON.stringify(data, null, 2));
				 })

				 .fail(function(jqXHR, textStatus, errorThrown) {
					 // Display error message.
					 var errorString = (errorThrown === "") ? "Error. " :
						 errorThrown + " (" + jqXHR.status + "): ";
					 errorString += (jqXHR.responseText === "") ? "" :
						 jQuery.parseJSON(jqXHR.responseText).message;
					 alert(errorString);
				 });
			 };
		</script>

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
			
		/**----------------------------------------------------------------------------------
		* Microsoft Developer & Platform Evangelism
		*
		* Copyright (c) Microsoft Corporation. All rights reserved.
		*
		* THIS CODE AND INFORMATION ARE PROVIDED "AS IS" WITHOUT WARRANTY OF ANY KIND, 
		* EITHER EXPRESSED OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE IMPLIED WARRANTIES 
		* OF MERCHANTABILITY AND/OR FITNESS FOR A PARTICULAR PURPOSE.
		*----------------------------------------------------------------------------------
		* The example companies, organizations, products, domain names,
		* e-mail addresses, logos, people, places, and events depicted
		* herein are fictitious.  No association with any real company,
		* organization, product, domain name, email address, logo, person,
		* places, or events is intended or should be inferred.
		*----------------------------------------------------------------------------------
		**/
		/** -------------------------------------------------------------
		# Azure Storage Blob Sample - Demonstrate how to use the Blob Storage service. 
		# Blob storage stores unstructured data such as text, binary data, documents or media files. 
		# Blobs can be accessed from anywhere in the world via HTTP or HTTPS. 
		#
		# Documentation References: 
		#  - Associated Article - https://docs.microsoft.com/en-us/azure/storage/blobs/storage-quickstart-blobs-php 
		#  - What is a Storage Account - http://azure.microsoft.com/en-us/documentation/articles/storage-whatis-account/ 
		#  - Getting Started with Blobs - https://azure.microsoft.com/en-us/documentation/articles/storage-php-how-to-use-blobs/
		#  - Blob Service Concepts - http://msdn.microsoft.com/en-us/library/dd179376.aspx 
		#  - Blob Service REST API - http://msdn.microsoft.com/en-us/library/dd135733.aspx 
		#  - Blob Service PHP API - https://github.com/Azure/azure-storage-php
		#  - Storage Emulator - http://azure.microsoft.com/en-us/documentation/articles/storage-use-emulator/ 
		#
		**/
		
		/* require_once 'vendor/autoload.php';
		require_once "./random_string.php";
		use MicrosoftAzure\Storage\Blob\BlobRestProxy;
		use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
		use MicrosoftAzure\Storage\Blob\Models\ListBlobsOptions;
		use MicrosoftAzure\Storage\Blob\Models\CreateContainerOptions;
		use MicrosoftAzure\Storage\Blob\Models\PublicAccessType;
		$connectionString = "DefaultEndpointsProtocol=https;AccountName=yasdicodingwebapp;AccountKey=yjbIzS7/7HS8wj1PlxPmw3ut21VogZGDGtEDB2w0p/6Q9EBSGaw7SH6IsCif4295EwFa15tWOWRmtE/YXfsWGQ==;EndpointSuffix=core.windows.net";

		//add value image url
		$url = document.getElementById("inputImage").value;
		$imgurl = 'http://www.foodtest.ru/images/big_img/sausage_3.jpg'; 
		$test = 'https://upload.wikimedia.org/wikipedia/commons/e/eb/Intel-logo.jpg';

		$imagename= basename($imgurl);
		if(file_exists($imagename)){continue;} 
		$image = getimg($test); 
		file_put_contents($imagename,$image);  
		
		// Create blob client.
		$blobClient = BlobRestProxy::createBlobService($connectionString);
		$fileToUpload = $imagename;
		if (!isset($_GET["Cleanup"])) {
			// Create container options object.
			$createContainerOptions = new CreateContainerOptions();
			// Set public access policy. Possible values are
			// PublicAccessType::CONTAINER_AND_BLOBS and PublicAccessType::BLOBS_ONLY.
			// CONTAINER_AND_BLOBS:
			// Specifies full public read access for container and blob data.
			// proxys can enumerate blobs within the container via anonymous
			// request, but cannot enumerate containers within the storage account.
			//
			// BLOBS_ONLY:
			// Specifies public read access for blobs. Blob data within this
			// container can be read via anonymous request, but container data is not
			// available. proxys cannot enumerate blobs within the container via
			// anonymous request.
			// If this value is not specified in the request, container data is
			// private to the account owner.
			$createContainerOptions->setPublicAccess(PublicAccessType::CONTAINER_AND_BLOBS);
			// Set container metadata.
			$createContainerOptions->addMetaData("key1", "value1");
			$createContainerOptions->addMetaData("key2", "value2");
			  $containerName = "yasblockblobs".generateRandomString();
			try {
				// Create container.
				$blobClient->createContainer($containerName, $createContainerOptions);
				// Getting local file so that we can upload it to Azure
				$myfile = fopen($fileToUpload, "w") or die("Unable to open file!");
				fclose($myfile);
				
				# Upload file as a block blob
				echo "Uploading BlockBlob: ".PHP_EOL;
				echo $fileToUpload;
				echo "<br />";
				
				$content = fopen($fileToUpload, "r");
				//Upload blob
				$blobClient->createBlockBlob($containerName, $fileToUpload, $content);
				// List blobs.
				$listBlobsOptions = new ListBlobsOptions();
				$listBlobsOptions->setPrefix("HelloWorld");
				echo "These are the blobs present in the container: ";
				do{
					$result = $blobClient->listBlobs($containerName, $listBlobsOptions);
					foreach ($result->getBlobs() as $blob)
					{
						echo $blob->getName().": ".$blob->getUrl()."<br />";
					}
				
					$listBlobsOptions->setContinuationToken($result->getContinuationToken());
				} while($result->getContinuationToken());
				echo "<br />";
				// Get blob.
				echo "This is the content of the blob uploaded: ";
				$blob = $blobClient->getBlob($containerName, $fileToUpload);
				fpassthru($blob->getContentStream());
				echo "<br />";
			}
			catch(ServiceException $e){
				// Handle exception based on error codes and messages.
				// Error codes and messages are here:
				// http://msdn.microsoft.com/library/azure/dd179439.aspx
				$code = $e->getCode();
				$error_message = $e->getMessage();
				echo $code.": ".$error_message."<br />";
			}
			catch(InvalidArgumentTypeException $e){
				// Handle exception based on error codes and messages.
				// Error codes and messages are here:
				// http://msdn.microsoft.com/library/azure/dd179439.aspx
				$code = $e->getCode();
				$error_message = $e->getMessage();
				echo $code.": ".$error_message."<br />";
			}
		} 
		else 
		{
			try{
				// Delete container.
				echo "Deleting Container".PHP_EOL;
				echo $_GET["containerName"].PHP_EOL;
				echo "<br />";
				$blobClient->deleteContainer($_GET["containerName"]);
			}
			catch(ServiceException $e){
				// Handle exception based on error codes and messages.
				// Error codes and messages are here:
				// http://msdn.microsoft.com/library/azure/dd179439.aspx
				$code = $e->getCode();
				$error_message = $e->getMessage();
				echo $code.": ".$error_message."<br />";
			}
		}
		?> */

	 </body>
 </html>
