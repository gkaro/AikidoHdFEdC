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

<h2 id="title">Renouveler l'inscription pour <?= $member->full_name; ?></h2>

<?php
    echo $this->Form->create($newSub);
    echo '<div class="row">';
        echo '<div class="col s6">';
            echo $this->Form->control('idseason', ['options' => $seasonTable,'label'=>'Saison', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s2">';
            echo $this->Form->hidden('id_member',['value'=>$member->id]);
        echo'</div>';
        echo '<div class="col s2">';
            echo $this->Form->control('age',['class'=>"form-control age"]);
        echo'</div>';
        if($member->dob != NULL){$dob = $member->dob?->format('m-d-Y');}else{$dob="";};
        echo '<div class="col s2" id="dob" data="'. $dob .'">';
       
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s3">';
            echo $this->Form->control('club_number',['options'=>$optionsClubs,'val'=>$member->edc_subscriptions[0]?->club_number,'label' => 'Nom du club','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s3">';
            echo $this->Form->control('grade',['options' => $optionsGrades,'val'=>$member->edc_subscriptions[0]?->edc_grade?->id,'label' => 'Grade actuel', 'class'=>"form-control"]);
        echo '</div>';
        echo '<div class="col s3">';
            echo $this->Form->control('degree',['options' => $optionsDegrees,'val'=>$member->edc_subscriptions[0]?->degree,'label' => 'Diplôme d\'enseignant', 'class'=>"form-control"]);
        echo '</div>';
        echo '<div class="col s3">'; 
            $optionsedc = ['oui' => 'oui', 'non' => 'non'];
            echo $this->Form->control('edc', ['options' => $optionsedc,'val'=>'non','label' => 'Ecole des Cadres', 'class'=>"form-control"]);
        echo '</div>';
    echo '</div>'; 
    echo '<div class="row">';
        echo '<div class="col">';
            echo $this->Form->button(__('Sauvegarder'),['class'=>'form-control btn red darken-4']);
        echo '</div>';
        echo '<div class="col">';
            echo $this->Html->link('Annuler', ['controller'=>'edc-members','action' => 'view',$member->id],['class'=>"form-control btn grey lighten-1"]);
        echo '</div>';
    echo '</div>';
    echo $this->Form->end();
?>

<!--calcul de l'âge-->
<script>

$(function() {
    var dob = $('#dob').attr('data');
    dob = new Date(dob);
    console.log(dob);
    var today = new Date();
    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    $('#age ').val(age);
});
</script>