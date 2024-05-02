
const projectManagementNav = document.querySelector(".project-management-nav");
const navItems = document.querySelectorAll(".project-management-nav-item a");
const taskNumberToText = new Map([
    [0, 'blocked'],
    [1, 'progress'],
    [2, 'done'],
    [3, 'review']
]);
projectManagementNav.addEventListener("click", (event) => {
    event.preventDefault();
    if (event.target.tagName == "A") {
        navItems.forEach((navItem) => {
            navItem.classList.remove('active')
        })

        event.target.classList.add("active");

        if (event.target.innerHTML == "all tasks") {
            fetchTasks("all")
                .then(data => updateTasksTableUi(data))
                .catch(error => console.error('Error fetching tasks:', error));
        }

        else if (event.target.innerHTML == "assigned tasks") {
            fetchTasks("assigned").then(data => updateTasksTableUi(data)).catch(error => console.error('Error fetching tasks:', error));
            ;
        }

        else if (event.target.innerHTML == "project overview") {
            // fetchProjectData();

        }

    }
});

function fetchTasks(action) {
    const url = `/project/management/${action}tasks`;
    console.log(url);
    return fetch(url, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch tasks');
            }
            return response.json();
        });
}

function updateTasksTableUi(tasks) {
    const tableBody = document.querySelector('.task-table tbody');
    let taskStatus;
    // Clear existing table rows
    tableBody.innerHTML = '';

    // Loop through tasks and create table rows
    tasks.forEach(taskData => {
        const row = document.createElement('tr');
        taskStatus = taskNumberToText.get(taskData.status);
        row.innerHTML = `
            <td>${taskData.label}</td>
            <td id="${taskData.id}" class="task-${taskStatus} task-status">${taskStatus.toUpperCase()}</td>
            <td>${taskData.assignee}</td>
            <td>${taskData.due_date}</td>
            <td>${taskData.creator}</td>
        `;
        tableBody.appendChild(row);
    });
}


