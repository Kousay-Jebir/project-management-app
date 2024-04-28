
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
            initProjectOverview();
            fetchProjectChart();
        }

    }
});

function fetchTasks(action) {
    url = `/project/management/${action}tasks`;
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


function initProjectOverview() {
    // Create a canvas element
    const canvasElement = document.createElement('canvas');
    canvasElement.id = 'acquisitions';

    // Append the canvas element to the div element
    document.querySelector(".chart-container").appendChild(canvasElement);
}

function fetchProjectChart() {
    (async function () {
        const data = [
            { year: 2010, count: 10 },
            { year: 2011, count: 20 },
            { year: 2012, count: 15 },
            { year: 2013, count: 25 },
            { year: 2014, count: 22 },
            { year: 2015, count: 30 },
            { year: 2016, count: 28 },
        ];

        new Chart(
            document.getElementById('acquisitions'),
            {
                type: 'bar',
                data: {
                    labels: data.map(row => row.year),
                    datasets: [
                        {
                            label: 'Acquisitions by year',
                            data: data.map(row => row.count)
                        }
                    ]
                }
            }
        );
    })();
}