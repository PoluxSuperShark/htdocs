<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Équipe</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body {
      background-color: #f4f6f9;
    }

    .team-card {
      border: none;
      border-radius: 15px;
      transition: transform 0.3s ease, box-shadow 0.3s ease;
      text-align: center;
      padding: 25px 15px;
    }

    .team-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }

    .team-img {
      width: 120px;
      height: 120px;
      object-fit: cover;
      border-radius: 50%;
      margin-bottom: 15px;
      border: 4px solid #5865F2; /* Couleur style Discord */
    }

    .team-role {
      font-size: 0.9rem;
      color: gray;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>

<div class="container mt-5">

    <?php include "../../components/navbar.php"; ?>

<br> 


  <div class="row g-4">

    <h1>Empereurs</h1>
    <!-- Membre 1 -->
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card team-card shadow-sm">
        <img src="https://yt3.ggpht.com/x0Xu_vrFiwMiE9rLuTSrhmJ1Vm5xi84wk9C1koJSCheu5N49VQs0WXg9qWn_SJcezeHdjnSl=s600-c-k-c0x00ffffff-no-rj-rp-mo"
             class="team-img"
             alt="Photo">
        <h5 class="fw-bold">PoluxSuperShark</h5>
        <div class="team-role">Fondateur et empereur du serveur</div>
        <p class="text-muted">Créateur du projet</p>
      </div>
    </div>

    <!-- Membre 2 -->
    <div class="col-12 col-sm-6 col-lg-4">
      <div class="card team-card shadow-sm">
        <img src="https://yt3.googleusercontent.com/t2jt8T57Bdi83goN7uMhR2NRYUJQFtR_eP2cBm3lB1tJjoQbiEXilhfsdFQVBXSkPX76gh0Yqks=s160-c-k-c0x00ffffff-no-rj"
             class="team-img"
             alt="Photo">
        <h5 class="fw-bold">Luna 91</h5>
        <div class="team-role">Impératrice du serveur</div>
        <p class="text-muted">Cheff de projet principale</p>
      </div>
    </div>


  </div>
</div>

</body>
</html>