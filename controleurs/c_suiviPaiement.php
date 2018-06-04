<?php
/**
 * Suivi des paiement
 *
 * Permettre au comptable de suivre le paiement d'une fiche de paie en respectant le cas d'utilisation "suivre le paiement fiche de frais"
 * 
 * @category  PPE
 * @package   GSB
 * @author    Kévin DELCOURT
 */

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

switch($action){
    case 'choixFiches':
        
        //On récupère la liste des visiteurs/mois qui ont une fiche mises en paiement
        $lesVisiteurs = $pdo->getLesVisiteursAvecEtat('VA');
        $lesMois = $pdo->getLesMoisAvecEtat('VA');
        
        $moisASelectionner = '000000';
        $visiteurASelectionner = 'a00000';
        
        include 'vues/v_listeFichesSuivi.php';
        
        break;
    case 'voirFiche':
        
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        
        $lesVisiteurs = $pdo->getLesVisiteursAvecEtat('VA');
        $lesMois = $pdo->getLesMoisAvecEtat('VA');
        
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        
        include 'vues/v_listeFichesSuivi.php';
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);
        $numAnnee = substr($leMois, 0, 4);
        $numMois = substr($leMois, 4, 2);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        
        
        //on vérifie que la fiche sélectionnée soit bien mise en paiement
        if( $lesInfosFicheFrais['idEtat'] == 'VA' ){
            include 'vues/v_etatFrais.php';
            include 'vues/v_formSuiviRemboursement.php';
        }else{
            
            ajouterErreur('Aucune fiche n\'est mise en paiement pour ce visiteur pour ce mois.');
            include 'vues/v_erreurs.php';
        }
        
        break;
    case 'rembourserFiche':
        
        $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
        
        $lesVisiteurs = $pdo->getLesVisiteursAvecEtat('VA');
        $lesMois = $pdo->getLesMoisAvecEtat('VA');
        
        $moisASelectionner = '000000';
        $visiteurASelectionner = 'a00000';
        
        include 'vues/v_listeFichesSuivi.php';
        
        $pdo -> majEtatFicheFrais($idVisiteur,$leMois,'RB');
        
        ajouterInfo('La fiche est maintenant marquée comme remboursée.');
        include 'vues/v_infos.php';
        
        break;
}