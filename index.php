<?php

include('config/autoload.php');

include('config/db.php');

$manager = new CharactersManager($db);

if(isset($_POST["create"]) && isset($_POST["name"])){
    $name = new Character(["nom" => $_POST["name"]]);

    if(!$character->valideName()){
        $message = 'This name is invalid.';
        unset($character);
    }elseif($manager->exists($character->name())){
        $message = "The character's name is already taken.";
        unset($character);
    }else {
        $manager->add($character);
    }
}
elseif(isset($_POST["use"]) && isset($_POST["name"])){
    if($manager->exists($_POST["name"])){
        $character = $manager->get($_POST["name"]);
    }
    else{
        $message = 'This character doesn\'t exist';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link rel="stylesheet" href="style.css">
    <title>TP : Mini jeu de combat</title>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col l5 offset-l4">
                <h3>Fight mini game</h3>
            </div>
        </div>
        <div class="row">
            <div class="col s12 m6 offset-m3 l5 offset-l3">
                <form action="" method="post">
                    <p>
                        <input type="text" name="name" class="white-text" placeholder="Name" maxlength="50">             
                        <input type="submit" class="btn waves-effect waves-light blue darken-3 right" value="Create this character" name="create">
                        <input type="submit" class="btn waves-effect waves-light amber darken-4 right" value="Use this character" name="use">
                    </p>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>
</html>