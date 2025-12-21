<script>
    $(document).ready(function(){
        $('select').formSelect();
    });
</script>

<h2 id="title">Modifier l'inscription pour <?= $sub->edc_member->full_name; ?></h2>

<?php
   echo $this->Form->create($sub);
    echo '<div class="row">';
        echo '<div class="col s6">';
            echo $this->Form->control('idseason', ['options' => $seasonTable,'label'=>'Saison', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s2">';
            echo $this->Form->hidden('idmember',['value' => $sub->edc_member->id]);
        echo'</div>';
       
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s3">';
            echo $this->Form->control('club_number',['options' => $optionsClubs,'label' => 'Nom du club', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s3">';
            echo $this->Form->control('grade',['options' => $optionsGrades,'label' => 'Grade actuel', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s3">';
            echo $this->Form->control('degree',['options' => $optionsDegrees,'label' => 'Avez-vous un diplôme d\'enseignant ?', 'class'=>"form-control"]);
        echo'</div>';
         echo '<div class="col s3">';
            echo $this->Form->control('edc', ['options' => ["oui"=>"oui", "non"=>"non"],'label'=>'EdC', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    
    echo '<div class="row">';
        echo '<div class="col">';
            echo $this->Form->button(__('Sauvegarder'),['class'=>"form-control btn red darken-4"]);
        echo'</div>';
        echo '<div class="col">';
            echo $this->Html->link('Annuler', ['controller'=>'edc-members','action' => 'view',$sub->edc_member->id],['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';
    echo $this->Form->end();
?>