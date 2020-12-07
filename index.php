<!DOCTYPE html>
<html>

<head>
  <title>Propania</title>
  <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="rpgstyle.css">
  <script type="module">
  import login from '/components/login.js'
  <?php
  include("funktionen.php");
  if (!isset($_SESSION["Spieler"])) {
      //header('location: index.php');
      //even better would be to select game-component
      document.body.innerHTML = login;
  }
  ?>
  </script>
</head>


<body>
  <h1>Propania</h1>
  <game-component></game-component>
</body>

</html>