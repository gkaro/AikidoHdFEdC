<div id="top">
    <?= $this->Html->link(
        '<i class="material-icons left">keyboard_return</i>Retour à la liste des stages',
        ['controller' => 'EdcCourses', 'action' => 'view',$course->id],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
    ) ?>
</div>

<h2 id="title" class="<?= $course->id_season; ?>" stageid="<?= $course->id; ?>" >Pré-inscriptions  <?= $course->full_name; ?></h2>

<?php /**connexion à l'API HelloAsso config dans app_local */

$tokenUrl = 'https://api.helloasso.com/oauth2/token';
$data = [
    'grant_type' => 'client_credentials',
    'client_id' => $helloassoClient,
    'client_secret' => $helloassoClientSecret,
];

/**commande curl pour lancer la requête d'autentification */
$ch = curl_init($tokenUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded'
]);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Erreur Curl : ' . curl_error($ch);
}
curl_close($ch);

$responseData = json_decode($response, true);

/**réception du token d'accès */
if (isset($responseData['access_token'])) {
    $accessToken = $responseData['access_token'];
} else {
    echo "Access token not found in response.";
}

/**on récupère l'identification de l'évènement */
$event = $course->hello_asso;

/**commande curl pour lancer la requête qui récupère les données de l'évènement */
$curl = curl_init();

curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.helloasso.com/v5/organizations/'. $association .'/forms/Event/'. $event .'/orders?pageIndex=1&pageSize=99&withDetails=true',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'accept: application/json',
        'authorization: Bearer '. $accessToken
    ],
]);

$responseComplete = curl_exec($curl);

if (curl_errno($curl)) {
    echo 'Erreur Curl : ' . curl_error($curl);
} else {
    $responseData = json_decode($responseComplete, true);
  
/**on affiche les résultats dans un tableau*/
    echo ' <table class="table table-hover responsive-table">
    <thead class="table-light">
        <tr class="" data-id="">   
            <th>Nom</th>
            <th>Prénom</th>
            <th>Club</th>
            <th>Grade</th>
            <th>Paiement</th>
            <th>Période</th>
            <th></th>
        </tr>
    </thead>
    <tbody>';
    if (isset($responseData['data']) && is_array($responseData['data'])) {  

        /**on décompose toutes les entrées dans l'évènement */   
        foreach ($responseData['data'] as $entry) { 

            /**initialisation des variables */      
            $lastNames = null;
            $firstNames = null;
            $clubName = null;
            $grade = null;
            $degree = null;
            $dob = null;
            $email = null;
            $phone = null;
            $payment = null;
            $period = null;

            /**on décompose tous les élèments de chaque entrée */
            foreach($entry['items'] as $item){
               
                $lastNames = rtrim($item['user']['lastName']); /**au cas où il y aurait un espace à la fin du nom */
                $firstNames = trim(str_replace(" ", "", $item['user']['firstName'])); /**au cas où il y aurait un espace à la fin du prénom */
                $name = $lastNames. " " .$firstNames;

                /**on décompose tous les éléments du formulaire créés sur mesure */
                foreach ($item['customFields'] as $field) {
                    if (isset($field['name']) && $field['name'] === 'Club') {
                         $clubName = $field['answer'];                             
                    }
                    if (isset($field['name']) && $field['name'] === 'Grade') {
                        $grade = $field['answer'];
                    }
                    if (isset($field['name']) && $field['name'] === 'Date de naissance') {
                        $prevDob = $field['answer'];
                        $dateObj = DateTime::createFromFormat('d/m/Y', $prevDob);
                        $dob = $dateObj->format('Y-m-d');/**conversion du format de la date de naissance */
                    }
                    if (isset($field['name']) && $field['name'] === 'Email') {
                        $email = $field['answer'];
                    }
                    if (isset($field['name']) && $field['name'] === 'Téléphone') {
                        $phone = $field['answer'];
                    }
                    if (isset($field['name']) && $field['name'] === 'Diplôme d\'enseignant') {
                        $degree = $field['answer'];
                    }
                    if (isset($field['name']) && $field['name'] === 'Participation à la demi-journée') {
                        $period = $field['answer'];    
                    }
                    if (isset($field['name']) && $field['name'] === 'Participation à la journée ou stage complet') {
                        $period = $field['answer'];  
                    }
                }
                if (isset($item['amount'])) {
                    $payment = $item['amount'] /100;/**conversion du paiement en décimal */
                }
                
                /**on continue le tableau */
                echo " <tr class='item' data-id='".$name ."'>";
                    echo "<td>" . $lastNames . "</td>
                         <td>" . $firstNames . "</td>
                         <td>" . $clubName . "</td>
                         <td>" . $grade . "</td>    
                         <td>" . $payment . "</td>
                         <td>" . $period . "</td>
                         <td>
                            <button class='search-btn' data-lastname='".$lastNames."' data-firstname='".$firstNames."' data-payment='".$payment."' data-clubname='".$clubName."' data-grade='".$grade."' data-dob='".$dob."' data-email='".$email."' data-degree='".$degree."' data-phone='".$phone."' data-period='".$period."'>Valider</button>
                        </td>
                </tr>";
            }/**on met toutes les infos dans le bouton de validation pour les récupérer en AJAX */
        }
    }
    echo '</tbody>
    </table>';
}

