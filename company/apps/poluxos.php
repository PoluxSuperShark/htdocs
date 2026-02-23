<?php
$q = $_GET['q'] ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Fake Windows 10 UI</title>
<style>
body{margin:0;background:url('https://wallpapercave.com/dwp1x/wp5493583.jpg') no-repeat center center fixed;background-size:cover;font-family:Segoe UI,sans-serif;overflow:hidden;}
.desktop{height:calc(100vh - 36px);padding:10px;color:white;display:flex;flex-wrap:wrap;align-items:flex-start;}
.desktop-icon{width:80px;text-align:center;cursor:pointer;margin:10px;}
.desktop-icon div{font-size:32px;}

.window{position:absolute;top:80px;left:100px;width:500px;height:320px;background:white;color:black;border-radius:6px;display:none;box-shadow:0 0 20px rgba(0,0,0,.5);flex-direction:column;}
.window-header{background:#0078d7;color:white;padding:6px;display:flex;justify-content:space-between;align-items:center;cursor:move;}
.window-header div{display:flex;gap:6px;}
.window-header button{background:none;border:none;color:white;font-weight:bold;cursor:pointer;}
.window-content{padding:10px;flex:1;overflow:auto;}

.taskbar{position:fixed;bottom:0;width:100%;height:36px;background:#1f1f1f;display:flex;align-items:center;justify-content:space-between;}
.task-left,.task-right{display:flex;align-items:center;}
.start{width:60px;height:36px;display:flex;justify-content:center;align-items:center;cursor:pointer;font-size:26px;background:#1f1f1f;color:white;border-radius:4px;}.start:hover{background:#333;}
.search{background:white;height:36px;display:flex;align-items:center;padding:0 12px;margin-left:6px;}
.search input{border:none;outline:none;width:280px;font-size:13px;}
.task-icon{width:40px;height:36px;display:flex;justify-content:center;align-items:center;cursor:pointer;color:white;font-size:16px;}
.task-icon:hover{background:#333;}
.cortana{width:10px;height:10px;border:2px solid white;border-radius:50%}
.task-right{color:white;gap:10px;padding-right:10px;font-size:11px;}
.datetime{text-align:right;line-height:12px;}
.start-menu{position:fixed;bottom:36px;left:0;width:420px;height:520px;background:#202020;color:white;display:none;box-shadow:none;}
.start-left{width:180px;background:#171717;height:100%;float:left;overflow:auto;padding:8px;font-size:13px;}
.start-left div{padding:4px;cursor:pointer;display:flex;justify-content:space-between;align-items:center;}
.pin-btn{cursor:pointer;color:#aaa;font-weight:bold;}
.start-left div:hover{background:#333;}
.start-right{margin-left:180px;padding:12px;}
.tile{width:90px;height:90px;background:white;color:black;display:inline-flex;justify-content:center;align-items:center;margin:6px;font-size:13px;border:2px solid black;}
</style>
</head>
<body>

<div class="desktop" id="desktop">
  <div class="desktop-icon" data-app="explorer"><div>🗑️</div>Corbeille</div>
  <div class="desktop-icon" data-app="notepad"><div>📄</div>Bloc-notes</div>
</div>

<div class="start-menu" id="startMenu">
    <div class="start-left" id="appList">
        <div data-app="calculator">Calculatrice <span class="pin-btn">📌</span></div>
        <div data-app="notepad">Bloc-notes <span class="pin-btn">📌</span></div>
        <div data-app="paint">Paint <span class="pin-btn">📌</span></div>
        <div data-app="explorer">Explorateur <span class="pin-btn">📌</span></div>
    </div>
    <div class="start-right">
        <div class="tile" data-app="mail">Mail</div>
        <div class="tile" data-app="edge">Edge</div>
        <div class="tile" data-app="photos">Photos</div>
        <div class="tile" data-app="store">Store</div>
    </div>
</div>

<div class="taskbar">
    <div class="task-left" id="pinnedApps">
        <div class="start" id="startBtn">⊞</div>
        <form class="search" id="searchForm"><input id="searchInput" placeholder="Taper ici pour rechercher"></form>
        <div class="task-icon" id="cortanaBtn"><div class="cortana"></div></div>
        <div class="task-icon" id="taskViewBtn">▢▢</div>
    </div>
    <div class="task-right">
        ^ 🔊 📶 🔋 
        <div class="datetime"><div id="clock"></div><div id="date"></div></div>
        <div id="notifBtn" style="cursor:pointer">💬</div>
    </div>
</div>

<script>
const startBtn=document.getElementById('startBtn');
const menu=document.getElementById('startMenu');
startBtn.onclick=()=>menu.style.display=menu.style.display==='block'?'none':'block';

/* Recherche Google */
document.getElementById('searchForm').onsubmit=e=>{
 e.preventDefault();
 const q=document.getElementById('searchInput').value.trim();
 if(q)window.location.href='https://www.google.com/search?q='+encodeURIComponent(q);
};

/* Horloge */
function updateClock(){
 const d=new Date();
 clock.textContent=d.toLocaleTimeString([], {hour:'2-digit',minute:'2-digit'});
 date.textContent=d.toLocaleDateString();
}
setInterval(updateClock,1000);updateClock();

/* Applications */
let apps={};
let pinnedApps=JSON.parse(localStorage.getItem('pinnedApps')||'[]');
function createWindow(id,title,content){
 if(apps[id]) return;
 const win=document.createElement('div');
 win.className='window';
 win.id='win_'+id;
 win.innerHTML=`<div class="window-header"><span>${title}</span><div><button class='minBtn'>_</button><button class='maxBtn'>□</button><button class='closeBtn'>×</button></div></div><div class="window-content">${content}</div>`;
 document.body.appendChild(win);
 apps[id]=win;
 updateTaskbar();
 win.style.display='flex';
 win.querySelector('.closeBtn').onclick=()=>{win.style.display='none';updateTaskbar();saveState();};
 win.querySelector('.minBtn').onclick=()=>{win.style.display='none';updateTaskbar();saveState();};
 win.querySelector('.maxBtn').onclick=()=>{win.style.width='90vw';win.style.height='80vh';win.style.left='5vw';win.style.top='5vh';};
}

function updateTaskbar(){
 const left=document.getElementById('pinnedApps');
 document.querySelectorAll('.task-left .app-icon').forEach(e=>e.remove());
 pinnedApps.forEach(id=>{
   const ico=document.createElement('div');
   ico.className='task-icon app-icon';
   ico.textContent=id==='explorer'?'📁':'📄';
   ico.onclick=()=>{createWindow(id,id,`Contenu de ${id}`);};
   left.appendChild(ico);
 });
}

/* Click sur icônes */
document.querySelectorAll('[data-app]').forEach(el=>{
 const pinBtn=el.querySelector('.pin-btn');
 el.onclick=e=>{
   if(e.target!==pinBtn){
     const id=el.getAttribute('data-app');
     createWindow(id,el.textContent.replace(' 📌',''),`Contenu de ${el.textContent.replace(' 📌','')}`);
   }
 };
 if(pinBtn){
   pinBtn.onclick=e=>{
     e.stopPropagation();
     const id=el.getAttribute('data-app');
     if(!pinnedApps.includes(id)) pinnedApps.push(id);
     localStorage.setItem('pinnedApps',JSON.stringify(pinnedApps));
     updateTaskbar();
   };
 }
});

/* Notifications */
const notifPanel=document.createElement('div');
notifPanel.className='notifications-panel';
notifPanel.innerHTML='<h3>Notifications</h3><p>Aucune notification</p>';
document.body.appendChild(notifPanel);
document.getElementById('notifBtn').onclick=()=>{
 notifPanel.style.display=notifPanel.style.display==='block'?'none':'block';
};

updateTaskbar();
</script>

</body>
</html>
