<?php

//Post a file into mongoDB
//Function Called when pressing the add Task button from the script.js
if($_SERVER['REQUEST_METHOD'] == 'POST')
{
    echo("Connected to the collection");


        //Data needed to be posted unto the mongoDB collection
        $taskName = $_POST['task_name'];

     if($_FILES)
     {

        
        $fname = $_FILES['filename']['name'];
        move_uploaded_file($_FILES['filename']['tmp_name'], "images/".$fname);

        try {
            require_once __DIR__ . '/vendor/autoload.php';
        
            //Connect to the mongoDB collection
            $client = 
            new MongoDB\Client(
                "mongodb+srv://jaebrn:NCGJwCSrV2DN7HSb@cart351-jbrown.gpkovlg.mongodb.net/?retryWrites=true&w=majority"
            
            );
            $collection = $client->CART351->GalleryItems;
        
                   
            //Determine how the data is diplayed in the mongoDB collection
            $collection->insertOne(
                ['Task Name' => $taskName
                ]);
            
            //Encode the data as a JSON for mongoDB
            $data = array();
            $data["response"] = "success";
                echo json_encode($data);
                exit;
            }

            //End the TRY()
            catch (Exception $e) {
                echo 'Caught exception: ',  $e->getMessage(), "\n";
            }

            //Confirm the connection is set to mongoDB
        echo("Connected to the collection");
        echo("<br>");

    exit;
    }
}





?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise 3 | CART 351</title>
    <link rel="stylesheet" href="stylesheet.css">
    <script src="script.js"></script>
</head>

<body>
    <div id="todoList">
        <!-- To-Do List Items will be inserted here dynamically -->
    </div>

   
        <div id="addTaskContainer">
            <input type="text" id="taskInput" placeholder="Add a new task" name="task_name">
            <button onclick="addTask()">Add Task</button>
        </div>
</body>