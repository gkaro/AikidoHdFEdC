<script>
    $(document).ready(function(){
        $('select').formSelect();

 //active certains formats pour le formulaire + liste déroulante en autocomplete : le résultat choisi est ensuite envoyé dans la fonction AJAX sendItem
        $('.datepicker').datepicker();
        $( 'input.autocomplete' ).autocomplete({
            data: {  <?php 
                foreach ($searchLicences as $key) {  
                    echo'"'.$key .'" : null,';
                }
            ?>},
            onAutocomplete: function(txt) {
                sendItem(txt);
            },
        });
    });
</script>

<?php
// si page générée depuis QRCode, reprendre les infos de prevresult sinon mettre à null
if(isset($prevresult)) {

    $lastname = $prevresult->lastname; 
    $firstname = $prevresult->firstname;

    switch ($prevresult->gender){
        case "M":
        $gender = "H";
        break;
        case "F":
        $gender = "F";
        break;
    }
    $grade = NULL;
    switch ($prevresult->grade){
        case "1":
        $grade = "8";
        break;
        case "2":
        $grade = "9";
        break;
        case "3":
        $grade = "10";
        break;
        case "4":
        $grade = "11";
        break;
        case "5":
        $grade = "12";
        break;
        case "6":
        $grade = "13";
        break;
        case "7":
        $grade = "14";
        break;
    }
    $dob = $prevresult->dob;
}
else{
    $lastname = null; 
    $firstname = null; 
    $clubnumber = null;
    $grade = null;
    $dob = null;
    $gender = null;
}
?>

<h2 id="title" destination="<?= $course->edc_place->name; ?>" origin="<?php if(isset($prevresult)) {echo $prevresult->club;}else{echo "none";}?>">Ajouter un nouveau pratiquant</h2>
<script>
    $(document).ready(function(){ 
    /*met à jour certains champs suite au scan du QRcode */
        var clubidfromlicence = $("#title").attr("origin");
        if(clubidfromlicence !="none"){
            $("#edc-subscription-club-number").attr("clubid", clubidfromlicence);
            $("#edc-subscription-club-number").val(clubidfromlicence).change();
        }

        var dob = $('#edc-subscription-edc-member-dob').val();
        dob = new Date(dob);
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
        $('#edc-subscription-age').val(age);
        $('#age').val(age);
     });
</script>

