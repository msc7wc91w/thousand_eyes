<?php

header("Content-Type: application/json");
header("Acess-Control-Allow-Origin: *");
header("Acess-Control-Allow-Methods: POST");
header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");

include_once '../../config/Database.php';
include_once '../../models/Post.php';

// Instantiate DB & connect
$database = new Database();
$db = $database->connect();

// Get raw posted data
$data = json_decode(file_get_contents("php://input"));

$Event_Id = $_POST ["Event_Id"];
$fileName  =  $_FILES['Event_Img']['name'];
$tempPath  =  $_FILES['Event_Img']['tmp_name'];
$fileSize  =  $_FILES['Event_Img']['size'];
		
if(empty($fileName))
{
	$errorMSG = json_encode(array("message" => "Please select image", "error" => true));	
	echo $errorMSG;
}
else
{
	$upload_path = '../../upload/'; // set upload folder path 
	
	$fileExt = strtolower(pathinfo($fileName,PATHINFO_EXTENSION)); // get image extension
	// valid image extensions
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
					
	// allow valid image file formats
	if(in_array($fileExt, $valid_extensions))
	{				
		//check file not exist our upload folder path
		if(!file_exists($upload_path . $fileName))
		{
			// check file size '5MB'
			if($fileSize < 5000000){
                // print_r($tempPath . $upload_path . $fileName);
				move_uploaded_file($tempPath, $upload_path . $fileName); // move file from system temporary path to our upload folder path 
			}
			else{		
				$errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload under 5 MB size", "error" => true));	
				echo $errorMSG;
			}
		}
		else
		{		
			$errorMSG = json_encode(array("message" => "Sorry, file already exists check upload folder", "error" => true));	
			echo $errorMSG;
		}
	}
	else
	{		
		$errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "error" => true));	
		echo $errorMSG;		
	}
}
		
// if no error caused, continue ....
if(!isset($errorMSG))
{
    $query = 'UPDATE `Events`
                SET 
                    Event_Img = :fileName
                WHERE
                    Event_Id = :Event_Id';

    // Prepare statement
    $stmt = $db->prepare($query);
    
    // Clean data
    $fileName = htmlspecialchars(strip_tags($fileName));
    $Event_Id = htmlspecialchars(strip_tags($Event_Id));

    // print($fileName);

    // Bind data
    $stmt->bindParam(':fileName', $fileName);
    $stmt->bindParam(':Event_Id', $Event_Id);
	// echo($stmt->debugDumpParams());
    
    try {
        $stmt->execute();
    } catch (\Throwable $th) {
        $message=$th->getMessage();
        echo $message;
    }
	echo json_encode(array("message" => "Image Uploaded Successfully", "error" => false));	
}
