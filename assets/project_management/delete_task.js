document.addEventListener("DOMContentLoaded", function (event) {
    document.querySelector(".task-table").addEventListener('click', (event) => {
        if (!(event.target.classList.contains("delete-task"))) {
            return
        }
        const taskId = event.target.dataset.taskId;
        const url = `/project/management/delete-task/${taskId}`;
        fetch(url, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',

            },
        })
            .then(response => {
                if (response.ok) {
                    if (response.status === 403) {
                        throw new Error('Forbidden: You are not allowed to delete this task.');
                    }
                }
                else {
                    throw new Error('Network response was not ok');
                }

                return response.json();
            })
            .then(data => {

                console.log('Data received:', data);
                event.target.closest("tr").remove()
            })
            .catch(error => {

                console.error('There was a problem with the request:', error);
            });
    })
});