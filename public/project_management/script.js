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
            fetchAllTasks();
        }
    }
})

function fetchAllTasks() {
    fetch("/project/management/tasks", {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    })
        .then(response => response.json())
        .then(data => {
            // Handle the fetched data
            console.log(data);
        })
        .catch(error => console.error('Error:', error));
}