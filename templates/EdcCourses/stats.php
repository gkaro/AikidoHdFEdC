<div id="top">
    <a class="waves-effect waves-light btn-flat" href="<?= $this->request->referer() ?>"><i class="material-icons left">keyboard_return</i>Retour</a>
</div>

<h2 id="title">Statistiques du stage / <?= $course->full_name; ?></h2>

<div class="row" style="margin:2% 0%;min-height:400px">
    <div id="geoloc" class="col s12 hide-on-small-only">
        <!--affichage carte avec répartition des participants selon géolocalisation de leur club-->  
        <script>
            async function initMap() {
                const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
                const map = new google.maps.Map(document.getElementById("map"), {
                    zoom: 7,
                    center: { lat: 50, lng: 3 },
                    mapId: "DEMO_MAP_ID",
                });
                const marker = new AdvancedMarkerElement({map});
                setMarkers(map);
            }

            const clubs = [
                <?php 
                foreach ($participantsClubs as $key) {  
                    echo'["'.$key->club_name .'",'.$key->map.'],';
                }
                ?>
            ];

            function setMarkers(map) { 
                for (let i = 0; i < clubs.length; i++) {
                    const club = clubs[i];
                    new google.maps.marker.AdvancedMarkerElement({
                        position: { lat: club[1], lng: club[2] },
                        map,
                        title: club[0],
                    });
                }
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode(
                    { address: <?php echo '"'. $course->edc_course_place->name.'"'; ?> }, 
                    function(results, status) {
                        if (status === 'OK') {
                            // Create a new marker at the geocoded location
                            var marker = new google.maps.marker.AdvancedMarkerElement({
                            position: results[0].geometry.location,
                            map: map,
                            title : "stage",
                            });
                        } else {
                            console.log('Geocode was not successful for the following reason: ' + status);
                        }
                    }
                );
            }
            window.initMap = initMap;
        </script>

        <style>
            #map {
                height: 100%;
                width: 100%;
                min-height:400px;
            } 
        </style>

        <div id="map">
            <!--on affiche la map ici -->
        </div>
        <?php echo "<script src=\"https://maps.googleapis.com/maps/api/js?key=". $googleMapKey. "&callback=initMap&v=weekly&libraries=marker\" async defer></script>";?>
    </div>
</div>

<div class="row" style="margin:2% 0%;min-height:400px">
    <!--affichage des clubs représentés-->   
    <div id="chart_clubs" class="col s4">
        <h5>Clubs représentés</h5>
        <table>
            <tbody>
                <?php 
                foreach ($participantsClubs as $key) {  
                    echo'<tr><td>'.$key->clubname .'</td> <td>'.($key->count).'</td></tr>';
                }
                ?> 
            </tbody>
        </table>
    </div>
    <div class="row col s8" style="margin:2% 0%;min-height:400px">
        <!--affichage tableau avec répartition des participants selon leur grade-->
        <div id="chart_grades" class="col s2">
            <h5 style="border-bottom: 1px solid #ddd;font-size: 14px;margin-bottom: 11px;">Grades</h5>
            <?php 
            foreach ($participantsGrades as $key) {
                echo "<div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>" .$key->count . "</span><br>";
                echo "<small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>" . $key->grade . "</small></div>";
            }
            ?>
        </div>
        <!--affichage répartition des participants selon leur diplôme-->
        <div id="chart_degrees" class="col s2">
            <h5 style="border-bottom: 1px solid #ddd;font-size: 14px;margin-bottom: 11px;">Diplômes</h5>
                <?php 
                    foreach ($participantsDegrees as $key) {
                        echo "<div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>" .$key->count . "</span><br>";
                        echo "<small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>" . $key->degree . "</small></div>";
                    }
                ?>
        </div><!--on affiche le tableau ici -->

        <div class="col s6">
            <div class="row">
                <div class="col s6">
                    <h5 style="border-bottom: 1px solid #ddd;font-size: 14px;margin-bottom: 11px;">Age</h5>
                    <div style='margin-bottom:7%'>
                        <span style='font-weight: normal;font-size: 18px;line-height: 22px;'><?php if($avgAge != null){ echo round($avgAge);} ?></span><br>
                        <small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>Moyenne d'âge</small>
                    </div>
                    <?php 
                        $countUnder25 = 0;
                        foreach ($participants as $p){
                            if($p->age <= '25' && $p->age != '0' && $p->age != NULL){
                                $countUnder25 = $countUnder25 + 1;    
                            }
                        }
                    
                        echo "<div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>".$countUnder25."</span><br>
                        <small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>- 25 ans</small></div>";

                        $countOver25 = 0;
                        foreach ($participants as $p){
                            if($p->age > '25' && $p->age != NULL){
                                $countOver25 = $countOver25 + 1;    
                            }
                        }
                    
                        echo "<div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>".$countOver25."</span><br>
                        <small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>+ 25 ans</small></div>";
                    ?>
                    <h5 style="border-bottom: 1px solid #ddd;font-size: 14px;margin-bottom: 11px;">Distance</h5>
                    <div style='margin-bottom:7%'>
                        <span style='font-weight: normal;font-size: 18px;line-height: 22px;'><?php if($avgKm != null){ echo round($avgKm);} ?></span><br>
                        <small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>Moyenne km parcourus</small>
                    </div>
                </div>

                <div class="col s6">
                    <?php
                        $total = 0;
                        $male = 0;
                            foreach ($participants as $p){
                                $total = $total + 1;    
                                if($p->edc_subscription->edc_member->gender == 'H'){
                                    $male = $male + 1;    
                                }
                            }
                        
                        echo "<h5 style='border-bottom: 1px solid #ddd;font-size: 14px;margin-bottom: 11px;'>Répartition H/F</h5>
                        <div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>".$male;
                        if($total != 0){echo ' <small> ('.round($male/$total*100).'%)</small></span><br>';}
                            echo "<small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>Hommes</small></div>";
                            
                        $female = 0;
                        foreach ($participants as $p){
                            if($p->edc_subscription->edc_member->gender == 'F'){
                                $female = $female + 1;    
                            }
                        }
                    
                        echo "<div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>".$female;
                        if($total != 0){echo ' <small> ('.round($female/$total*100).'%)</small></span><br>';}
                            echo "<small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>Femmes</small></div>";

                        $edc = 0;
                        foreach ($participants as $p){
                            if($p->edc == 'oui'){
                                $edc = $edc + 1;    /**on calcule et affiche le nombre de participants inscrits à l'EdC */
                            }
                        }
                        echo "<h5 style='border-bottom: 1px solid #ddd;font-size: 14px;margin-bottom: 11px;'>EdC</h5>
                        <div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>".$edc."</span><br>
                            <small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>Inscrits école des cadres</small></div>";

                        $nonedc = 0;
                        foreach ($participants as $p){
                            if($p->edc != 'oui'){
                                $nonedc = $nonedc + 1;    /**on calcule et affiche le nombre de participants non inscrits à l'EdC */
                            }
                        }
                        echo "<div style='margin-bottom:7%'><span style='font-weight: normal;font-size: 18px;line-height: 22px;'>".$nonedc."</span><br>
                            <small style='color: #6c6c6c;font-size: 12px;vertical-align: middle;'>Non inscrits école des cadres</small></div>";

                    ?>   
            </div>
        </div>
    </div>
</div>