<script> //active certains formats pour le formulaire + liste déroulante en autocomplete : le résultat choisi est ensuite envoyé dans la fonction AJAX sendItem
    $(document).ready(function(){
        $('select').formSelect();
        $('.datepicker').datepicker();
        $( 'input.autocomplete' ).autocomplete({
            data: {  <?php 
                foreach ($members as $key) {  
                    echo'"'.$key .'" : null,';
                }
            ?>},
            onAutocomplete: function(txt) {
                sendItem(txt);
            },
        });
    });
</script>
<div id="top">
    <?= $this->Html->link(
        '<i class="material-icons left">keyboard_return</i>Retour au stage',
        ['controller' => 'EdcCourses', 'action' => 'view',$course->id],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
    ) ?>
</div>

<h2 id="title" class="<?= $course->id_season; ?>" stageid="<?= $course->id; ?>" >Inscription stage <?= $course->edc_type?->name; ?> à <?= $course->edc_place?->name; ?> </h2>

<div>
<?php   //formulaire d'inscription au stage. La fonction AJAX cherche dans la base edcmembers et edcsubscriptions. Met à jour la table edcparticipants
    echo $this->Form->create();
    echo '<div class="row">';
        echo '<div class="col s8">';
            echo'<div class="input-field col s12">
                    <input type="text" id="autocomplete-input" class="autocomplete">
                    <label for="autocomplete-input">Rechercher un nom</label>
                </div>';//fonctionne avec le script en début de code
        echo'</div>';
        echo '<div class="col s4 hide-on-small-only">';
            echo $this->Html->link('Nouveau', ['controller'=>'edc-participants','action' => 'addnewfromcourse', $course->id],['class'=>"form-control btn red darken-4"]);
        echo'</div>';//lien pour créer une nouvelle entrée dans la base edcmembers, edcsubscriptions et edcparticipants
        echo '<div class="col s4 show-on-small-only hide-on-med-and-up">';
            echo $this->Html->link(
            '<i class="material-icons">person_add</i>',
            ['controller' => 'EdcParticipants', 'action' => 'addnewfromcourse',$course->id],
            ['escape' => false, 'class' => 'waves-effect waves-light btn red darken-4']
            );
        echo'</div>';//version mobile
    echo'</div>';
    echo '<div id="warninglicence" class="row" style="display:none">
    <div class="card  orange darken-3">
        <div class="card-content white-text">
            Attention, vérifiez que la licence soit bien à jour.
        </div>
    </div>
