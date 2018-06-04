<?php
/**
 * Vue Liste des fiches mises en paiement
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Kévin Delcourt
 */
?>

<?php 
//Cas où il n'y a aucune fiche à valider
if( count($lesMois) == 0 ){ ?>

    <h2>Il n'y a aucune fiche mise en paiement!</h2>

<?php }else{ ?>

<h2>Suivre le paiement d'une fiche.</h2>
<div class="row">
    <div class="col-md-4">
        <h3>Sélectionner une fiche : </h3>
    </div>
    <div class="col-md-4">
        <form action="index.php?uc=suivrePaiements&action=voirFiche" 
              method="post" role="form">
            <div class="form-group">
                
                <label for="lstMois" >Mois : </label>
                <select id="lstMois" name="lstMois" class="form-control">
                    
                    <?php
                    foreach ($lesMois as  $unMois) {
                        $uneMois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        
                        if ($uneMois == $moisASelectionner) {
                            ?>
                            <option selected value="<?php echo $uneMois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $uneMois ?>">
                                <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
                
                <br>
                
                <label for="lstVisiteurs" >Visiteur : </label>
                <select id="lstVisiteurs" name="lstVisiteurs" class="form-control">
                    
                    <?php
                    foreach ($lesVisiteurs as $unVisiteur) {
                        $id = $unVisiteur['id'];
                        $nom = $unVisiteur['nom'];
                        $prenom = $unVisiteur['prenom'];
                        
                        if ($id == $visiteurASelectionner) {
                            ?>
                            <option selected value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $id ?>">
                                <?php echo $nom . ' ' . $prenom ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>


            </div>
            <input id="ok" type="submit" value="Valider" class="btn btn-success" 
                   role="button">
            <input id="annuler" type="reset" value="Réinitialiser" class="btn btn-warning" 
                   role="button">
        </form>
    </div>
</div>
<hr>
<?php } 