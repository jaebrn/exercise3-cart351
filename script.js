function addTask() {
    
    let taskInput = document.querySelector("#taskInput");
    const taskText = taskInput.value.trim();
    console.log(taskInput);

    if (taskText !== '') {
        // Post new task to the server

        //Pakage the data into KeyValue pairs to send it
        let taskData = new FormData(taskInput);

        // Clear input field
        taskInput.value = '';

    }

    //Grab the taskData value and confirm it has been sent to the collection
    fetch('/script.js', {
        method: 'POST',
        body: taskData
        })
        .then(result => {
 
            console.log(result.response);
            if(result.response!=="success"){
                throw new Error('Something went wrong '+ result);
     
            }
            else{
                console.log('Here:', result);
                taskInput.reset();
     
            }
         })
        .catch(error => {
        console.error('Error:', error);
        });

}

function updateTaskUI(tasks) {
    const todoList = document.getElementById('todoList');
    todoList.innerHTML = '';

    tasks.forEach(task => {
        const taskItem = document.createElement('div');
        taskItem.textContent = task;
        todoList.appendChild(taskItem);
    });
}


function displayTask(tasks) {

}