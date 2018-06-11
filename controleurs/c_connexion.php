<?php
/**
 * Gestion de la connexion
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
if (!$uc) {
    $uc = 'demandeconnexion';
}

switch ($action) {
case 'demandeConnexion':
    include 'vues/v_connexion.php';
    break;
case 'valideConnexion':
    $login = filter_input(INPUT_POST, 'login', FILTER_SANITIZE_STRING);
    $mdp = filter_input(INPUT_POST, 'mdp', FILTER_SANITIZE_STRING);
    
    //Pour déterminer si l'utilisateur est un visiteur ou un comptable on recherche d'abord ses id dans la bdd comptable
    $comptable = $pdo->getInfosComptable($login, $mdp);
    
    if ( is_array($comptable) ){
        
        $id = $comptable['id'];
        $nom = $comptable['nom'];
        $prenom = $comptable['prenom'];
        $poste = "Comptable";
        
    }else{
        //Si il n'est pas dans les comptables on regarde chez les visiteurs.
        $visiteur = $pdo->getInfosVisiteur($login, $mdp);
        if ( is_array($visiteur)) {
            
            $id = $visiteur['id'];
            $nom = $visiteur['nom'];
            $prenom = $visiteur['prenom'];
            $poste = "Visiteur";
        } 
    }
    
   
    //Au sortir de cette étape si on n'a pas encore défini de variable 'id' cela signifie que les identifiants sont incorrect
    if( !isset($id) ){
        
        ajouterErreur('Mauvais login ou mot de passe');
        include 'vues/v_erreurs.php';
        include 'vues/v_connexion.php';
        
    } else {
        
        //Si on l'a bien défini on peut se connecter
        connecter($id, $nom, $prenom, $poste);
        header('Location: index.php');
    }
    
    break;
default:
    include 'vues/v_connexion.php';
    break;
}
