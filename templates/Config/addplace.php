<?php   
    echo $this->Form->create($place);
   
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('name',['label' => 'Lieu de stage','class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
   
    
    echo '<div class="row">';
        echo '<div class="col">';
            echo $this->Form->button(__('Sauvegarder'),['class'=>'form-control btn red darken-4']);
        echo'</div>';
        echo '<div class="col">';
            echo $this->Html->link('Annuler', ['controller'=>'config','action' => 'index'],['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';

    echo $this->Form->end();
?>
</div>