</div>';
    echo '<div class="row ">';
        echo '<div class="col s6">';
            echo $this->Form->control('last_name',['label' => 'Nom','id' => 'lastname','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s6">';
            echo $this->Form->control('first_name',['label' => 'Prénom','id' => 'firstname','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s0">';
            echo $this->Form->control('full_name',['label' => '', 'id' => 'name','style'=>"display:none"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row ">';
        echo '<div class="col s4">';
            echo $this->Form->control('licence',['label' => 'N° de licence','id' => 'licence','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('email',['label' => 'Email','id' => 'email','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
           echo $this->Form->control('phone',['label' => 'Téléphone','id' => 'phone','class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row show-on-small-only hide-on-med-and-up">';
        echo '<div class="col s6">';
            echo $this->Form->control('last_name',['label' => 'Nom','id' => 'lastname','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s5">';
            echo $this->Form->control('first_name',['label' => 'Prénom','id' => 'firstname','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s0">';
            echo $this->Form->control('full_name',['label' => '', 'id' => 'name','style'=>"display:none"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row show-on-small-only hide-on-med-and-up">';
        echo '<div class="col s4">';
            echo $this->Form->control('licence',['label' => 'N° de licence','id' => 'licence','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('email',['label' => 'Email','id' => 'email','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s ">';
           echo $this->Form->control('phone',['label' => 'Téléphone','id' => 'phone','class'=>"form-control"]);
        echo'</div>';
    echo'</div>';
    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('dob',['label' => 'Date de naissance','id' => 'dob','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('gender',['label' => 'Genre','id' => 'gender','class'=>"form-control"]);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('age',['label' => 'Age','id' => 'age','class'=>"form-control"]);
        echo'</div>';
    echo'</div>';

    //si inscription à renouveler, afficher avertissement :
    echo '<div id="renew" class="row" style="display:none">
            <div class="card  red darken-4">
                <div class="card-content white-text">
                    Stop ! Avant de continuer, indiquez le club, le grade et le diplôme de l\'année en cours.
                </div>
            </div>
        </div>';

    echo '<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('club',['options'=>$optionsClubs,'label' => 'Club','id' => 'club','class'=>"form-control browser-default",'clubid' => '0']);
        echo'</div>';
        echo '<div class="col s3">';
            echo $this->Form->control('grade',['options'=>$optionsGrades,'label' => 'Grade','id' => 'grade','class'=>"form-control browser-default",'gradeid' => '0']);
        echo'</div>';
        echo '<div class="col s3">';
            echo $this->Form->control('degree',['options'=>$optionsDegrees,'label' => 'Diplôme','id' => 'degree','class'=>"form-control browser-default"]);
        echo'</div>';
        echo '<div class="col s2">';
            echo $this->Form->control('edc',['options'=>['non'=>'non','oui'=>'oui'],'id' => 'edc', 'class'=>"form-control browser-default"]);
        echo'</div>';

        //si inscripton à renouveler, afficher bouton validation :
        echo'<div class="row" >';
            echo '<div class="col">';
                echo '<div id="buttonrenew" idmember="0" idsubscriptions="0" style="display:none; margin:20px 0px" class="form-control btn red darken-4">Renouveler</div>';
            echo'</div>';   
        echo'</div>';

        //si inscripton renouvelée alors afficher message d'info: :
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
            echo $this->Form->control('saturday_am',['options'=>['non','oui'],'label' => 'Samedi Matin', 'class'=>"form-control",'id' => 'saturday_am']);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('saturday_pm',['options'=>['non','oui'],'label' => 'Samedi Après-Midi', 'class'=>"form-control",'id' => 'saturday_pm']);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->control('sunday_am',['options'=>['non','oui'],'label' => 'Dimanche Matin', 'class'=>"form-control",'id' => 'sunday_am']);
        echo'</div>';
    echo'</div>';

    echo'<div class="row">';
        echo '<div class="col s4">';
            echo $this->Form->control('rgpd',['options'=>['oui','non'],'id' => 'rgpd', 'class'=>"form-control",'value'=>'oui']);
        echo'</div>';
        echo '<div class="col s4">';
            echo $this->Form->label('payment','Paiement');
            echo $this->Form->text('payment',['id' => 'payment', 'class'=>"form-control",'value'=>'0']);
        echo'</div>'; 
        echo '<div class="col s4">';
         echo $this->Form->control('freeofcharge',['options'=>['edc'=>'école des cadres','codir'=>'Comité Directeur','autre'=>'Autre'],'empty' => true,'label' => 'Motif de gratuité','id' => 'foc', 'class'=>"form-control"]);
        echo'</div>';
    echo'</div>';  
    echo'<div class="row gx-5">';
        echo '<div class="col">';
            echo '<div id="save" idmember="0" idsubscriptions="0" class="form-control btn red darken-4">Sauvegarder</div>';
        echo'</div>';   
        echo '<div class="col">';
            echo $this->Html->link('Retour', ['controller'=>'edc-courses','action' => 'view',$course->id],['class'=>"form-control btn grey lighten-1"]);
        echo'</div>';
    echo'</div>';
    
    echo $this->Form->end();
?>
</div>

<script>
/**rempli le champ name avec le nom et prénom */
$( "#firstname" ).focusout(function(){
    var lastname = $('#lastname').val();
    var firstname = $('#firstname').val();
    var name = lastname + " " + firstname;
    $('#name').val(name);
});

/**fonction de recherche d'adhérent utilisée avec formulaire de recherche*/
function sendItem(name) {
    const idseason = $("#title").attr('class');
    const csrfToken = $('meta[name="csrfToken"]').attr("content");
    $.ajax({
        type: 'POST',
        headers : {"X-CSRF-Token": csrfToken},
        url: '/edc-participants/result',
        data: {name, idseason},
        dataType: 'json',
        success: function(response){
            var id = response['id'];
            $("#save").attr("idmember",id);
               
            /**on remplit le formulaire avec les données trouvées */
            $("#lastname").val(response['last_name']);
            $("#firstname").val(response['first_name']);
            $("#licence").val(response['licence']);
            $("#email").val(response['email']);
            $("#phone").val(response['phone']);
            $("#gender").val(response['gender']);     
            $("#dob").val(response['dob']);

            //calcul de l'âge
            var dob = new Date($("#dob").val());   
            const age = dob ? Math.floor((new Date() - new Date(dob)) / (365.25 * 24 * 60 * 60 * 1000)) : "";
            $("#age").val(age);
 
            //on vérifie si inscription sur année en cours ou pas et on affiche le card d'avertissement si pas d'inscription en cours
            var sub = response['edc_subscriptions'][0];
            if(typeof sub === "undefined"){
                $('#renew').show();
                $('#buttonrenew').show();
            }
            else if(response['edc_subscriptions'][0]['id_season'] != idseason){
                $('#renew').show();
                $('#buttonrenew').show();
            }

            //on remplit les champs avec les infos de la dernière inscription en base
            $("#save").attr("idsubscriptions",response['edc_subscriptions'][0]['id']);

            $("#club").attr("clubid",response['edc_subscriptions'][0]['club_number']).val(response['edc_subscriptions'][0]['club_number']).change();
            $("#degree").val(response['edc_subscriptions'][0]['degree']).change();
            $("#grade").attr("gradeid",response['edc_subscriptions'][0]['edc_grade']['id']).val(response['edc_subscriptions'][0]['edc_grade']['id']);
            $("#edc").val(response['edc_subscriptions'][0]['edc']).change();  

            /*une fois qu'on a trouvé le participant, on regarde si son numéro de licence est à jour*/
            var lastname = response['last_name'];
            var firstname = response['first_name'];
            $.ajax({
                type: 'POST',
                headers: { "X-CSRF-Token": csrfToken },
                url: '/edc-participants/resultlicence',
                data: {lastname, firstname},
                dataType: 'json',
                success: function(response){
                    /**si licence pas à jour ou pas trouvée, on affiche warning */
                    if(response == null){
                        $('#warninglicence').show();
                    }    
                },
                error: function(){
                    alert("Echec de la récupération du numéro de licence");
                }     
            });
        },
        error: function(){
            alert("Echec de la recherche.");
        }   
    });
};

/**sauvegarde les données du formulaire si l'adhérent est déjà dans la base */
$( "#save" ).click(function() {
    const csrfToken = $('meta[name="csrfToken"]').attr("content");

    var lastname = $("#lastname").val();
    var firstname = $("#firstname").val();
    var name = lastname + " " + firstname;
    var idmember = $("#save").attr("idmember");
    var idsubs = $("#save").attr("idsubscriptions");
    var idcourse = $("#title").attr("stageid");
    var paid = $("#payment").val();
    var age = $("#age").val();
    var email = $("#email").val();
    var phone = $("#phone").val();
    var samediam = $("#saturday_am").val();
    var samedipm = $("#saturday_pm").val();
    var dimancheam = $("#sunday_am").val();
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
        url: '/edc-participants/addknown',
        data: {
            name: name,
            lastname: lastname,
            firstname: firstname,
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
            comments : comments,
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

/**bouton de renouvellement d'inscription */
$( "#buttonrenew" ).click(function() {
    const csrfToken = $('meta[name="csrfToken"]').attr("content");

    var lastname = $("#lastname").val();
    var firstname = $("#firstname").val();
    var name = lastname + " " + firstname;
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
            lastname: lastname,
            firstname: firstname,
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
            /**on masque les champs de renouvellement une fois sauvegardé et on met à jour le bouton save avec l'id d'inscription*/  
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

