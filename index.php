<?php

// On enregistre notre autoload.
/* function chargerClasse($classname) {
    require $classname.'.php';
  }

  spl_autoload_register('chargerClasse');*/




// On fait appel à la classe Personnage
require 'classes/Personnage.php';
// On fait appel à la classe PersonnagesManager
require 'classes/PersonnagesManager.php';

session_start(); // On appelle session_start() 

if (isset($_GET['deconnexion'])) {
  session_destroy();
  header('Location: .');
  exit();
}

// On fait appel à la connexion à la bdd
require 'config/autoload.php';

// On fait appel à le code métier
require 'combat.php';
?>
<!DOCTYPE html>
<html>

<head>
  <title>🥋Vs🥋 Fight ! </title>

  <meta charset="utf-8" />
</head>

<body>
  <p>Nombre de personnages créés : <?= $manager->count() ?></p>
  <?php
  // On a un message à afficher ?
  if (isset($message)) {
    echo '<b>', $message, '</b>'; // Si oui, on l'affiche.
  }
  // Si on utilise un personnage (nouveau ou pas).
  if (isset($perso)) {
  ?>
    <p><a href="?deconnexion=1">Déconnexion</a></p>

    <fieldset>
      <legend>Mes informations</legend>
      <p>
        Nom : <?= htmlspecialchars($perso->nom()) ?><br />
        Dégâts : <?= $perso->degats() ?><br />
        Niveau : <?= $perso->niveau() ?><br />
        Experience <?= $perso->experience() ?>
      </p>
    </fieldset>

    <fieldset>
      <legend>Qui frapper ?</legend>
      <p>
        <?php
        $persos = $manager->getList($perso->nom());
        if (empty($persos)) {
          echo 'Personne à frapper !';
        } else {
          foreach ($persos as $unPerso) {
            echo '<a href="?frapper=', $unPerso->id(), '">',
              htmlspecialchars($unPerso->nom()),'</a> 
              (dégâts : ', $unPerso->degats(),',
              niveau : ', $unPerso->niveau(),', 
              experience : ', $unPerso->experience(),'
              )<br />';
          }
        }
        ?>
      </p>
    </fieldset>
  <?php
  }
  // Sinon on affiche le formulaire de création de personnage
  else {
  ?>
    <form action="" method="post">
      <p>
        Nom : <input type="text" name="nom" maxlength="50" />
        <input type="submit" value="Créer ce personnage" name="creer" />
        <input type="submit" value="Utiliser ce personnage" name="utiliser" />
      </p>
    </form>

  <?php } ?>

</body>

</html>
<?php
// Si on a créé un personnage, on le stocke dans une variable session afin d'économiser une requête SQL.
if (isset($perso)) {
  $_SESSION['perso'] = $perso;
}
