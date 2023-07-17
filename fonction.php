<?php

// Connexion à la base de données (une seule connexion réutilisée)
$connectMe = null;

function connexion() {
    global $connectMe;
    if ($connectMe === null) {
        try {
            $connectMe = new PDO('mysql:host=localhost;dbname=budget','root','');
        } catch (Exception $e) {
            die('Am na fou dokhoul d'.$e->getMessage());
        }
    }
    return $connectMe;
}

// Calcul du budget
function calculBudget() {
    global $connectMe;
    $connectMe = connexion();
    $req = $connectMe->query('SELECT SUM(montant) as budget FROM revenu');
    $resultat = $req->fetch();
    return $resultat['budget'];
}

echo "Budget total : " . calculBudget();

// Calcul de la dépense
function calculDepense() {
    global $connectMe;
    $connectMe = connexion();
    $req = $connectMe->query('SELECT SUM(montant) as depense FROM depense');
    $donnees = $req->fetch();
    $resultat = $donnees['depense'];
    return $resultat;
}

echo "Dépense totale : " . calculDepense();

// Calcul du solde
function calculSolde() {
    global $connectMe;
    $budget = calculBudget();
    $depense = calculDepense();
    $solde = $budget - $depense;
    return $solde;
}

echo "Solde : " . calculSolde();


//Ajout de depenses
$detecteur=null;
function ajoutDepense($titreDepense,$montantDepense){
    global $connectMe;
    global $detecteur;
    $connectMe = connexion();
    $req=$connectMe->prepare('INSERT INTO depense (titre,montant) VALUES (?,?)');
    if(isset($titreDepense) && isset($montantDepense) && $montantDepense>0)
        {
            // $req->execute([$req($titreDepense,$montantDepense)]);
         $detecteur = $req->execute([$titreDepense, $montantDepense]);
           var_dump($detecteur);
        }
}
ajoutDepense('finacement',500000);  

?>
