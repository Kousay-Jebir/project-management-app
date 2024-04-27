const projectManagementNav = document.querySelector(".project-management-nav");
const navItems = document.querySelectorAll(".project-management-nav-item a");

projectManagementNav.addEventListener("click", (event) => {
    event.preventDefault();
    if (event.target.tagName == "A") {
        navItems.forEach((navItem) => {
            navItem.classList.remove('active')
        })

        event.target.classList.add("active");

        if (event.target.innerHTML == "all tasks") {
            fetchAllTasks()
                .then(data => allTasksUi(data))
                .catch(error => console.error('Error fetching tasks:', error));
        }
    }
});

function fetchAllTasks() {
    return fetch("/project/management/tasks", {
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

function allTasksUi(tasks) {
    const tableBody = document.querySelector('.task-table tbody');

    // Clear existing table rows
    tableBody.innerHTML = '';

    // Loop through tasks and create table rows
    tasks.forEach(taskData => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${taskData.label}</td>
            <td class="task-${taskData.status.toLowerCase()}">${taskData.status}</td>
            <td>${taskData.assignee}</td>
            <td>${taskData.due_date}</td>
            <td>${taskData.creator}</td>
        `;
        tableBody.appendChild(row);
    });
}
