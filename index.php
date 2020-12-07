<!DOCTYPE html>
<html>
<!-- 
  Flow:
  Look if we got a PHP Session if we did not got that try to get the value of our session
  When we do not got a PHP Session we get One

  Look if we are Logged in if we did not got that we need to render the login screen

  Get Current Game Component and display it.




-->
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