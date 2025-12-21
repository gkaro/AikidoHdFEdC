<h2 id="title" destination="<?= $course->edc_place->name; ?>" class="<?= $course->id_season; ?>" stageid="<?= $course->id; ?>" >Inscription stage <?= $course->edc_type->name; ?> à <?= $course->edc_place->name; ?> </h2>

<div style="margin-bottom:10%">

<?php   

    echo $this->Form->create($participant);
    echo '<div class="card  red darken-4">
            <div class="card-content white-text">
                Pratiquant inconnu dans la base. Merci de compléter les informations manquantes avant de valider
            </div>
    </div>';
    echo '<div class="row">';
        echo ' <div class="col s4">';
            echo $this->Form->control('edc_subscription.id_season', ['options' => $seasons,'label'=>'Saison', 'class'=>"form-control browser-default"]);
        echo'</div>';
    echo'</div>';
   
    
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.full_name',['label' => 'Nom','id' => 'name','class'=>"form-control","value"=>$_GET['name']]);
        echo'</div>';
        echo '<div class="col s4">';
            echo '<div id="searchdatabase" style="margin:20px 0px" class="form-control btn cyan">Chercher dans la base</div>';
        echo'</div>';      
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.last_name',['label' => 'Nom','id' => 'name','class'=>"form-control","value"=>$_GET['lastname']]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.first_name',['label' => 'Prénom','id' => 'name','class'=>"form-control","value"=>$_GET['firstname']]);
        echo'</div>';  
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.licence',['label' => 'N° de licence','id' => 'name','class'=>"form-control"]);
        echo'</div>';   
    echo'</div>';

    echo '<div id="notfoundindb" class="row" style="display:none">
            <div class="card red">
                <div class="card-content white-text">
                    Pas de correspondance trouvée...
                </div>
            </div>
        </div>';
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.email',['label' => 'Email','id' => 'email','class'=>"form-control","value"=>$_GET['email']]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.phone',['label' => 'Téléphone','id' => 'phone','class'=>"form-control","value"=>$_GET['phone']]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.dob',['label' => 'Date de naissance','id' => 'dob','class'=>"form-control","value"=>$_GET['dob']]);
        echo'</div>';
        echo '<div class="col s3">';
            echo $this->Form->control('edc_subscription.age',['class'=>"form-control age"]);
        echo'</div>';
        echo '<div class="col s3">';
            $optionsGender = ['H'=>'H','F'=>'F','Autre'=>'Autre'];
            echo $this->Form->control('edc_subscription.edc_member.gender',['options' => $optionsGender,'label' => 'Genre', 'class'=>"form-control browser-default"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s4">';
        if($_GET['club'] == "***Hors Ligue Hauts de France (préciser la ligue ci-dessous)***"){
            echo $this->Form->control('edc_subscription.club_number',['options'=>$optionsClubs,'label' => 'Club','id' => 'club','class'=>"form-control browser-default","value"=>"99999999"]);
        }else{
            echo $this->Form->control('edc_subscription.club_number',['options'=>$optionsClubs,'label' => 'Club','id' => 'club','class'=>"form-control browser-default","value"=>"$club->id_fed"]);
        }
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.grade',['options'=>$optionsGrades,'label' => 'Grade','id' => 'grade','class'=>"form-control browser-default","value"=>$grade->id]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.degree',['options'=>$optionsDegrees,'label' => 'Diplôme','id' => 'degree','class'=>"form-control browser-default","value"=>$_GET['degree']]);
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col s4">';
            if($_GET['period'] == "Samedi matin" | $_GET['period'] == "Samedi matin et après-midi" | $_GET['period'] == "Samedi matin et après-midi et Dimanche" | $_GET['period'] == "Samedi matin et Dimanche"){
                echo $this->Form->control('saturday_am',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Samedi Matin', 'class'=>"form-control browser-default",'value'=>1]);
            }else{
                echo $this->Form->control('saturday_am',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Samedi Matin', 'class'=>"form-control browser-default"]);
            }
        echo'</div>';
        echo '<div class="col s4">';
            if($_GET['period'] == "Samedi après-midi" | $_GET['period'] == "Samedi matin et après-midi" | $_GET['period'] == "Samedi matin et après-midi et Dimanche" | $_GET['period'] == "Samedi après-midi Dimanche"){
                echo $this->Form->control('saturday_pm',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Samedi Après-Midi', 'class'=>"form-control browser-default",'value'=>1]);
            }else{
                echo $this->Form->control('saturday_pm',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Samedi Après-Midi', 'class'=>"form-control browser-default"]);
            }
        echo'</div>';
        echo '<div class="col s4">';
            if($_GET['period'] == "Dimanche matin" | $_GET['period'] == "Samedi matin et après-midi et Dimanche"| $_GET['period'] == "Samedi matin et Dimanche" | $_GET['period'] == "Samedi après-midi et Dimanche"){
                echo $this->Form->control('sunday_am',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Dimanche Matin', 'class'=>"form-control browser-default",'value'=>1]);
            }else{
                echo $this->Form->control('sunday_am',['options'=>['0'=>'non','1'=>'oui'],'label' => 'Dimanche Matin', 'class'=>"form-control browser-default"]);
            }
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->label('rgpd','RGPD');
            echo $this->Form->text('rgpd',['id' => 'rgpd', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->label('payment','Paiement');
            echo $this->Form->text('payment',['id' => 'payment', 'class'=>"form-control","value"=>$_GET['payment']]);
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
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->button(__('Sauvegarder'), ['class'=>'form-control btn btn-primary']);
        echo'</div>';   
        echo '<div class="col s4">';
            echo $this->Html->link('Retour', ['controller'=>'edc-courses','action' => 'helloasso',$course->hello_asso],['class'=>"form-control btn btn-primary"]);
        echo'</div>';
    echo'</div>';
    echo $this->Form->end();
?>
   

</div>
<script>
/**calcul de l'âge */
$( "#dob" ).focusout(function(){
    var dob = $('#dob').val();
    dob = new Date(dob);
    var today = new Date();
    var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
    $('#edc-subscription-age').val(age);
});

/**calcul des km */
$( "#club").change(function(){
    var clubnumber = $("#club").val()
    var destination = document.getElementById('title').getAttribute('destination');   
    $.ajax({
        type: 'POST',
        headers : {
            'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
        },
        url: "/edc-participants/calckm/",
        data:{
            clubnumber : clubnumber,
            destination : destination,
        },
        dataType: 'json',
        async:false, 
        success: function(response){
            $("#km").val(response)
        },

        error: function(){
            alert("erreur" );
        }
    });
});

/**recherche du pratiquant dans la base */
$( "#searchdatabase").click(function() {
    var name = $("#name").val();
    var idseason = $("#title").attr('class');
    $.ajax({
        type: 'POST',
        headers : {
            'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
        },
        url: '/edc-participants/result',
        data: {
            name: name,
            idseason: idseason,
        },
        dataType: 'json',
        success: function(response){
            if(response == null){
                $('#notfoundindb').show();    
            }else{           
            var id = response['id'];
            $("#save").attr("idmember",id);
            $("#linkrenew").attr("href","/edc-subscriptions/renew/"+id);
            $("#linkrenewmobile").attr("href","/edc-subscriptions/renew/"+id);
       
            $("#name").val(response['name']);
            $("#email").val(response['email']);
            $("#phone").val(response['phone']);
            $("#gender").val(response['gender']); 

            $("#dob").val(response['dob']);
            var dob = new Date($("#dob").val());   
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            $("#age").val(age);
                 
            //on remplit les champs avec les infos de la dernière inscription en base
            $("#save").attr("idsubscriptions",response['edc_subscriptions'][0]['id']);
           // var club = response['edc_subscriptions'][0]['edc_club']['name'];
            $("#club").attr("clubid",response['edc_subscriptions'][0]['club_number']);
            $("#club").val(response['edc_subscriptions'][0]['club_number']).change();
            $("#degree").val(response['edc_subscriptions'][0]['degree']).change();
           // var grade = response['edc_subscriptions'][0]['edc_grade']['label'];
            $("#grade").attr("gradeid",response['edc_subscriptions'][0]['edc_grade']['id']);
            $("#grade").val(response['edc_subscriptions'][0]['edc_grade']['id']);
            $("#edc").val(response['edc_subscriptions'][0]['edc']).change();  
            }
        },
        error: function(){
            alert("Echec de la récupération des informations. Rechargez la page. ");
        }    
    });
    
});
</script>