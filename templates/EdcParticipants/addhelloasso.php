<h2 id="title" class="<?= $course->id_season; ?>" stageid="<?= $course->id; ?>" >Inscription <?= $course->edc_type?->name; ?> à <?= $course->edc_place?->name; ?> </h2>

<div style="margin-bottom:10%">

<?php  
//calcul de l'âge
    $dob = $_GET['dob'];
    $dobTimestamp = strtotime(str_replace('/', '-', $dob));
    $todayTimestamp = time();
    $age = floor(($todayTimestamp - $dobTimestamp) / (365.25 * 24 * 60 * 60));   

// calcul du kilométrage
    if($club != NULL){
        $origin = str_replace(' ', '_', $club->map);
        $destination = str_replace(' ', '_', $course->edc_place?->name);
        $url = "https://maps.googleapis.com/maps/api/distancematrix/json?origins=".urlencode($origin)."&destinations=" . urlencode( $destination) . "&key=" . $googleMapKey;
        $jsonfile = file_get_contents($url);
        $jsondata = json_decode($jsonfile);
        $km = round($jsondata->rows[0]->elements[0]->distance->value/1000);
    }else{
        $km = "0";
    }
    
//formulaire pour inscrire les personnes pré-inscrites sur Helloasso    
    echo $this->Form->create();

    echo '<div class="row">';
        echo ' <div class="col s4">';
            echo $this->Form->control('id_season', ['options' => $seasons,'label'=>'Saison', 'class'=>"form-control browser-default"]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.full_name',['label' => 'Nom','id' => 'name','class'=>"form-control", "value"=>$_GET['name']]);
        echo'</div>';
        echo '<div class="col s4">';
        /**on vérfie les données déjà présentes en base */
            echo '<div id="searchdatabase" style="margin:20px 0px" class="form-control btn cyan">Chercher dans la base</div>';
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.email',['label' => 'Email','id' => 'email','class'=>"form-control","value"=>$_GET['email']]);
        echo'</div>';
        echo '<div class="col s4">';
        echo $this->Form->control('edc_member.phone',['label' => 'Téléphone','id' => 'phone','class'=>"form-control","value"=>$_GET['phone']]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_member.dob',['label' => 'Date de naissance','id' => 'dob','class'=>"form-control","value"=>$_GET['dob']]);
        echo'</div>';
        echo '<div class="col s3">';
            $optionsGender = ['H'=>'H','F'=>'F','Autre'=>'Autre'];
            echo $this->Form->control('edc_member.gender',['options' => $optionsGender,'id' => 'gender','label' => 'Genre', 'class'=>"form-control browser-default"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        //si inscription à renouveler, afficher avertissement :
        if($subscription == null){
            echo '<div id="renew" class="row">
                <div class="card red darken-4">
                    <div class="card-content white-text">
                    Stop ! Avant de continuer, indiquez le club, le grade et le diplôme de l\'année en cours.
                    </div>
                </div>
            </div>';
        }
        echo '<div class="col s4">';
        if($club != NULL){
            echo $this->Form->control('club_number',['options'=>$optionsClubs,'label' => 'Club','id' => 'club','class'=>"form-control browser-default",'value'=>$club->id_fed]);
        }else{
            echo $this->Form->control('club_number',['options'=>$optionsClubs,'label' => 'Club','id' => 'club','class'=>"form-control browser-default"]);
        }
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('grade',['options'=>$optionsGrades,'label' => 'Grade','id' => 'grade','class'=>"form-control browser-default",'value'=>$grade->id]);
        echo'</div>';
        echo '<div class="col s4">';
        if($degree != NULL){
            echo $this->Form->control('degree',['options'=>$optionsDegrees,'label' => 'Diplôme','id' => 'degree','class'=>"form-control browser-default",'value'=>$degree->id]);
        }else{
            echo $this->Form->control('degree',['options'=>$optionsDegrees,'label' => 'Diplôme','id' => 'degree','class'=>"form-control browser-default"]);
        }
        echo'</div>';

         //si inscription à renouveler, afficher bouton validation :
        if($subscription == null){
            echo'<div class="row" >';
                echo '<div class="col">';
                    echo '<div id="buttonrenew" idmember="0" idsubscriptions="0" style="margin:20px 0px" class="form-control btn red darken-4">Renouveler</div>';
                echo'</div>';   
            echo'</div>';
        }
        //si inscription renouvelée alors afficher message d'info: :
        echo '<div id="validrenew" class="row" style="display:none">
                <div class="card  green">
                    <div class="card-content white-text">
                        Adhérent renouvelé, vous pouvez continuer l\'inscription au stage...
                    </div>
                </div>
            </div>';

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
            if($_GET['period'] == "Samedi après-midi" | $_GET['period'] == "Samedi matin et après-midi" | $_GET['period'] == "Samedi matin et après-midi et Dimanche" | $_GET['period'] == "Samedi après-midi et Dimanche"){
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
        echo '<div class="col s3">';
            echo $this->Form->label('km','Km parcourus');
            echo $this->Form->text('km',['id' => 'km', 'class'=>"form-control",'value'=>$km]);
        echo'</div>'; 
        echo '<div class="col s3">';
            echo $this->Form->control('age',['id' => 'age','class'=>"form-control age","value"=>$age]);
        echo'</div>'; 
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col">';
            if($subscription == null){
                echo '<div id="save" idmember="'. $member->id . '" idsubscriptions="0" class="form-control btn red darken-4">Sauvegarder</div>';
            }else{
                echo '<div id="save" idmember="'. $subscription->id_member . '" idsubscriptions=" '. $subscription->id . '" class="form-control btn red darken-4">Sauvegarder</div>';
            }
        echo'</div>';   
        echo '<div class="col">';
            echo $this->Html->link('Retour', ['controller'=>'edc-courses','action' => 'helloasso',$course->hello_asso],['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';
    echo $this->Form->end();
?>
</div>

<script>
$("#searchdatabase").click(function() {
    const csrfToken = $('meta[name="csrfToken"]').attr("content");
    var name = $("#name").val();
    var idseason = $("#title").attr('class');
    $.ajax({
        type: 'POST',
        headers : {"X-CSRF-Token": csrfToken},
        url: '/edc-participants/result',
        data: {
            name: name,
            idseason: idseason,
        },
        dataType: 'json',
        success: function(response){
            var id = response['id'];
            $("#save").attr("idmember",id);
            $("#linkrenew").attr("href","/edc/edc-subscriptions/renew/"+id);
            $("#linkrenewmobile").attr("href","/edc/edc-subscriptions/renew/"+id);     
            $("#name").val(response['full_name']);

            /**on remplit le champ email que s'il est vide */
            if($("#email").val() == ""){
                $("#email").val(response['email']);
            }

            /**on remplit le champ phone que s'il est vide */
            if($("#phone").val() == ""){
                $("#phone").val(response['phone']);
            }         

            /**on remplit le champ date de naissance que s'il est vide */
            if($("#dob").val() == ""){
                $("#dob").val(response['dob']);
            }   

            /**on calcule l'âge */
            var dob = new Date($("#dob").val());   
            var today = new Date();
            var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
            $("#age").val(age);

            $("#gender").val(response['gender']); 
                 
            //on remplit les champs avec les infos de la dernière inscription en base
            $("#save").attr("idsubscriptions",response['edc_subscriptions'][0]['id']);
            $("#club").attr("clubid",response['edc_subscriptions'][0]['club_number']);
            $("#club").val(response['edc_subscriptions'][0]['club_number']).change();
            $("#degree").val(response['edc_subscriptions'][0]['degree']).change();
            $("#grade").attr("gradeid",response['edc_subscriptions'][0]['edc_grade']['id']);
            $("#grade").val(response['edc_subscriptions'][0]['edc_grade']['id']);
            $("#edc").val(response['edc_subscriptions'][0]['edc']).change();  
        },
        error: function(){
            alert("Echec de la récupération des informations. Rechargez la page. ");
        }    
    });
    
});

$( "#save" ).click(function() {
    const csrfToken = $('meta[name="csrfToken"]').attr("content");

    var name = $("#name").val();
    var idmember = $("#save").attr("idmember");
    var idsubs = $("#save").attr("idsubscriptions");
    var idcourse = $("#title").attr("stageid");
    var paid = $("#payment").val();
    var age = $("#age").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var samediam = $("#satam").val();
    var samedipm = $("#satpm").val();
    var dimancheam = $("#sunam").val();
    var edc = $("#edc").val();
    var rgpd = $("#rgpd").val();
    var clubid = $("#club").val();
    var gradeid = $("#grade").val();
    var degree = $("#degree").val();
    var idseason = $("#title").attr('class');

    $.ajax({
        type: 'POST',
        headers : {"X-CSRF-Token": csrfToken},
        url: '/edc-participants/addknown',
        data: {
            name: name,
            phone: phone,
            email: email,
            idmember : idmember,
            idsubs : idsubs,
            idcourse : idcourse,
            paid : paid,
            age : age,
            samediam : samediam,
            samedipm : samedipm,                    
            dimancheam : dimancheam,
            edc : edc,
            rgpd : rgpd,
            clubid : clubid,
            gradeid : gradeid,
            degree : degree,
            idseason : idseason
        },
        dataType: 'text',
        success: function(response){
            location.href = "/edc-courses/view/"+idcourse;
        },
        error: function(){
            alert("some problem in saving data");
        }
    });
});

$( "#buttonrenew" ).click(function() {
    const csrfToken = $('meta[name="csrfToken"]').attr("content");
    
    var name = $("#name").val();
    var idmember = $("#save").attr("idmember");
    var idcourse = $("#title").attr("stageid");
    var paid = $("#payment").val();
    var age = $("#age").val();
    var edc = $("#edc").val();
    var rgpd = $("#rgpd").val();
    var comments = $("#comments").val();
    var clubid = $("#club").val();
    var gradeid = $("#grade").val();
    var degree = $("#degree").val();
    var idseason = $("#title").attr('class');

    $.ajax({
        type: 'POST',
        headers : {"X-CSRF-Token": csrfToken},
        url: '/edc-participants/addrenew',
        data: {
            name: name,
            idmember : idmember,
            idcourse : idcourse,
            paid : paid,
            age : age,
            edc : edc,
            rgpd : rgpd,
            comments : comments,
            clubid : clubid,
            gradeid : gradeid,
            degree : degree,
            idseason : idseason
        },
        dataType: 'json',
        success: function(response){        
            $('#renew').hide();
            $('#buttonrenew').hide();
            $('#validrenew').show();            
            $("#save").attr("idsubscriptions",response['edc_subscriptions'][0]['id']);
        },
        error: function(){
            alert("some problem in saving data");
        }
    });
});
</script>