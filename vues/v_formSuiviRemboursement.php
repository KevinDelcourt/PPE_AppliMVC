<?php
/**
 * Vue du formulaire de passage d'une fiche à l'état remboursé.
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Kévin Delcourt
 */
?>
<hr>
<div class="row">
    <div class="col-md-12">

        <form method="post" 
              action="index.php?uc=suivrePaiements&action=rembourserFiche" 
              role="form">
                  
                <input type="text" name="idVisiteur" hidden value="<?php echo $idVisiteur ?>">
                <input type="text" name="leMois" hidden value="<?php echo $leMois ?>">
                
                <button class="btn btn-success btn-lg" type="submit">Confirmer le remboursement de cette fiche de frais</button>
            
        </form><br>

    </div>
</div>
