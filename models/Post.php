<?php
    class Post {
        // DB stuff
        private $conn;

        // Post Properties
        public $User_Id;
        public $Name;
        public $Email;
        public $Event_Id;
        
        // Constructor with DB
        public function __construct($db){
            $this->conn = $db;
        }

        // Get Posts
        public function read(){
            // Create query
            $query = 'SELECT *
                FROM Users';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Execute query
            $stmt-> execute();

            return $stmt;
        }

        // Event Create
        public function event_create(){
            // Create query
            $query = 'INSERT INTO Events
                SET
                    Victim = :Victim,
                    Event_Time = :Event_Time';

            // Prepare statement
            $stmt = $this->conn->prepare($query);

            // Clean data
            $this->Victim = htmlspecialchars(strip_tags($this->Victim));
            $this->Event_Time = htmlspecialchars(strip_tags($this->Event_Time));

            // Bind data
            $stmt->bindParam(':Victim', $this->Victim);
            $stmt->bindParam(':Event_Time', $this->Event_Time);

            // Execute query
            try {
                if($stmt->execute()){
                    // Create query
                    $query = 'SELECT Event_Id FROM Events ORDER BY Event_Id DESC LIMIT 1;';

                    // Prepare statement
                    $stmt = $this->conn->prepare($query);

                    // Execute query
                    $stmt-> execute();

                    return $stmt;
                }
            } catch (\Throwable $th) {
                $message=$th->getMessage();
                echo $message;
            }
        }
        
        // Witness Create
        public static function witness_create(int $User_Id, int $Event_Id, $db){
            // Create query
            $query = 'INSERT INTO `Witness`
                SET 
                    User_Id = :User_Id,
                    Event_Id = :Event_Id';
            
            // Prepare statement
            $stmt = $db->prepare($query);
    
            // Clean data
            $User_Id = htmlspecialchars(strip_tags($User_Id));
            $Event_Id = htmlspecialchars(strip_tags($Event_Id));
    
            // Bind data
            $stmt->bindParam(':User_Id', $User_Id);
            $stmt->bindParam(':Event_Id', $Event_Id);
    
            // Execute query
            try {
                if($stmt->execute()){
                    return true;
                }
            } catch (\Throwable $th) {
                $message=$th->getMessage();
                echo $message;
            }
            return false;
        }
        // // Witness Create
        // public function witness_create(){
        //     // Create query
        //     $query = 'INSERT INTO `Witness`
        //         SET 
        //             `User_Id` = :User_Id,
        //             `Event_Id` = :Event_Id';
            
        //     // Prepare statement
        //     $stmt = $this->conn->prepare($query);

        //     // Clean data
        //     $this->User_Id = htmlspecialchars(strip_tags($this->User_Id));
        //     $this->Event_Id = htmlspecialchars(strip_tags($this->Event_Id));

        //     // Bind data
        //     $stmt->bindParam(':User_Id', $this->User_Id);
        //     $stmt->bindParam(':Event_Time', $this->Event_Id);

        //     // Execute query
        //     try {
        //         if($stmt->execute()){
        //             return true;
        //         }
        //     } catch (\Throwable $th) {
        //         $message=$th->getMessage();
        //         echo $message;
        //     }

        //     printf("Error: %s. \n", $stmt->error);

        //     return false;
        // }
    }