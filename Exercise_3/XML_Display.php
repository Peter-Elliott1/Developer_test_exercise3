<!DOCTYPE html>
<html><!--HTML tag were used to center in formation being displayed and for a return button to index page-->
	<head>
		<title>Exercise 3</title>
	</head>
	<body><center>
		<?php
			function checkSiteID($xml)//Check the SiteID
			{
				$Site = true; //Assuming the SiteID is set to DUB for now
				
				foreach ($xml->children() as $child)// cycle through every inner tag set
				{
					if($child->getName() == "SiteID" AND $child != "DUB") 
					{//if the current tag is SiteID and its value if not DUB return false ending loop 
						return false;
					}
					// Continue cycle untill the end
					$Site = checkSiteID($child);
				}
				
				return $Site;//if end of loop is reached return true
			}

			function checkCommand($xml)//Check the command value
			{
				$Command = true; //Assume for now that Command Value is set to DEFAULT 
				foreach($xml->children() as $child) 
				{
					$role = $child->attributes();//this gets the tag
					if ($child->getName() == "Declaration" AND $child->attributes() != "DEFAULT")
					{//The current tag Declaration Command attributes is not set to 'DEFAULT' 
						return false;
					}
					
					$Command = checkCommand($child);
				} 
				
				return $Command;
			}

			//Function calls all others after file is uploaded
			function checkXML($myXMLFile)//checkXML is pased in the file location not the file itself
			{
				libxml_use_internal_errors(true);// for error handling
				$xml = simplexml_load_file($myXMLFile);//Now it reads in XML file
				
				if ($xml == false) //inform user of xml tags that don't match or if one is missing or other simple errors
				{
					echo "Failed loading XML: ";
					foreach(libxml_get_errors() as $error) 
					{
						echo "<br>", $error->message;//list errors and what line they occurred on
					}
				} 
				else 
				{//if code runs check the following and display corresponding message
					if(checkCommand($xml) == false)//Check Declaration tag attribute command is set to default
					{	
						print("Status Code : -1  <br> Invalid Command specified" );//Command not set to default
					}
					else if(checkSiteID($xml) == false)//Check if SiteID is DUB
					{	
						print("Status Code : -2  <br> Invalid Site specified" );//SiteID is not DUB
					}
					else //if no errors occur and Comand is set to 'Default' and SiteID is set to 'DUB'  
					{
						print("Status Code : 0  <br> Document was structured correctly");//Document is correct
					}
				}
			}

			$File_location = "uploads/";//file directory xml is saved
			$Target_file = $File_location . basename($_FILES["fileToUpload"]["name"]);//file path
			$Upload_error = true;
			$file_Extentsion = pathinfo($Target_file,PATHINFO_EXTENSION);// to check file extention
			// Check if image file is a actual image or fake image

			if (file_exists($Target_file)) // Check if file already exists
			{
				unlink($Target_file);//removes the file
			}

			if($file_Extentsion != "xml" )// Allow only XML file format
			{
				echo "Sorry, only XML files are allowed. <br>";
				$Upload_error = false;
			}
			else // if everything is ok, try to upload file
			{
				if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $Target_file)) 
				{
					echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded. <br>";
					checkXML($Target_file);
				}
				else 
				{//File could not be uploaded
					echo "Sorry, there was an error uploading your file. <br>";
				}
			}
		?>
		
		<br><!--The button below returns user to the previous page-->
		<button onclick="window.location.href ='index.html';" id="Page_return">Click me</button>
		</center>
	</body>
</html>