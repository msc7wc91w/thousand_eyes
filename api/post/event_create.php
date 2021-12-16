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

    // Instantiate blog post object
    $post = new Post($db);

    // Get raw posted data
    $data = json_decode(file_get_contents("php://input"));

    $post->Victim = $data -> Victim;
    $post->Event_Time = $data -> Event_Time;

    //Blog post query
    $result = $post->event_create();

    //Get row count
    $num = $result->rowCount();

    // Create post
    if($num > 0){
        // Post array
        $posts_arr = array();
        $posts_arr['data'] = array();

        while($row = $result->fetch(PDO::FETCH_ASSOC)){
            extract($row);

            $post_item = array(
                'message' => 'Post Created',
                'Event_Id' => $Event_Id
            );

            // Push to "data"
            array_push($posts_arr['data'], $post_item);
        }

        //Turn to JSON & output
        echo json_encode($posts_arr);
    }
    else{
        echo json_encode(
            array('message' => 'Post Not Created', "status" => false)
        );
    }