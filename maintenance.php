<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>V2 en approche</title>
<meta name="viewport" content="width=device-width, initial-scale=1">

<style>
body {
    margin:0;
    height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    background:#0f0f0f;
    font-family:Arial, sans-serif;
    color:white;
    text-align:center;
    overflow:hidden;
}

.container {
    animation: fadeIn 1.5s ease-in-out;
}

h1 {
    font-size:3rem;
    margin-bottom:40px;
    letter-spacing:2px;
}

.timer {
    display:flex;
    gap:20px;
    justify-content:center;
}

.box {
    background:#111;
    padding:30px;
    border-radius:15px;
    min-width:100px;
    box-shadow:0 0 20px #0d6efd;
}

.number {
    font-size:3rem;
    font-weight:bold;
    color:#0d6efd;
    text-shadow:0 0 15px #0d6efd;
}

.label {
    font-size:0.9rem;
    opacity:0.7;
    margin-top:10px;
}

@keyframes fadeIn {
    from {opacity:0; transform:translateY(20px);}
    to {opacity:1; transform:translateY(0);}
}
</style>
</head>
<body>

<div class="container">
    <h1>🚀 La V2 arrive vendredi 27 à 9h30</h1>

    <div class="timer">
        <div class="box">
            <div class="number" id="days">0</div>
            <div class="label">Jours</div>
        </div>
        <div class="box">
            <div class="number" id="hours">0</div>
            <div class="label">Heures</div>
        </div>
        <div class="box">
            <div class="number" id="minutes">0</div>
            <div class="label">Minutes</div>
        </div>
        <div class="box">
            <div class="number" id="seconds">0</div>
            <div class="label">Secondes</div>
        </div>
    </div>
</div>

<script>
const targetDate = new Date("2026-02-27T09:30:00").getTime();

function updateTimer() {
    const now = new Date().getTime();
    const distance = targetDate - now;

    if (distance <= 0) {
        window.location.href = "/";
        return;
    }

    document.getElementById("days").innerText =
        Math.floor(distance / (1000*60*60*24));
    document.getElementById("hours").innerText =
        Math.floor((distance/(1000*60*60)) % 24);
    document.getElementById("minutes").innerText =
        Math.floor((distance/(1000*60)) % 60);
    document.getElementById("seconds").innerText =
        Math.floor((distance/1000) % 60);
}

setInterval(updateTimer, 1000);
updateTimer();
</script>

</body>
</html>