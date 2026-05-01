const submit = document.getElementById("homeBtn");
const home = document.querySelector("#homeBtn");
const closeBtn = document.querySelector("#closeBtn");
const loginDialog = document.querySelector("#loginDiaog");

function openLoginDialog(){
    loginDialog.showModal();
}

function closeLoginDialog(){
    loginDialog.closest();
}

function handleOutsideClick(){
    if(event.target===loginDialog){
        loginDialog.closest();
    }
}
addEventListener("click" ,()=>{
    location.reload();
});
homeBtn.addEventListener("click",openLoginDialog);
closeBtn.addEventListener("click",closeLoginDialog);
loginDialog.addEventListener("click",handleOutsideClick);

