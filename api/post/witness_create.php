<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Post.php';
    
    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    //Instantiate blog post object
    // $post = new Post($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    // $post->User_Ids = $data->User_Ids;
    // $User_Id = $data->User_Id;
    $Event_Id = $data->Event_Id;

    $success = true;
    foreach ($data->User_Ids as $User_Id) {
        $result = Post::witness_create($User_Id, $Event_Id, $db);
        if(!$result)
            $success = false;
    }   

    // Create post
    if($success){
        echo json_encode(
            array('message' => 'Post Created', "error" => false)
        );
    }
    else{
        echo json_encode(
            array('message' => 'Post Not Created', "error" => true)
        );
    }

    