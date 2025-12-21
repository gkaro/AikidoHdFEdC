
<script>
    $(document).ready(function(){
        $('select').formSelect();
    });
</script>

<h2 id="title">Modifier <?= $member->full_name; ?></h2>

<?php
   echo $this->Form->create($member);
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('last_name',['label' => 'Nom', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('first_name',['label' => 'Prénom', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('full_name',['label' => 'Nom complet', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s6">';
            echo $this->Form->control('dob',['label' => 'Date de naissance', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('gender',['options'=>['H'=>'H','F'=>'F','Autre'=>'Autre'],'label' => 'Sexe (H, F ou Autre', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('street',['label' => 'N° de rue et rue', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('zip',['label' => 'Code postal', 'class'=>"form-control"]);
        echo'</div>';   
        echo '<div class="col s4">';
            echo $this->Form->control('city',['label' => 'Ville', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s6">';
            echo $this->Form->control('email',['label' => 'Email', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s6">';
            echo $this->Form->control('phone',['label' => 'Téléphone', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col">';
            echo $this->Form->button(__('Sauvegarder'),['class'=>"form-control btn red darken-4"]);
        echo'</div>';
        echo '<div class="col">';
            echo $this->Html->link('Annuler', ['controller'=>'edc-members','action' => 'view',$member->id],['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';
    echo $this->Form->end();
?>

<script>
$( "#first-name" ).focusout(function(){
    var lastname = $('#last-name').val();
    var firstname = $('#first-name').val();
    var name = lastname + " " + firstname;
    $('#full-name').val(name);
});
</script>