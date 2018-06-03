<?php
/**
 * Vue de validation des frais.
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Kévin Delcourt
 */


//Cas où il n'y a aucune fiche pour la combinaison mois/jour sélectionnée
if( (count($lesFraisForfait) == 0)||($lesInfosFicheFrais['idEtat'] != 'CL' ) ){ ?>

    <h2>Il n'y a aucune fiche à valider pour ce visiteur à ce mois.</h2>

<?php }else{ ?>


<!-- Elements forfaitisés -->

<div class="row">    
    <h3>Eléments forfaitisés</h3>
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=validerFrais&action=validerMajFraisForfait" 
              role="form">
            <fieldset>       
                <?php
                foreach ($lesFraisForfait as $unFrais) {
                    $idFrais = $unFrais['idfrais'];
                    $libelle = htmlspecialchars($unFrais['libelle']);
                    $quantite = $unFrais['quantite']; ?>
                    <div class="form-group">
                        <label for="idFrais"><?php echo $libelle ?></label>
                        <input required type="text" id="idFrais" 
                               name="lesFrais[<?php echo $idFrais ?>]"
                               size="10" maxlength="5" 
                               value="<?php echo $quantite ?>" 
                               class="form-control">
                    </div>
                    <?php
                }
                ?>
                <input type="text" name="idVisiteur" hidden value="<?php echo $idVisiteur ?>">
                <input type="text" name="leMois" hidden value="<?php echo $leMois ?>">
                
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-warning" type="reset">Réinitialiser</button>
            </fieldset>
        </form>
    </div>
</div>

<hr> <!-- Elements forfaitisés -->

<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Descriptif des éléments hors forfait</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="date">Date</th>
                    <th class="libelle">Libellé</th>  
                    <th class="montant">Montant</th>  
                    <th class="action">Action</th> 
                </tr>
            </thead>  
            <tbody>
            <?php
            foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                $libelle = htmlspecialchars($unFraisHorsForfait['libelle']);
                $date = $unFraisHorsForfait['date'];
                $montant = $unFraisHorsForfait['montant'];
                $id = $unFraisHorsForfait['id']; ?>  
                
                <form action="index.php?uc=validerFrais&action=actionMajFraisHorsForfait" 
                            method="post" role="form">
                    <tr>
                        <td> 
                            
                            <input required type="text" name="dateFrais" class="form-control" value="<?php echo $date ?>">
                        
                        </td>
                        <td> 
                        
                            <input required type="text" name="libelle" class="form-control" value="<?php echo $libelle ?>">
                        
                        </td>
                        <td>
                            
                            <input required type="text" name="montant"  class="form-control" value="<?php echo $montant ?>">
                        
                        </td>
                        <td>
                            
                            <!-- Input non affiché qui permet de récupérer l'id du frais/visiteur/mois sur lequel on agis -->
                            <input type="text" name="idFrais" hidden value="<?php echo $id ?>">
                            <input type="text" name="idVisiteur" hidden value="<?php echo $idVisiteur ?>">
                            <input type="text" name="leMois" hidden value="<?php echo $leMois ?>">
                            
                            <button class="btn btn-success" name="corriger" type="submit">Corriger</button>
                            <button class="btn btn-danger" name="refuser" type="submit">Refuser</button>
                            <button class="btn btn-info" name="reporter" type="submit">Reporter</button>
                            <button class="btn btn-warning" type="reset">Réinitialiser</button>
                        
                        
                        </td>
                    </tr>
                
                </form>
                <?php
            }
            ?>
            </tbody>  
        </table>
    </div>
</div>

<hr> <!-- Pièces justificatives -->

<div class="row">    
    
    <div class="col-md-4">
        <form method="post" 
              action="index.php?uc=validerFrais&action=validerMajNBJust" 
              role="form">
                  
                    <input type="text" name="idVisiteur" hidden value="<?php echo $idVisiteur ?>">
                    <input type="text" name="leMois" hidden value="<?php echo $leMois ?>">
                
                    <div class="form-group">
                        <label for="nbPJFrais">Pièces justificatives</label>
                        <input required type="text" 
                               name="nbPJ"
                               size="10" maxlength="5" 
                               value="<?php echo $nbJustificatifs; ?>" 
                               class="form-control">
                    </div>
                    
                <button class="btn btn-success" type="submit">Corriger</button>
                <button class="btn btn-warning" type="reset">Réinitialiser</button>
            
        </form>
    </div>
</div>

<hr> <!-- Validation de la fiche entière -->


<form method="post" 
              action="index.php?uc=validerFrais&action=validerFiche" 
              role="form">
                  
                <input type="text" name="idVisiteur" hidden value="<?php echo $idVisiteur ?>">
                <input type="text" name="leMois" hidden value="<?php echo $leMois ?>">
                
                <button class="btn btn-success btn-lg" type="submit">Valider et mettre en paiement la fiche de frais</button>
            
</form><br>

<?php } 