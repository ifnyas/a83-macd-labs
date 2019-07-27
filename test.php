<html>
	<head>
		<title>Upload Blob</title>
		/*<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
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
		</style>*/
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
			//Azure Blob Upload Start
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
			//Azure Blob Upload End
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
			  $fname = "HelloWorld.txt";

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
	 </body>
 </html>
