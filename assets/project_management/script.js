
const projectManagementNav = document.querySelector(".project-management-nav");
const navItems = document.querySelectorAll(".project-management-nav-item a");
const projectOverviewSection = document.querySelector(".project-overview");
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

        // Check if the clicked link is "project overview"
        if (event.target.innerHTML == "project overview") {
            // Show the project overview section
            projectOverviewSection.classList.add("visible");
        } else {
            // Hide the project overview section for other links
            projectOverviewSection.classList.remove("visible");
        }

        if (event.target.innerHTML == "all tasks") {
            fetchTasks("all")
                .then(data => updateTasksTableUi(data))
                .catch(error => console.error('Error fetching tasks:', error));
        } else if (event.target.innerHTML == "assigned tasks") {
            fetchTasks("assigned")
                .then(data => updateTasksTableUi(data))
                .catch(error => console.error('Error fetching tasks:', error));
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
            <td class="truncate">${taskData.description}</td>
            <td>${taskData.creator}</td>
        `;
        tableBody.appendChild(row);
    });
}

document.querySelector(".task-table").addEventListener('click', (event) => {
    const target = event.target;
    const isDescriptionCell = target.classList.contains("truncate");

    // Check if the clicked element is the description cell
    if (isDescriptionCell) {
        const description = target.innerText; // Get the description text
        openModal(description);
    }
});

function openModal(description) {
    // Create modal container
    const modalContainer = document.createElement('div');
    modalContainer.classList.add('modal-container');

    // Create modal content
    const modalContent = document.createElement('div');
    modalContent.classList.add('modal-content');
    modalContent.innerHTML = `
        <p>${description}</p>
    `;

    // Append modal content to modal container
    modalContainer.appendChild(modalContent);

    // Append modal container to body
    document.body.appendChild(modalContainer);

    // Center the modal in the window
    const modalHeight = modalContent.offsetHeight;
    const modalWidth = modalContent.offsetWidth;
    modalContent.style.top = `calc(50% - ${modalHeight / 2}px)`;
    modalContent.style.left = `calc(50% - ${modalWidth / 2}px)`;

    // Close the modal when clicking anywhere outside of it
    modalContainer.addEventListener('click', closeModal);

    // Prevent clicks on the modal content from closing the modal
    modalContent.addEventListener('click', (event) => {
        event.stopPropagation();
    });
}

function closeModal(event) {
    const modalContainer = event.target.closest('.modal-container');
    if (modalContainer) {
        modalContainer.remove();
    }
}