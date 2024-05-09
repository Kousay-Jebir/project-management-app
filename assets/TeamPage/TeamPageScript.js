console.log("hellurs");
let membersButton=document.getElementById("members-button");
let projectsButton=document.getElementById("projects-button");
let overviewButton=document.getElementById("overview-button");
let content=document.getElementById("content")


showOverview();
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
   <h1>Team Statistics:</h1>
   <table style="width:100%" class="task-component">
       <tr>
         <th>members</th>
         <td id="nb_members"></td>
       </tr>
       <tr>
         <th>projects</th>
         <td id="nb_projects"></td>
       </tr>
       <tr>
           <th>current project</th>
           <td id="current_project"></td>
       </tr>
     </table>

     <h1>MySpace:</h1>
     <table style="width:100%" class="task-component">
         <tr>
           <th>role within the team</th>
           <td>manager</td>
         </tr>
         <tr>
           <th>tasks</th>
           <td id="nb_tasks"></td>
         </tr>
         
       </table>
   </div>
 </div>`
 await fetchData(id,"overview").then(data=>{document.getElementById("description").innerHTML=data.teamDescription;
 document.getElementById("nb_members").innerHTML=data.nb_members
 document.getElementById("nb_projects").innerHTML=data.nb_projects
 document.getElementById("nb_tasks").innerHTML=data.nb_tasks
 document.getElementById("current_project").innerHTML=data.current_project
 })
 document.getElementById("overview-content").classList.remove("hidden")
}
async function showProjects(){
let id=document.getElementById("team_id").innerText;
content.innerHTML=` <div id="projects-container" class="projects-container">
</div>`
let projects= await fetchData(id,"projects");
generateProjects(projects,id);

 }
async function showMembers(){
  let id=document.getElementById("team_id").innerText;
  content.innerHTML=` <div id="members-container" class="members-container">
  </div>`
  let members= await fetchData(id,"members");
  generateMembers(members);

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
               throw new Error('Failed to fetch data');
           }
           
           return response.json();
       });
}
function generateProjects(projects,id){

  console.log(projects)
  let container=document.getElementById("projects-container")
  projects.forEach(project => {
    container.innerHTML+=`<div class="project-component">
    <h1 id="project-name" class="project-name">${project.projectName}</h1>
    <p id="project-description" class="project-description">${project.description}</p>
    <a href="/project/management/${id}/${project.project_id}"><button class="project-button">View Project</button></a>
</div>`
  });

}
function generateMembers(members){

 
  let container=document.getElementById("members-container")
  members.forEach(member => {
    container.innerHTML+=`<div class="member-component">
    <h2>${member.userName}</h2>
    <div class="email-thingy">
    <p style="text-align: center;"><span style="font-size: 20px;">Email:     </span>${member.email}</p>
    </div>
</div>`
  });

}