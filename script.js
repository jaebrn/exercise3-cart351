function addTask() {
    const taskInput = document.getElementById('taskInput');
    const taskText = taskInput.value.trim();

    if (taskText !== '') {
        // Post new task to the server

        // Clear input field
        console.log(taskText);
        taskInput.value = '';
    }
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