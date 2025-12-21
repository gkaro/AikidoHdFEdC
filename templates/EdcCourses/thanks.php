<div style="margin-top:5%">
    Merci pour votre participation.
    <br>
    Vous allez recevoir un mail de confirmation de votre envoi.
    <br><br>
    A très bientôt sur les tatamis!
</div>

<?php
    // Infos de redirection
    $delai=5; // Délai en secondes
    $url='https://aikido-hdf.fr/'; // Adresse vers laquelle rediriger le visiteur
    // Redirection dans x secondes
    header('Refresh: '.$delai.';url='.$url);
?>