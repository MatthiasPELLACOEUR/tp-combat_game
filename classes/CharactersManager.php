<?php

class CharactersManager
{
    private $db;
    
    public function __construct(PDO $db)
    {
        $this->setDb($db);
    }

    public function add(Character $character)
    {
        // request insertion

        $insertChar = $this->db->prepare('INSERT INTO personnages(nom) VALUES (:nom)');
        $insertChar->bindValue(':nom', $character->name());
        $insertChar->execute();

        $character->hydrate([
            'id' => $this->db->lastInsertId(),
            'degats' => 0,
        ]);
    }

    public function count()
    {
        //request count 

        return $this->db->query('SELECT COUNT(*) FROM personnages')->fetchColumn(PDO::FETCH_ASSOC);
    }

    public function delete(Character $character)
    {
        //delete request

        $this->db->exec('DELETE FROM personnages WHERE id ='. $character->id());
    }

    public function exists($info)
    {
        // Si le paramètre est un entier, on veut récupérer le personnage avec son identifiant.
        // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.
    
        // Sinon, on veut récupérer le personnage avec son nom.
        // Exécute une requête de type SELECT avec une clause WHERE, et retourne un objet Personnage.

        if(is_int($info)){
            return(bool) $this->db->query('SELECT COUNT(*) FROM personnages WHERE id = '.$info)->fetchColumn();
        }
        $insertChar = $this->db->prepare('SELECT COUNT(*) FROM personnages WHERE nom = :nom');
        $insertChar->execute([':nom' => $info]);
        return (bool) $insertChar->fetchColumn();
    }

    public function get($info)
    {
        if(is_int($info)){
            $charactersStatement = $this->db->query('SELECT id, nom, degats FROM personnages WHERE id ='.$info);
            $characterRow = $charactersStatement->fetch(PDO::FETCH_ASSOC);

            return new Character($characterRow);
        }
        else{
            $reqCharacters = $this->db->prepare('SELECT id, nom, degats FROM personnages WHERE nom = :nom');
            $reqCharacters->execute([':nom' => $info]);

            return new Character($reqCharacters->fetch(PDO::FETCH_ASSOC));
        }
    }

    public function getList($name)
    {
        // Retourne la liste des personnages dont le nom n'est pas $nom.
        // Le résultat sera un tableau d'instances de Personnage.

        $characters = [];

        $reqList = $this->db->prepare('SELECT id, nom, degats FROM personnages WHERE nom <> :nom ORDER BY nom');
        $reqList->execute([':nom' => $name]);

        while ($characterRow = $reqList->fetch(PDO::FETCH_ASSOC)){
            $characters[] = new Character($characterRow);
        }

        return $characters;
    }

    public function update(Character $character)
    {
        // Prépare une requête de type UPDATE.
        // Assignation des valeurs à la requête.
        // Exécution de la requête.

        $reqUpdate = $this->db->prepare('UPDATE personnages SET degats = :degats WHERE id = :id');

        $reqUpdate->bindValue(':degats', $character->damages(), PDO::PARAM_INT);
        $reqUpdate->bindValue(':id', $character->id(), PDO::PARAM_INT);

        $reqUpdate->execute();
    }

    public function setDb(PDO $db)
    {
        $this->db = $db;
    }

}