curl_close($curl);
?>

<script>
    $(document).ready(function() {
        // Attacher un event listener à chaque bouton de recherche
        $('.search-btn').on('click', function() {
            var lastName = $(this).data('lastname');
            var firstName = $(this).data('firstname');
            var clubName = $(this).data('clubname');
            var grade = $(this).data('grade');
            var dob = $(this).data('dob');
            var phone = $(this).data('phone');
            var email = $(this).data('email');
            var payment = $(this).data('payment');
            var degree = $(this).data('degree');
            var period = $(this).data('period');
            var idseason = $("#title").attr('class');
            var idCourse = $("#title").attr("stageid");
            var name = lastName + " " + firstName
            $.ajax({
                type: 'POST',
                headers : {
                    'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
                },
                url:' /edc-participants/result',
                data: {
                    name : name,
                    idseason: idseason,
                },
                dataType: 'json',
                success: function(response){
                    if(response != null){
                        /**l'adhérent est connu dans la base, on affiche ses données dans un formulaire pour l'inscrire au stage */
                        var id = response['id'];
                        location.href = "/edc-participants/addhelloasso?id1="+idCourse+"&id2="+id+"&name="+name+"&lastname="+lastName+"&firstname="+firstName+"&club="+clubName+"&grade="+grade+"&dob="+dob+"&phone="+phone+"&email="+email+"&degree="+degree+"&payment="+payment+"&period="+period;

                    }else{
                        /**l'adhérent n'est pas connu dans la base, on affiche ses données dans un formulaire pour l'inscrire dans la base puis au stage */
                        location.href = "/edc-participants/addnewhelloasso?id1="+idCourse+"&name="+name+"&lastname="+lastName+"&firstname="+firstName+"&club="+clubName+"&grade="+grade+"&dob="+dob+"&phone="+phone+"&email="+email+"&degree="+degree+"&payment="+payment+"&period="+period;
                    }
                },
                error: function(){
                    alert("Echec de la récupération des informations. Rechargez la page. ");
                } 
            });
            //fetchID(lastName,firstName,clubName,grade,dob,phone,email,payment,degree,period); // Appel de la fonction fetchID avec le nom de famille 
        });

        /**modifier la couleur de la ligne si la personne déjà validée */
        $(".table tr").each(function(){
            var row = $(this);
            var name = row.data("id");
            var idcourse = $("#title").attr("stageid");
            $.ajax({
                type: 'POST',
                headers : {
                    'X-CSRF-Token': $('meta[name="csrfToken"]').attr('content')
                },
                url: '/edc-courses/searchparticipant',
                data: {
                    name : name,
                    idcourse: idcourse,
                },
                dataType: 'json',
                success: function(response){
                    if(response != null){
                        /**personne déjà inscrite, on modifie les couleurs de la ligne*/
                        row.css("background-color", "lightgrey");
                        row.css("color", "grey");
                        console.log(response);
                    }else{
                        /**personne non inscrite on fait rien de spécial mais on met son nom dans le console log*/
                        console.log(name);
                    }
                },
                error: function(){
                    console.log(name + idcourse + ": Echec de la récupération des informations. Rechargez la page. ");
                } 
            });
        })
        
    });

    

    
    
   
</script>
