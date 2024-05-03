// this file is responsible to handle any task status updates from the user
document.addEventListener("DOMContentLoaded", function (event) {
    const taskNumberToText = new Map([
        [0, 'blocked'],
        [1, 'progress'],
        [2, 'done'],
        [3, 'review']
    ]);

    const updateStatusInDataBase = (taskId, status) => {
        const url = `/project/management/update-status/${taskId}`;
        const requestBody = JSON.stringify({ status: status });
        fetch(url, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',

            },
            body: requestBody,
        })
            .then(response => {
                if (response.ok) {
                    if (response.status === 403) {
                        throw new Error('Forbidden: You are not allowed to update this task.');
                    }
                }
                else {
                    throw new Error('Network response was not ok');
                }

                return response.json();
            })
            .then(data => {

                console.log('Data received:', data);
            })
            .catch(error => {

                console.error('There was a problem with the request:', error);
            });
    };

    const changeTaskStatus = (oldStatus, task) => {
        const statusValues = Array.from(taskNumberToText.values());
        const currentIndex = statusValues.indexOf(oldStatus);
        const nextIndex = (currentIndex + 1) % statusValues.length;
        const newStatus = statusValues[nextIndex];

        console.log(newStatus);
        task.classList.remove(`task-${oldStatus}`);
        task.classList.add(`task-${newStatus}`);

        // Move the added class to the beginning
        const classListArray = Array.from(task.classList);
        const index = classListArray.indexOf(`task-${newStatus}`);
        if (index !== -1) {
            classListArray.splice(index, 1);
            classListArray.unshift(`task-${newStatus}`);
            task.className = classListArray.join(' ');
        }
        task.innerHTML = newStatus.toUpperCase();
        return newStatus
    };

    const changeTaskStatusHandler = (event) => {
        const clickedElemnt = event.target;
        if (!(clickedElemnt.classList.contains("task-status"))) {
            return
        }
        const status = clickedElemnt.classList[0].split('-')[1];
        const newStatus = changeTaskStatus(status, clickedElemnt);
        updateStatusInDataBase(clickedElemnt.id, newStatus);
    };




    const taskTable = document.querySelector(".task-table");
    taskTable.addEventListener('click', changeTaskStatusHandler);

});