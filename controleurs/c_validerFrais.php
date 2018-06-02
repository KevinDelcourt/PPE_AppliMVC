<?php
/**
 * Validation des frais
 *
 * Vérifier si il s'agit de la première validation du mois, cloturer les fiches du mois passé si ce n'est pas le cas.
 * Permettre au comptable de choisir parmis tout les visiteurs une fiche de frais à valider
 * 
 *
 * @category  PPE
 * @package   GSB
 * @author    Kévin DELCOURT
 */


$mois = getMois(date('d/m/Y'));

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

//Avant tout on regarde si on a besoin de faire la cloture du mois dernier
if( $pdo->estPremierValidationMois($mois)){
    
    //Cloture de toutes les fiches en cours du mois précédent
    $pdo -> majEtatGroupee( 'CR' , getMoisAnterieur($mois), 'CL' );
    
}

switch ($action){
    
    //Demander au comptable de choisir une fiche 
    case 'choixFiches':
        
        break;
    case '  ':
        
        break;
}