<?php
    echo $this->Form->create($participant);
    echo '<div class="row">';
        echo ' <div class="col s4">';
            echo $this->Form->control('edc_subscription.idseason', ['options' => $seasons,'label'=>'Saison', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo'<div class="input-field col s12">
                    <input type="text" id="autocomplete-input" class="autocomplete">
                    <label for="autocomplete-input">Rechercher une licence</label>
                </div>';//fonctionne avec le script en début de code
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s6">';
            echo $this->Form->control('edc_subscription.edc_member.last_name',['label' => 'Nom', 'value'=>$lastname,'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s6">';
            echo $this->Form->control('edc_subscription.edc_member.first_name',['label' => 'Prénom', 'value'=>$firstname,'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s0">';
            echo $this->Form->control('edc_subscription.edc_member.full_name',['label' => '', 'value'=>$lastname . " ". $firstname,'style'=>"display:none"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.licence',['class'=>"form-control text", 'value'=>$licence]);        
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.email',['class'=>"form-control"]);        
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.phone',['label' => 'Téléphone', 'class'=>"form-control"]);
        echo'</div>';
        
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.dob',['label' => 'Date de naissance', 'class'=>"form-control", 'value'=>$dob]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.age',['class'=>"form-control age"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.edc_member.gender',['options' => $optionsGender,'label' => 'Genre', 'class'=>"form-control browser-default", 'value' =>$gender]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.club_number',['options' => $optionsClubs,'label' => 'Nom du club','class'=>"form-control browser-default",'clubid' => 'none']);
            
        echo'</div>';
        echo '<div class="col s4">';
        if($grade == NULL){
            $defaultgrade = '99';
        }else{$defaultgrade = $grade;}
            echo $this->Form->control('edc_subscription.grade',['options' => $optionsGrades,'label' => 'Grade actuel', 'class'=>"form-control browser-default",'value'=>$defaultgrade]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('edc_subscription.degree',['options' => $optionsDegrees,'label' => 'Avez-vous un diplôme d\'enseignant ?', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';

    echo '<div class="row">';
        echo '<div class="col s4">'; 
        $optionsedc = ['oui' => 'oui', 'non' => 'non'];
            echo $this->Form->control('edc_subscription.edc',  ['options' => $optionsedc,'val'=>'non','id'=>'edc','label' => 'Ecole des Cadres', 'class'=>"form-control"]);
        echo '</div>';
        
    echo'</div>';
    
    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('saturday_am',['options'=>['non','oui'],'label' => 'Samedi Matin', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('saturday_pm',['options'=>['non','oui'],'label' => 'Samedi Après-Midi', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('sunday_am',['options'=>['non','oui'],'label' => 'Dimanche Matin', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('rgpd',['options'=>['oui','non'],'id' => 'rgpd', 'class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->label('payment','Paiement');
            echo $this->Form->text('payment',['id' => 'payment', 'class'=>"form-control"]);
        echo'</div>'; 
        echo '<div class="col s4">';
         echo $this->Form->control('freeofcharge',['options'=>['edc'=>'école des cadres','codir'=>'Comité Directeur','autre'=>'Autre'],'empty' => true,'label' => 'Motif de gratuité','id' => 'foc', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';  
    echo'<div class="row">';
        echo '<div class="col s3">';
            echo $this->Form->control('km',['class'=>'form-control ']);
        echo'</div>';    
    echo'</div>';

    echo '<div class="row gx-5">';
        echo '<div class="col">';
            echo $this->Form->button(__('Sauvegarder'), ['class'=>'form-control btn red darken-4']);
        echo'</div>';
        echo '<div class="col">';
        $referer = $this->request->referer();
            echo $this->Html->link('Annuler',  $referer,['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';
    echo $this->Form->end();
?>




<script>
    //calcul de l'âge lorsque la date de naissance est remplie manuellement
    $("#edc-subscription-edc-member-dob").focusout(function(){
        var dob = $('#edc-subscription-edc-member-dob').val();
        dob = new Date(dob);
        var today = new Date();
        var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
        $('#edc-subscription-age').val(age);
        $('#age').val(age);
    });


    //pour remplir le champ name
    $("#edc-subscription-edc-member-first-name").focusout(function(){
        var lastname = $('#edc-subscription-edc-member-last-name').val();
        var firstname = $('#edc-subscription-edc-member-first-name').val();
        var name = lastname + " " + firstname;
        $('#edc-subscription-edc-member-full-name').val(name);
        console.log(name);
    });

    //calcul kilométrage lorsque le club est indiqué manuellement
    $("#edc-subscription-club-number").change(function(){
        const csrfToken = $('meta[name="csrfToken"]').attr("content");

        var clubnumberfromid = $("#edc-subscription-club-number").attr('clubid');
        var clubnumberfromvalue = $("#edc-subscription-club-number").val();
        if(clubnumberfromid != "none"){clubnumber = clubnumberfromid}else{clubnumber = clubnumberfromvalue}
        var destination = $("#title").attr('destination');
        if(clubnumber != "99999999"){
            $.ajax({
                type: 'POST',
                headers : {"X-CSRF-Token": csrfToken},
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
                    alert("erreur" + clubnumber+destination);
                }
            });
        }
        
    });

    /**fonction recherche par numéro de licence via le champ de recherche */
    function sendItem(licence) {
        const csrfToken = $('meta[name="csrfToken"]').attr("content");
        $.ajax({
            type: 'POST',
            headers : {"X-CSRF-Token": csrfToken},                
            url: '/edc-participants/resultlicencenew',
            data: {
                licence: licence,
            },
            dataType: 'json',
            success: function(response){
                $("#edc-subscription-edc-member-last-name").val(response['last_name']);
                $("#edc-subscription-edc-member-first-name").val(response['first_name']);
                $("#edc-subscription-edc-member-licence").val(response['licence']);

                var clubid = response['club'];
                $("#edc-subscription-club-number").attr("clubid",clubidfromlicence);
                $("#edc-subscription-club-number").val(clubidfromlicence).change();

                if(response['gender'] == "M"){$("#edc-subscription-edc-member-gender").val('H');}
                else{$("#edc-subscription-edc-member-gender").val('F');}
                    
                $("#edc-subscription-edc-member-dob").val(response['dob']);

                //calcul de l'âge
                var dob = new Date($("#edc-subscription-edc-member-dob").val());   
                var today = new Date();
                var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                $("#edc-subscription-age").val(age);

                /**correspondance pour les grades */
                if(response['grade'] == "1"){
                    $("#edc-subscription-grade").val("8");
                }if(response['grade'] == "2"){
                    $("#edc-subscription-grade").val("9");
                }if(response['grade'] == "3"){
                    $("#edc-subscription-grade").val("10");
                }if(response['grade'] == "4"){
                    $("#edc-subscription-grade").val("11");
                }if(response['grade'] == "5"){
                    $("#edc-subscription-grade").val("12");
                }if(response['grade'] == "6"){
                    $("#edc-subscription-grade").val("13");
                }if(response['grade'] == "7"){
                    $("#edc-subscription-grade").val("14");
                }
            },
            error: function(){
                alert("Echec de la récupération des informations. Rechargez la page.");
            }   
        });
    };
</script>