
let areTasksAltered = false;
const projectManagementNav = document.querySelector(".project-management-nav");
const navItems = document.querySelectorAll(".project-management-nav-item a");
const sections = document.querySelectorAll('.project-management-dashboard > *');
const taskNumberToText = new Map([
    [0, 'blocked'],
    [1, 'progress'],
    [2, 'done'],
    [3, 'review']
]);

projectManagementNav.addEventListener("click", (event) => {
    event.preventDefault();
    if (event.target.tagName != "A") {
        return
    }

    navItems.forEach((navItem) => {
        navItem.classList.remove('active')
        navItem == event.target ? navItem.classList.add("active") : navItem.classList.remove("active");
    })

    // Toggle visibility of content based on navbar item clicked
    const sectionName = event.target.dataset.section;
    sections.forEach(section => {
        if (section.classList.contains(sectionName)) {
            section.classList.add('visible');
        } else {
            section.classList.remove('visible');
        }
    });

    if (event.target.id == "all-tasks" && areTasksAltered == true) {
        // Get the table element
        const taskTable = document.querySelector('.task-table');
        // Get all rows in the table body
        const rows = taskTable.querySelectorAll('tbody tr');

        // Loop through each row and hide if not an assigned task
        rows.forEach(row => {
            const assignedUser = row.querySelector('.assigned-user');

            row.style.display = ''; // Show the row
            console.log(row)

        });
    }
})

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



function showAssignedTasks(userName) {
    areTasksAltered = true;
    // Get the table element
    const taskTable = document.querySelector('.task-table');
    // Get all rows in the table body
    const rows = taskTable.querySelectorAll('tbody tr');

    // Loop through each row and hide if not an assigned task
    rows.forEach(row => {
        const assignedUser = row.querySelector('.assigned-user');
        if (assignedUser.innerHTML !== userName) {
            row.style.display = 'none';
        } else {
            row.style.display = ''; // Show the row
            console.log(row)
        }
    });
}

// Call the function when the "Assigned Tasks" link is clicked
const assignedTasksLink = document.getElementById('assigned-tasks-link');
assignedTasksLink.addEventListener('click', function (event) {
    event.preventDefault();
    showAssignedTasks(event.target.dataset.user);
});


