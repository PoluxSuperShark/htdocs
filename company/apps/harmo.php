<?php
$q = $_GET['q'] ?? '';
$results = [];

if($q){
    $json = file_get_contents(
        "https://api.deezer.com/search?q=".urlencode($q)
    );
    $results = json_decode($json,true);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<title>Harmo</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;}

body{
    font-family:Arial,sans-serif;
    background:#0b0b0f;
    color:#fff;
    padding:40px 20px 120px;
}

/* LOGO */
.logo{
    text-align:center;
    font-size:48px;
    font-weight:bold;
    margin-bottom:30px;
    color:rgb(165,30,136);
    text-shadow:0 0 5px rgb(165,30,136),
                0 0 15px rgb(165,30,136),
                0 0 30px rgb(165,30,136);
    animation:neon 2s infinite alternate;
}

@keyframes neon{
    from{ text-shadow:0 0 5px rgb(165,30,136),
                       0 0 15px rgb(165,30,136);}
    to{   text-shadow:0 0 10px rgb(165,30,136),
                       0 0 30px rgb(165,30,136),
                       0 0 60px rgb(165,30,136);}
}

/* RECHERCHE */
.search-box{max-width:600px;margin:0 auto 40px;}

.search-box form{display:flex;gap:10px;}

.search-box input{
    flex:1;padding:15px;border:none;border-radius:12px;
    background:#151520;color:#fff;font-size:16px;
}

.search-box input:focus{
    background:#1f1f2e;
    box-shadow:0 0 10px rgb(165,30,136);
}

.search-box button{
    padding:15px 25px;border:none;border-radius:12px;
    background:rgb(165,30,136);
    font-weight:bold;cursor:pointer;
}

.search-box p{color:rgb(165,30,136);}

/* GRILLE */
.grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(250px,1fr));
    gap:25px;
}

.card{
    background:#151520;
    padding:15px;
    border-radius:15px;
    text-align:center;
}

/* COVER */
.cover{position:relative;cursor:pointer;}

.cover img{
    width:100%;
    border-radius:12px;
}

.cover span{
    position:absolute;
    top:50%;left:50%;
    transform:translate(-50%,-50%);
    font-size:24px;
    color:rgb(165,30,136);
    text-shadow:0 0 10px rgb(165,30,136);
}

/* BOUTON DEEZER */
.full{
    display:inline-block;
    margin-top:8px;
    padding:6px 12px;
    background:rgb(165,30,136);
    color:#000;
    border-radius:8px;
    text-decoration:none;
    font-weight:bold;
}

/* PLAYER GLOBAL */
#playerBar{
    position:fixed;
    bottom:0;
    left:0;
    width:100%;
    background:#111;
    padding:10px;
    box-shadow:0 -3px 20px #000;
}

#playerBar audio{width:100%;}
</style>
</head>
<body>

<div class="logo">🎵armo</div>

<div class="search-box">
<form>
<input name="q" placeholder="Rechercher un artiste, album..." value="<?= htmlspecialchars($q) ?>">
<button>Rechercher</button>
</form>
<br>
<p>Pour des raisons de droits d'auteurs, les morceaux sont limités à 30 secondes.</p>
</div>

<div class="grid">
<?php
if(!empty($results['data'])){
foreach($results['data'] as $track){
?>
<div class="card">

<div class="cover"
onclick="playTrack('<?= $track['preview'] ?>')">
<img src="<?= $track['album']['cover_medium'] ?>">
<span>🎵armo</span>
</div>

<b><?= htmlspecialchars($track['title']) ?></b>
<?= htmlspecialchars($track['artist']['name']) ?>

<br>

<a class="full"
target="_blank"
href="<?= $track['link'] ?>">
Écouter complet
</a>

</div>
<?php
}}
?>
</div>

<!-- PLAYER GLOBAL -->
<div id="playerBar">
<audio id="player" controls></audio>
</div>

<script>
function playTrack(url){
    const player=document.getElementById("player");
    player.src=url;
    player.play();
}
</script>

</body>
</html>
