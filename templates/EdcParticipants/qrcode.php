<div id="insertresulthere" style="margin-top:10%">
    
   Scannez le QR Code

    <div id="qr-reader" style="width: 300px;"></div>
    <div id="title" class="<?= $course->id_season; ?>" stageid="<?= $course->id; ?>" ></div>
</div>


<div id="result" style="display:none">
    <h2 id="title" class="<?= $course->id_season; ?>" stageid="<?= $course->id; ?>" >Inscription <?= $course->edc_course_type->name; ?> à <?= $course->edc_course_place->name; ?> </h2>

    <div style="margin-bottom:10%" >

        <?php   //formulaire d'inscription au stage. La fonction AJAX cherche dans la base edcmembers et edcsubscriptions. Met à jour la table edcparticipants

            echo $this->Form->create();

            echo '<div class="row">';
                echo '<div class="col s4">';
                    echo $this->Form->control('full_name',['label' => 'Nom','id' => 'name','class'=>"form-control"]);
                echo'</div>';
                echo '<div class="col s4">';
                    echo $this->Form->control('email',['label' => 'Email','id' => 'email','class'=>"form-control"]);
                echo'</div>';
                echo '<div class="col s4">';
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
                    echo $this->Form->control('edc',['options'=>['non'=>'non','oui'=>'oui'],'id' => 'edc','class'=>"form-control browser-default"]);
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
                    echo $this->Form->control('saturday_am',['options'=>['non','oui'],'label' => 'Samedi Matin', 'class'=>"form-control browser-default"]);
                echo'</div>';
                echo '<div class="col s4">';
                    echo $this->Form->control('saturday_pm',['options'=>['non','oui'],'label' => 'Samedi Après-Midi', 'class'=>"form-control browser-default"]);
                echo'</div>';
                echo '<div class="col s4">';
                    echo $this->Form->control('sunday_am',['options'=>['non','oui'],'label' => 'Dimanche Matin', 'class'=>"form-control browser-default"]);
                echo'</div>';
            echo'</div>';

            echo'<div class="row">';
                echo '<div class="col s4">';
                    echo $this->Form->control('rgpd',['options'=>['oui','non'],'id' => 'rgpd', 'class'=>"form-control browser-default"]);
                echo'</div>';
                echo '<div class="col s4">';
                    echo $this->Form->label('payment','Paiement');
                    echo $this->Form->text('payment',['id' => 'payment', 'class'=>"form-control "]);
                echo'</div>'; 
                echo '<div class="col s4">';
                echo $this->Form->control('free_of_charge',['options'=>['edc'=>'école des cadres','codir'=>'Comité Directeur','autre'=>'Autre'],'empty' => true,'label' => 'Motif de gratuité','id' => 'foc', 'class'=>"form-control browser-default"]);
                echo'</div>';
            echo'</div>';  
            echo'<div class="row">';
                echo '<div class="col">';
                    echo '<div id="save" idmember="0" idsubscriptions="0" style="margin:20px 0px" class="form-control btn btn-primary">Sauvegarder</div>';
                echo'</div>';   
                echo '<div class="col">';
                    echo $this->Html->link('Retour', ['controller'=>'edc-courses','action' => 'view',$course->id],['class'=>"form-control btn btn-primary","style"=>"margin:20px 0"]);
                echo'</div>';
            echo'</div>';
            
            echo $this->Form->end();
        ?>
    </div>

</div>

<script>
    function onScanSuccess(decodedText, decodedResult) {
        var idseason = $("#title").attr('class');
        var idcourse = $("#title").attr('stageid');
        $.ajax({            
            type: 'POST',
            headers : {
                'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
            },
            url: '/edc-participants/resultscan',
            data: {
                decodedText: decodedText,
                idseason: idseason,
            },
            dataType: 'json',
            success: function(response){

                if(response != null){
                    var id = response['id'];
                    $("#save").attr("idmember",id);
                    $('#result').show();
                    $('#insertresulthere').hide();
                    $("#name").val(response['full_name']);
                    $("#email").val(response['email']);
                    $("#phone").val(response['phone']);
                    $("#gender").val(response['gender']);
                    
                    $("#dob").val(response['dob']);
                    var age = Math.floor((new Date()-new Date($("#dob").val())) / (365.25 * 24 * 60 * 60 * 1000));
                    $("#age").val(age);
                    
                    //on vérifie si inscription sur année en cours ou pas et on affiche le card d'avertissement
                    if(typeof response['edc_subscriptions'][0] === "undefined"){
                        $('#renew').show();
                        $('#buttonrenew').show();
                    }
                    else if(response['edc_subscriptions'][0]['id_season'] != idseason){
                        $('#renew').show();
                        $('#buttonrenew').show();
                    }
                     //on remplit les champs avec les infos de la dernière inscription en base
                    $("#save").attr("idsubscriptions",response['edc_subscriptions'][0]['id']);

                    $("#club").attr("clubid",response['edc_subscriptions'][0]['club_number']);
                    $("#club").val(response['edc_subscriptions'][0]['club_number']).change();

                    $("#degree").val(response['edc_subscriptions'][0]['degree']).change();

                    $("#grade").attr("gradeid",response['edc_subscriptions'][0]['edc_grade']['id']);
                    $("#grade").val(response['edc_subscriptions'][0]['edc_grade']['id']);

                    $("#edc").val(response['edc_subscriptions'][0]['edc']).change();
                    
                }else{
                    location.href = "/edc-participants/addnewfromcourse/"+idcourse+"/"+decodedText;
                }
            },
            error: function(){
                console.log(decodedText);
                alert("Echec : pas de correspondance trouvée ");
            }
        });
        //pauseScanner();
    }

    function onScanError(errorMessage) {
        console.error(`QR scan error: ${errorMessage}`);
    }

    // Start the QR code scanner
    const html5QrcodeScanner = new Html5QrcodeScanner(
        "qr-reader", 
        { 
            fps: 10, 
            qrbox: 250,
            videoConstraints: {
                facingMode: { exact: "environment" },
            } 
        }
    );

    html5QrcodeScanner.render(onScanSuccess, onScanError);

    //sauvegarde le formulaire
    $( "#save" ).click(function() {
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
        var samediam = $("#samedi-am").val();
        var samedipm = $("#samedi-pm").val();
        var dimancheam = $("#dimancheam").val();
        var edc = $("#edc").val();
        var rgpd = $("#rgpd").val();
        var comments = $("#comments").val();
        var clubid = $("#club").val();
        var gradeid = $("#grade").val();
        var degree = $("#degree").val();
        var idseason = $("#title").attr('class');

        $.ajax({
            type: 'POST',
            headers : {
                'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
            },
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
    }
);
</script>
