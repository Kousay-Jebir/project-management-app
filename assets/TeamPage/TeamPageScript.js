console.log("hellurs");
let membersButton=document.getElementById("members-button");
let projectsButton=document.getElementById("projects-button");
let overviewButton=document.getElementById("overview-button");
let content=document.getElementById("content")

membersButton.addEventListener('click',showMembers)
projectsButton.addEventListener('click',showProjects)
overviewButton.addEventListener('click',showOverview)
async function showOverview(){
   let id=document.getElementById("team_id").innerText;
   content.innerHTML=`<div id="overview-content"class="overview-content hidden">
   <div class="team-description">
       <h1>Team Description:</h1>
       <p style="font-size: 20px;" id="description"></p>
   </div>
   <div class="team-current-tasks">
       <h1>Current Tasks:</h1>
       <li class="task-component">
           ourhfrzi
       </li>
   </div>
 </div>`
 await fetchData(id,"overview").then(data=>{document.getElementById("description").innerHTML=data.teamDescription})
 document.getElementById("overview-content").classList.remove("hidden")
}
function showProjects(){
    content.innerHTML=`<div>Projects</div>`
 }
 function showMembers(){
    content.innerHTML=`<div>Members</div>`
 }
 async function fetchData(id,action) {

   const url = `/TeamPage/${id}/${action}`;
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
           console.log(response)
           return response.json();
       });
}