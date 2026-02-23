<?php
function detectOS($userAgent) {
    if (stripos($userAgent, 'Windows') !== false) {
        return 'windows';
    } elseif (stripos($userAgent, 'Mac') !== false) {
        return 'mac';
    } elseif (stripos($userAgent, 'Linux') !== false) {
        return 'linux';
    }
    return 'unknown';
}

$os = detectOS($_SERVER['HTTP_USER_AGENT']);
?>

<div class="container mt-5 text-center">

    <h1 class="mb-3">Télécharger le launcher</h1>
    <p class="text-muted">Disponible pour Windows, macOS et Linux</p>

    <?php
    $downloadLink = "#";
    $downloadText = "Télécharger";

    if ($os === 'windows') {
        $downloadLink = "downloads/launcher_setup.exe";
        $downloadText = "Télécharger pour Windows";
    } elseif ($os === 'mac') {
        $downloadLink = "downloads/launcher_mac.dmg";
        $downloadText = "Télécharger pour macOS";
    } elseif ($os === 'linux') {
        $downloadLink = "downloads/launcher_linux.AppImage";
        $downloadText = "Télécharger pour Linux";
    }
    ?>

    <a id="mainDownloadBtn"
       href="<?= $downloadLink ?>"
       class="btn btn-primary btn-lg mt-3">
        <?= $downloadText ?>
    </a>

    <div class="mt-4">
        <p class="text-muted mb-2">Autres systèmes :</p>
        <a href="downloads/launcher_setup.exe" class="btn btn-outline-secondary m-1">Windows</a>
        <a href="downloads/launcher_mac.dmg" class="btn btn-outline-secondary m-1">macOS</a>
        <a href="downloads/launcher_linux.AppImage" class="btn btn-outline-secondary m-1">Linux</a>
    </div>

</div>

<script>
    // Ajustement côté navigateur (plus fiable)
    const platform = navigator.platform.toLowerCase();
    const btn = document.getElementById("mainDownloadBtn");

    if (platform.includes("win")) {
        btn.href = "downloads/launcher_setup.exe";
        btn.textContent = "Télécharger pour Windows";
    } 
    else if (platform.includes("mac")) {
        btn.href = "downloads/launcher_mac.dmg";
        btn.textContent = "Télécharger pour macOS";
    } 
    else if (platform.includes("linux")) {
        btn.href = "downloads/launcher_linux.AppImage";
        btn.textContent = "Télécharger pour Linux";
    }
</script>