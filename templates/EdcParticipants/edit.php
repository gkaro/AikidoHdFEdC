<script>
    $(document).ready(function(){
        $('select').formSelect();
        $('.datepicker').datepicker();
  });
</script>

<h2 id="course" destination="<?= $participant->edc_course?->edc_course_place?->name;?>" participant="<?= $participant->id;?>">Modifier l'inscription de <?= $participant->edc_subscription->edc_member->full_name;?> au stage <?= $participant->edc_course->name;?></h2>

<div style="margin-bottom:10%">

<?php   
    echo $this->Form->create($participant);
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.last_name',['class'=>"form-control",'label' => 'Nom']);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.first_name',['class'=>"form-control",'label' => 'Prénom']);
        echo'</div>';
         echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.full_name',['class'=>"form-control",'label' => 'Nom complet']);
        echo'</div>';
    echo'</div>';
   
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.club_number',['options'=>$optionsClubs,'label' => 'Club','id' => 'club','class'=>"form-control",'place'=>$participant->edc_subscription->edc_club->city]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.grade',['options'=>$optionsGrades,'label' => 'Grade','id' => 'grade','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.degree',['options'=>$optionsDegrees,'label' => 'Diplôme','id' => 'degree','class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('saturday_am',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Samedi Matin', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('saturday_pm',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Samedi Après-Midi', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('sunday_am',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Dimanche Matin', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->label('rgpd','RGPD');
            echo $this->Form->text('rgpd',['id' => 'rgpd', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->label('payment','Paiement');
            echo $this->Form->text('payment',['id' => 'payment', 'class'=>"form-control"]);
        echo'</div>'; 
        echo '<div class="col s4">';
            echo $this->Form->label('edc','Ecole des Cadres');
            echo $this->Form->text('edc',['id' => 'edc', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';  
    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->label('km','Km parcourus');
            echo $this->Form->text('km',['id' => 'km', 'class'=>"form-control"]);
            echo '<a class="waves-effect waves-light" id="recalc_km"><i class="tiny material-icons">replay</i><small>recalc. km</small></a>';
        echo'</div>'; 
        echo '<div class="col s4">';
            echo $this->Form->label('age','Age');
            echo $this->Form->text('age',['id' => 'age', 'class'=>"form-control"]);
            echo '<a class="waves-effect waves-light" id="recalc_age"><i class="tiny material-icons">replay</i><small>recalc. age</small></a>';
        echo'</div>';      
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col">';
            echo $this->Form->button(__('Sauvegarder'),['class'=>'form-control btn red darken-4']);
        echo'</div>';   
        echo '<div class="col">';
            echo $this->Html->link('Retour', ['controller'=>'edc-courses','action' => 'view',$participant->id_course],['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';
    echo $this->Form->end();
?>
</div>


<script>
    $( "#recalc_km" ).click(function() {
        var id = $("#course").attr('participant');
        var origin = $("#club").attr('place');
        var destination = $("#course").attr('destination');
        $.ajax({
            type: 'POST',
            headers : {
                'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
            },
            url: "/edc-participants/recalckm/"+id,
            data:{
                id : id,
                origin : origin,
                destination : destination,
            },
            dataType: 'text',
            async:false, 
            success: function(response){
                //location.reload(true);
                console.log(response); 
            },

            error: function(){
                alert("erreur" );
            }
        });
    }); 

    $( "#recalc_age" ).click(function() {
        var id = $("#course").attr('participant');
        console.log(id);
        $.ajax({
            type: 'POST',
            headers : {
                'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
            },
            url: "/edc-participants/recalcage/"+id,
            data:{
                id : id,
            },
            dataType: 'text',
            async:false, 
            success: function(response){
                console.log('test ' + response);
                //location.reload(true); 
            },

            error: function(){
                alert("erreur" );
            }
        });
    });

/**rempli le champ name avec le nom et prénom */
$( "#edc-subscription-edc-member-first-name" ).focusout(function(){
    var lastname = $('#edc-subscription-edc-member-last-name').val();
    var firstname = $('#edc-subscription-edc-member-first-name').val();
    var name = lastname + " " + firstname;
    $('#edc-subscription-edc-member-full-name').val(name);
}); 
</script>
