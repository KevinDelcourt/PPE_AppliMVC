<?php
/**
 * Vue Infos - Sert à informer l'utilisateur de la réussite de sa requête
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Kévin Delcourt   
 */
?>
<div class="alert alert-success" role="alert">
    <?php
    foreach ($_REQUEST['info'] as $info) {
        echo '<p>' . htmlspecialchars($info) . '</p>';
    }
    ?>
</div>