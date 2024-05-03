console.log("hellurs");
let membersButton=document.getElementById("members-button");
let projectsButton=document.getElementById("projects-button");
let overviewButton=document.getElementById("overview-button");
let content=document.getElementById("content")

membersButton.addEventListener('click',showMembers)
projectsButton.addEventListener('click',showProjects)
overviewButton.addEventListener('click',showOverview)
function showOverview(){
   content.innerHTML=`<div></div>`
}
function showProjects(){
    content.innerHTML=`<div>Projects</div>`
 }
 function showMembers(){
    content.innerHTML=`<div>Members</div>`
 }
