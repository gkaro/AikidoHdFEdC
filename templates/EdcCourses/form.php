<script>
  $(document).ready(function(){
    $('select').formSelect();
  });
</script>


<h2 id="title" class="<?= $course->idseason; ?>">Formulaire d'évaluation du stage <?php echo $course->edc_course_type?->name; ?> du <?php echo $course->date; ?></h2>
<?php
    echo $this->Form->create($form);

    echo '<div class="row gx-5">';
      echo '<div class="col s4">';
        echo $this->Form->control('id_course',['type'=>'hidden','default' => $course->id]);
      echo'</div>';
      echo '<div class="col s4">';
        $today = date("Y-m-d H:i:s");
        echo $this->Form->control('date',['type'=>'hidden','default' => $today,'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';

    echo '<div class="row gx-5">';
      echo '<div class="col s4">';
        echo $this->Form->control('name',['id'=>'participants','label' => 'Nom et Prénom', 'class'=>"form-control"]);
      echo'</div>';
      echo ' <div class="col s4">';
        echo $this->Form->control('grade',['label' => 'Grade', 'class'=>"form-control"]);
      echo'</div>';
      echo ' <div class="col s4">';
        echo $this->Form->control('email',['label' => 'Email', 'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';

    echo '<div class="row gx-5">';
      $options = ['très satisfaisant' => 'très satisfaisant', 'plutôt satisfaisant' => 'plutôt satisfaisant', 'plutôt insatisfaisant' => 'plutôt insatisfaisant','très insatisfaisant' => 'très insatisfaisant'];
      echo ' <div class="col s12">';
        echo $this->Form->control('question1',['options' =>$options ,'empty'=>true,'label' => 'Qualité de l\'organisation (inscription, accueil, information, conditions matérielles...)', 'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';
    echo '<div class="row gx-5">';
      echo '<div class="col s12">';
        echo $this->Form->control('question2',['options' =>$options ,'empty'=>true,'label' => 'Qualité de l\'animation, de l\'intervention des animateurs', 'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';
    echo '<div class="row gx-5">';
      echo '<div class="col s12">';
        echo $this->Form->control('question3',['options' =>$options ,'empty'=>true,'label' => 'Qualité et pertinence du contenu proposé, des ressources mises à disposition', 'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';
    
    echo '<div class="row gx-5">';
      echo '<div class="col s12">';
        echo $this->Form->control('comments',['type' => 'textarea','label' => 'Remarques, précisions, suggestions', 'class'=>"form-control"]);
      echo'</div>';
    echo'</div>';
    echo '<div class="row gx-5">';
      echo '<div class="col">';
      echo $this->Form->button(__('Envoyer'),['class'=>'form-control btn btn-primary']);
      echo'</div>';
    echo'</div>';

    echo $this->Form->end();
?>