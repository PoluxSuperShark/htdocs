<p>Viens voir le profil de <a href="#" id="bot-link">@PoluxBot</a></p>

<?php $botbio = "

    PoluxSuperShark gère le chat du serveur Minecraft et est le bot principal.
    Il est développé par PoluxSuperShark, Nelix et Qin, les fondateurs du serveur Discord.

    Egalement, de nouvelles fonctionnalités au bot vont venir prochainement,
    comme la modération, les jeux/fun, l'IA et + !

    Site web : https://poluxsupershark.net/discord
    YouTube : https://www.youtube.com/@PoluxSuperShark

" ?>

<div class="profile-popup" id="bot-popup">
    <img src="https://yt3.googleusercontent.com/x0Xu_vrFiwMiE9rLuTSrhmJ1Vm5xi84wk9C1koJSCheu5N49VQs0WXg9qWn_SJcezeHdjnSl=s88-c-k-c0x00ffffff-no-rj" alt="Avatar Bot">
    <h3>@PoluxBot</h3>
    <p>Bot Discord multifonction</p>
    <p>Status : en ligne</p>
    <p>ID : 1474093426067771627</p>
    <?php echo $botbio ?>
</div>


<!-- Bot profile -->
<script>
    const link = document.getElementById('bot-link');
    const popup = document.getElementById('bot-popup');

    link.addEventListener('click', function(e) {
        e.preventDefault(); // empêche le lien classique
        // toggle affichage
        if (popup.style.display === 'block') {
        popup.style.display = 'none';
        } else {
            popup.style.display = 'block';
            // placer le popup proche du lien
            const rect = link.getBoundingClientRect();
            popup.style.top = rect.bottom + window.scrollY + 'px';
            popup.style.left = rect.left + window.scrollX + 'px';
        }
    });

    // cacher le popup si clic à l'extérieur
    document.addEventListener('click', function(e) {
        if (!popup.contains(e.target) && e.target !== link) {
            popup.style.display = 'none';
        }
    });
</script>

<p>PoluxBot est un bot Discord qui gère le chat du serveur Minecraft et le projet Minecraft.
    <br> Il est développé avec soins et amour par <code>PoluxSuperShark</code>, <code>Nelix</code> et <code>Qin0</code>. <br>
    Il gère l'intelligence artificielle, la modération, les jeux, le fun etc. <br>
    Constament mis à jour, le bot rend le serveur Discord de <code>PoluxSuperShark</code> vivant et pratique.
</p>

<br>

<p>Le profil du bot :</p>
<img src="../../assets/screens/poluxbot.png" alt="Le bot du serveur Discord" width="20%" height="20% style="margin-top: -40rem; border-radius: 5px;">
