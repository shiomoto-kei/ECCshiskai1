const dialog = document.getElementById(/*社員安否一覧表*/'');
document.getElementById('openDialog').addEventListener('click',()=>{
    dialog.showModal();
});

document.getElementById('').addEventListener('click',(e)=>{
    e.preventDefault();
    const $ename = document.getElementById('$ENAME').value.trim();

    window.location.href = `detail.html?id=$(/*URL(id)*/)`;
})