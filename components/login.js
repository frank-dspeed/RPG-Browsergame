//     <title>Login</title>
export default `<div class="LoginContainer">
<form action="/rpg.php" method="POST">
  <label>Benutzername :</label><br>
  <input type="text" id="bname" name="bname"><br>
  <label>Passwort :</label><br>
  <input type="password" id="pname" name="pw"><br><br>
  <input type="submit" name="action" value="Einloggen"><input type="submit" name="action" value="Registrieren"><input type="reset" value="Zurücksetzen">
</form>
</div>`
