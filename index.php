<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // need to process
    // $author = $_POST['a_author'];
    $users = $_POST['a_users'];
    $task = $_POST['a_task'];
    $date = $_POST['a_date'];
    $description = $_POST['a_descript'];
    $priority = $_POST['a_priority'];

    try {
        require_once __DIR__ . '/vendor/autoload.php';

        //1: connect to mongodb atlas
        $client =
            new MongoDB\Client(
                "mongodb+srv://jaebrn:NCGJwCSrV2DN7HSb@cart351-jbrown.gpkovlg.mongodb.net/?retryWrites=true&w=majority"

            );
        //2: connect to collection (that exists):
        $collection = $client->CART351->GalleryItems;

        $collection->insertOne(
            [
                //'author' => $author,
                'users' => $users,
                'task' =>  $task,
                'date' => new MongoDB\BSON\UTCDateTime(strtotime($date) * 1000),
                'description' => $description,
                'priority' => $priority
            ]
        );

        //make an array an encode as json
        $msg = array();
        $msg["response"] = "success";
        echo json_encode($msg);
        exit;
    } //END TRY
    catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
    exit;
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercise 3 | CART 351</title>
    <link rel="stylesheet" href="stylesheet.css">
    <!-- <script src="script.js"></script> -->
</head>

<body>
    <div id="result"></div>

    <div class="formContainer">
        <form id="newTask" action="" enctype="multipart/form-data">
            <!-- Form for new task creation-->
            <h3> New Task:</h3>
            <fieldset>
                <p><label>Task:</label><input type="text" size="24" maxlength="40" name="a_task" required></p>
                <p><label>Users:</label><input type="text" size="24" maxlength="40" name="a_users" required></p>
                <p><label>Due Date:</label><input type="date" size="24" maxlength="40" name="a_date" required></p>
                <p><label>Description:</label><textarea type="text" maxlength="400" name="a_descript" required></textarea></p>
                <p><label>Priority: (1: Low-5: High)</label><input type="number" min="1" max="5" name="a_priority" required></p>
                <p class="sub"><input type="submit" name="submit" value="Submit Task" id="buttonS" /></p>
            </fieldset>
        </form>
    </div>

    <!-- <div id="todoList">
         To-Do List Items will be inserted here dynamically 
    </div>


    <div id="addTaskContainer">
        <input type="text" id="taskInput" placeholder="Add a new task" name="task_name">
        <button onclick="addTask()">Add Task</button>
    </div>

    <div id="displayCollection">
        <button onclick="displayTask()">Display Task</button>
    </div> -->

    <script>
        window.onload = function() {
            console.log("ready");
            document.querySelector("#newTask").addEventListener("submit", function() {
                event.preventDefault();
                console.log("button clicked");
                console.log("form has been submitted");


                //part two
                let form = document.querySelector("#newTask");
                let formData = new FormData(form);
                /*console.log to inspect the data */
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ', ' + pair[1]);
                }



                fetch('/index.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(function(response) {
                        console.log("got here with response ...");
                        return response.json();
                    })
                    .then(result => {

                        console.log(result.response);
                        if (result.response !== "success") {
                            throw new Error('Something went wrong ' + result);

                        } else {
                            console.log('Here:', result);
                            displayResponse(result);
                            form.reset();

                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });

            })

            function displayResponse(theResult) {
                let container = document.createElement("div");
                container.classList.add("outer");
                document.querySelector("#result").appendChild(container);

                let title = document.createElement("h3");
                title.textContent = "Results from user";
                container.appendChild(title);

                let contentContainer = document.createElement("div");
                contentContainer.classList.add("content");
                container.appendChild(contentContainer);

                for (let property in theResult) {
                    console.log(property);
                    if (property === "fileName") {
                        let img = document.createElement("img");
                        img.setAttribute('src', 'images/' + theResult[property]);
                        contentContainer.appendChild(img);
                    } else if (property !== "response") {
                        let para = document.createElement('p');
                        para.textContent = property + "::" + theResult[property];
                        contentContainer.appendChild(para);
                    }

                }


            }
        }
    </script>
</body>