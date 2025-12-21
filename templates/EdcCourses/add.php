<script>
   $(document).ready(function(){
      $('select').formSelect();/**pour les listes déroulantes dans le formulaire */
    });
</script>

<div id="top">
    <?= $this->Html->link(
        '<i class="material-icons left">keyboard_return</i>Retour à la liste des stages',
        ['controller' => 'EdcCourses', 'action' => 'index'],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
    ) ?>
</div>

<h2 id="title">Ajouter un stage</h2>
<?php
    echo $this->Form->create($course);
    
    echo '<div class="row gx-5">';
      echo '<div class="col s4">';
        echo $this->Form->control('id_season', ['options' => $seasons,'label'=>'Saison', 'class'=>"form-control"]);
      echo'</div>';
      echo ' <div class="col s4">';
        echo $this->Form->control('id_type',['options' => $types,'label' => 'Type de stage', 'class'=>"form-control"]);
      echo'</div>';
      echo ' <div class="col s4">';
        echo $this->Form->control('date',['class'=>"form-control"]);
      echo'</div>';
    echo'</div>';


    echo '<div class="row gx-5">';
      echo '<div class="col s4">';
        echo $this->Form->control('id_place',['options' => $places,'label' => 'Lieu du stage', 'class'=>"form-control"]);
      echo'</div>';
      echo '<div class="col s4">';
        echo $this->Form->control('edc_teachers._ids',['type' => 'select','multiple' => true,'options' => $teachers,'label' => 'Intervenants', 'class'=>"form-control"]);
      echo'</div>';
      echo '<div class="col s4">';
        echo $this->Form->control('price',['label' => 'Tarif', 'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';

    echo '<div class="row gx-5">';
      echo '<div class="col s4">';
        echo $this->Form->control('full_name',['label' => 'Nom complet', 'class'=>"form-control"]);
      echo'</div>';
      echo '<div class="col s4">';
        echo $this->Form->control('hello_asso',['label' => 'ID Hello Asso', 'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';

    echo '<div class="row gx-5">';
      echo '<div class="col">';
        echo $this->Form->button(__('Sauvegarder'),['class'=>'form-control btn red darken-4']);
      echo'</div>';
      echo '<div class="col">';
        echo $this->Html->link('Annuler', ['controller'=>'edc-courses','action' => 'index'],['class'=>"form-control btn grey lighten-1"]);
      echo'</div>';
    echo'</div>';

    echo $this->Form->end();
?>

<script> /**récupère les infos du stage pour créer le nom complet */
$( "#id-place" ).change(function(){
  var type = $("#id-type option:selected" ).text();
  var date = $("#date").val();
  var place = $("#id-place option:selected").text();
  var completename = type + "-" + date + "-" + place
  $('#full-name').val(completename) ;
});
</script>
