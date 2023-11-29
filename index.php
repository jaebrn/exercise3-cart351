<?php
require_once __DIR__ . '/vendor/autoload.php';
$taskQuery = array('Type' => 'Task');

try {
    $client =
        new MongoDB\Client(
            "mongodb+srv://jaebrn:NCGJwCSrV2DN7HSb@cart351-jbrown.gpkovlg.mongodb.net/?retryWrites=true&w=majority"

        );
    //2: connect to collection (that exists):
    $collection = $client->CART351->GalleryItems;
    $resultObject = $collection->find($taskQuery); // filter results to tasks only here
    echo ("</br>");
    echo "<h3> Query Results:::</h3>";

    echo "<div id='back'>";

    foreach ($resultObject as $galleryItem) {

        //go through each doc

        echo "<div class ='outer'>";
        echo "<div class ='content'>";

        foreach ($galleryItem as $key => $value) {
            if ($key != "imagePath" && $key != "creationDate") {

                echo ("<p>" . $key . " ::" . $value . "</p>");
            }

            if ($key == "creationDate") {
                $dateTime = $value->toDateTime();
                echo ("<p>" . $key . " ::" . $dateTime->format('r') . "</p>");
            }
        }
    }
    //end back
    echo "</div>";
    //FOR ONE OBJECT. //


} catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') { //runs when submitting new task

    // need to process
    $Type = $_POST['Type'];
    $author = $_POST['a_author'];
    $users = $_POST['a_users'];
    $task = $_POST['a_task'];
    $date = $_POST['a_date'];
    $description = $_POST['a_descript'];
    $priority = $_POST['a_priority'];

    try {
        //1: connect to mongodb atlas


        $collection->insertOne(
            [
                'Type' => "Task",
                'author' => $author,
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

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['submitSearch'])) { //runs when submitting search
    //NEW open a the db and add in the try and catch
    // get the contents from the db and output. ..
    try {
        // get the search field
        $searchField = $_GET["a_search"];
        //make the request
        $resultObject = $collection->find(['artist' => $searchField]);

        $arrayToReturn = [];
        foreach ($resultObject as $galleryItem) {
            $myPackagedData = new stdClass();
            foreach ($galleryItem as $key => $value) {
                if ($key == "creationDate") {
                    $dateTime = $value->toDateTime();
                    $myPackagedData->$key = $dateTime->format('r');
                } else if ($key != "_id") {
                    $myPackagedData->$key = $value;
                }
            }
            $arrayToReturn[] = $myPackagedData;
        }
        echo (json_encode($arrayToReturn));


        exit;
    } //END TRY
    catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }
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
    <!-- <select id="user" onchange="onChange(this.value)">
        <option value="0"> Log In </option>
        <option value="1"> Jenna </option>
        <option value="2"> Sabine </option>
        <option value="3"> Alex </option>
    </select> -->

    <div id="result"></div>

    <div class="formContainer">
        <form id="newTask" action="" enctype="multipart/form-data">
            <!-- Form for new task creation-->
            <h3> Add New Task:</h3>
            <fieldset>
                <p><label>Who are you?</label><input type="text" size="24" maxlength="40" name="a_author" required></p>
                <p><label>What needs to be done?</label><input type="text" size="24" maxlength="40" name="a_task" required></p>
                <p><label>Who needs to do it?</label><input type="text" size="24" maxlength="40" name="a_users" required></p>
                <p><label>When must it be done by?</label><input type="date" size="24" maxlength="40" name="a_date" required></p>
                <p><label>Tell me more...</label><textarea type="text" maxlength="400" name="a_descript" required></textarea></p>
                <p><label>How urgent is it? (1: Not - 5: Very)</label><input type="number" min="1" max="5" name="a_priority" required></p>
                <p class="sub"><input type="submit" name="submit" value="Submit Task" id="buttonS" /></p>
            </fieldset>
        </form>
    </div>

    <div class="searchFormContainer">
        <h1> SEARCH & VIEW</h1>
        <form id="searchForm" action="">
            <p>Search By Artist: <input type="text" size="24" maxlength="40" name="a_search" id="a_search">
                <input type="submit" name="submit" value="submit my info" id="buttonSearch" />
            </p>
        </form>
    </div>


    <script>
        window.onload = function() {
            console.log("ready");
            document.querySelector("#searchForm").addEventListener("submit", function() {
                event.preventDefault();
                console.log("button clicked");
                console.log("form has been submitted");
                let formData = new FormData(document.querySelector("#searchForm"));
                formData.append("submitSearch", "extraTest");
                /* excellent function */
                // converts form data to the data format we need 
                const queryString = new URLSearchParams(formData).toString();

                // make sure that the URL is the same as the page we are currently working on
                fetch("index.php/?" + queryString)
                    .then(function(response) {
                        return response.json();
                    })
                    .then(function(jsonData) {
                        console.log(jsonData);
                        displayResponse(jsonData);
                    });

            })
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

                document.querySelector("#result").innerHTML = "";
                let back = document.createElement("div");
                back.id = "back";
                let title = document.createElement("h3");
                title.textContent = "Results from user";
                document.querySelector("#result").appendChild(title);
                document.querySelector("#result").appendChild(back);


                for (let i = 0; i < theResult.length; i++) {

                    let container = document.createElement("div");
                    container.classList.add("outer");
                    back.appendChild(container);

                    let contentContainer = document.createElement("div");
                    contentContainer.classList.add("content");
                    container.appendChild(contentContainer);

                    for (let property in theResult[i]) {
                        console.log(property);

                        if (property !== "imagePath") {
                            let para = document.createElement('p');
                            para.textContent = property + "::" + theResult[i][property];

                            contentContainer.appendChild(para);
                        }

                    }
                } //outer for
            }
        }
    </script>
</body>