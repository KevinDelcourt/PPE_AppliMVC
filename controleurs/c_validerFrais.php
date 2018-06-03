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
        //On récupère la liste des visiteurs ayant une fiche à valider
        $lesVisiteurs = $pdo->getLesVisiteursPourValidation();
       
        //Et la liste des mois pour lesquels il a une fiche à valider
        $lesMois = $pdo->getLesMoisPourValidation();
        
        //Valeur absurde pour que dans les tests suivant on ne sélectionne aucun mois/visiteur dans la liste,
        // ainsi le premier sera naturellement sélectionné
        $moisASelectionner = '000000';
        $visiteurASelectionner = 'a00000';
        
        include 'vues/v_listeFichesValidation.php';
        break;
    case 'voirFrais':
        
        $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
        
        $lesVisiteurs = $pdo->getLesVisiteursPourValidation();
        $lesMois = $pdo->getLesMoisPourValidation();
        
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        
        include 'vues/v_listeFichesValidation.php';
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    
        include 'vues/v_validationFrais.php';

        break;
    case 'validerMajFraisForfait':
        
        
        $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
        
        $lesVisiteurs = $pdo->getLesVisiteursPourValidation();
        $lesMois = $pdo->getLesMoisPourValidation();
        
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        
        include 'vues/v_listeFichesValidation.php';
        
        $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);

        if (lesQteFraisValides($lesFrais)) {
            
            $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
            ajouterInfo('Les frais forfaitisés ont été mis à jour.');
            include 'vues/v_infos.php';
            
        } else {
            ajouterErreur('Les valeurs des frais doivent être numériques');
            include 'vues/v_erreurs.php';
        }
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    
        include 'vues/v_validationFrais.php';
        
        break;
    case 'actionMajFraisHorsForfait':
        
        $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
        
        $lesVisiteurs = $pdo->getLesVisiteursPourValidation();
        $lesMois = $pdo->getLesMoisPourValidation();
        
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        
        include 'vues/v_listeFichesValidation.php';
        
        
        $idFrais = filter_input(INPUT_POST, 'idFrais', FILTER_SANITIZE_STRING);
        $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_STRING);
        $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
        $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
        
        //Pour déterminer quelle action on veut faire on regarde sur quel bouton submit on a cliqué
        if ( filter_has_var(INPUT_POST, "corriger")) {
            
            valideInfosFrais($dateFrais, $libelle, $montant);

            if (nbErreurs() != 0) {
                include 'vues/v_erreurs.php';
            } else {
                $pdo->majFraisHorsForfait($idFrais , $libelle , $dateFrais , $montant );
                
                ajouterInfo('Le frais hors forfait a été mis à jour');
                include 'vues/v_infos.php';
            }
            
        }
        elseif ( filter_has_var(INPUT_POST, "reporter") ) {
            
            $pdo->reportFraisHorsForfait($idFrais,$idVisiteur,$mois);
            ajouterInfo('Le frais hors forfait a été reporté à la fiche du mois en cours pour ce visiteur.');
            include 'vues/v_infos.php';
        } 
        elseif ( filter_has_var(INPUT_POST, "refuser") ) {

            $libelle = substr("REFUSE-" . $libelle, 0, 30);
            
            $pdo->majLibelleHorsForfait($idFrais,$libelle);
            
            ajouterInfo('Le frais hors forfait a bien été refusé.');
            include 'vues/v_infos.php';
        } 
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    
        include 'vues/v_validationFrais.php';
        
        break;
    case 'validerMajNBJust':
        
        $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
        
        $lesVisiteurs = $pdo->getLesVisiteursPourValidation();
        $lesMois = $pdo->getLesMoisPourValidation();
        
        $moisASelectionner = $leMois;
        $visiteurASelectionner = $idVisiteur;
        
        include 'vues/v_listeFichesValidation.php';
        
        $nbMajJustificatifs = filter_input(INPUT_POST, 'nbPJ', FILTER_SANITIZE_STRING);
        
        if ( nbJustificatifValide($nbMajJustificatifs) ) {
            
            $pdo->majNbJustificatifs($idVisiteur, $leMois, $nbMajJustificatifs);
            
            ajouterInfo('Le nombre des pièces justificatives a été mis à jour.');
            include 'vues/v_infos.php';
            
        } else {
            ajouterErreur('La valeur du nombre de pièces justificatives doit être numérique.');
            include 'vues/v_erreurs.php';
        }
        
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur, $leMois);

        $nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
    
        include 'vues/v_validationFrais.php';
        
        break;
    case 'validerFiche':
        
        $idVisiteur = filter_input(INPUT_POST, 'idVisiteur', FILTER_SANITIZE_STRING);
        $leMois = filter_input(INPUT_POST, 'leMois', FILTER_SANITIZE_STRING);
        
        $lesVisiteurs = $pdo->getLesVisiteursPourValidation();
        $lesMois = $pdo->getLesMoisPourValidation();
        
        $moisASelectionner = '000000';
        $visiteurASelectionner = 'a00000';
        
        include 'vues/v_listeFichesValidation.php';
        
        $pdo -> majEtatFicheFrais($idVisiteur,$leMois,'VA');
        
        ajouterInfo('La fiche a été validée et mise en paiement, vous pouvez maintenant saisir le suivi du paiement de cette fiche.');
        include 'vues/v_infos.php';
        
        
        
        
        break;
}