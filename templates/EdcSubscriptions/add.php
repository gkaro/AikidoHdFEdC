<script>
    $(document).ready(function(){
        $('select').formSelect();
    });
</script>
<div id="top">
    <?= $this->Html->link(
        '<i class="material-icons left">keyboard_return</i>Retour à la liste des inscrits',
        ['controller' => 'EdcMembers', 'action' => 'index'],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
    ) ?>
</div>

<h2 id="title">Ajouter une inscription</h2>
<?php
    echo $this->Form->create($sub);

    echo '<div class="row">';
        echo ' <div class="col s4">';
            echo $this->Form->control('id_season', ['options' => $seasons,'label'=>'Saison', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s0">';
            echo $this->Form->hidden('type',['value' => 1, 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.last_name',['label' => 'Nom', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.firs_tname',['label' => 'Prénom', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.full_name',['label' => 'Nom complet', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s2">';
            echo $this->Form->control('edc_member.dob',['label' => 'Date de naissance', 'class'=>"form-control datepicker"]);
        echo'</div>';
        echo '<div class="col s1">';
            echo $this->Form->control('age',['class'=>"form-control age"]);
        echo'</div>';
        echo '<div class="col s1">';
            $optionsGender = ['H'=>'H','F'=>'F','Autre'=>'Autre'];
            echo $this->Form->control('edc_member.gender',['options' => $optionsGender,'label' => 'Genre', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.email',['class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.phone',['label' => 'Téléphone', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('club_number',['options' => $optionsClubs,'label' => 'Nom du club', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('grade',['options' => $optionsGrades,'label' => 'Grade actuel', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('degree',['options' => $optionsDegrees,'label' => 'Diplôme d\'enseignant', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">'; 
            $optionsedc = ['oui' => 'oui', 'non' => 'non'];
            echo $this->Form->control('edc',  ['options' => $optionsedc,'label' => 'Ecole des Cadres', 'class'=>"form-control"]);
        echo '</div>';
    echo'</div>';
    echo '<div class="row gx-5">';
        echo '<div class="col">';
            echo $this->Form->button(__('Sauvegarder'), ['class'=>'form-control btn red darken-4']);
        echo'</div>';
        echo '<div class="col">';
            echo $this->Html->link('Annuler', ['controller'=>'edc-members','action' => 'index'],['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';
    echo $this->Form->end();
?>




<script>
/**calcul de l'âge */
$( "#edc-member-dob" ).focusout(function(){
    var dob = $('#edc-member-dob').val();
    dob = new Date(dob);
    var today = new Date();
    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    $('#age ').val(age);
});

/**rempli le champ Nom avec nom de famille et prénom */
$( "#edc-member-firstname" ).focusout(function(){
    var lastname = $('#edc-member-lastname').val();
    var firstname = $('#edc-member-firstname').val();
    var name = lastname + " " + firstname;
    $('#edc-member-name').val(name);
});
</script>