<div class="row hide-on-small-only">
    <div id="prevcourse" class="col s6 m4 l4"> <!--première colonne : affiche les données du précédent stage -->
        <div class="home_title">Dernier stage</div>
        <div class="course_block">
            <div> <?= $prevCourse->date?->format('d-m-Y'); ?></div><!-- date du stage -->
            <div><a style="color:lightgray;" href="/edc-courses/view/<?= $prevCourse->id; ?>"><?= $prevCourse->edc_type?->name; ?></a></div><!-- nom du stage -->
            <div><?= $prevCourse->edc_place?->name; ?></div><!-- lieu du stage -->
        </div>
        <div class="stats_block">
            <div class="row"><!-- stats -->
                <!-- nombre de participants -->
                <?php 
                    $count = 0;
                    foreach ($prevCourse->edc_participants as $part){
                            $count = $count + 1;   
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Participants</small></div>';
                    ?>
             <!-- nombre de participants inscrits à l'école des cadres -->
                <?php $count = 0;
                    foreach ($prevCourse->edc_participants as $part){
                        if($part->edc =='oui'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>dont inscrits EdC</small></div>'; 
                    ?>
            </div>
            <div class="row">
                <!-- nombre de participants hommes -->
                <?php $count = 0;
                    foreach ($prevCourse->edc_participants as $p){
                        if($p->edc_subscription->edc_member->gender =='H'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Hommes</small></div>'; 
                    ?>
                <!-- nombre de participants femmes -->
                <?php $count = 0;
                    foreach ($prevCourse->edc_participants as $p){
                        if($p->edc_subscription->edc_member->gender =='F'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Femmes</small></div>'; 
                    ?>
            </div>
            <div class="row">
                <!-- trajet moyen -->
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= round($avgKm)?></span><br><small>Moy. Km</small></div>
            </div>
        </div>
        <div>
            <a class="waves-effect waves-light btn" href="/edc-courses/stats/<?= $prevCourse->id; ?>"><i class="material-icons">insert_chart</i></a><!-- lien vers page de stats -->

            <!-- uniquement sur desktop et tablette -->
            <?= $this->Html->link("Liste des participants XLS", ['controller' => 'EdcCourses','action' => 'exportparticipants', $prevCourse->id],['class'=>'btn btn-primary hide-on-small-only']) ?> <!-- export excel -->


            <!-- uniquement sur mobile -->
            <?= $this->Html->link("XLS", ['controller' => 'EdcCourses','action' => 'exportparticipants', $prevCourse->id],['class'=>'btn btn-primary show-on-small-only hide-on-med-and-up']) ?><!-- export excel -->
        </div>
    </div>

    <?php if($nextCourses != null):?><!-- si le prochain stage est défini on fait ce qui suit sinon voir class nonextcourse -->
    <div class="col s6 m4 l3" id="nextcourse">
        <div class="home_title">Prochains stages</div>
            <?php 
            foreach($nextCourses as $n){
                echo '<div class="course_block">
                <div>'. $n->date->format('d-m-Y') . '</div>
                <div><a style="color:lightgray;" href="/edc-courses/view/' . $n->id . '">' . $n->edc_type->name.'</a></div>
                <div>' . $n->edc_place->name . '</div>
                </div>';
            }
            ?>
        
        </div>
   <? else: ?>
    <div class="col s6 m4 l3 nonextcourse">
        <div class="home_title">Pas d'autres stages prévus</div>
    </div>
    <?php endif; ?>  
    <div class="col s6 m4 l5">
        <div class="home_title">Top 10 stages</div>
        <div class="topten_stats">
            <table>
             <?php foreach($bestCourses as $b){
                echo '<tr><td>' . $b->date->format('d-m-Y') . '</td><td>' . $b->edc_type?->name . '</td><td><small>' . $b->edc_place?->name .'</small></td><td style="font-weight:bold">'. $b->count.'</td></tr>';
                } ?>
            </table>
        </div>
    </div>
</div>

<div id="dashboard_home" class="row hide-on-small-only">
    <div class="col s6 m4 l4">
        <div class="home_title">Bilan de la saison précédente</div>
        <div class="summary_stats">
            <div class="row">
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $allPrevCoursesTotal; ?></span><br><small>Nombre total de stages</small></div>
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $allPrevParticipantsCount; ?></span><br><small>Nombre de participations</small></div>
            </div>
            <div class="row">
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $allPrevParticipantsHdfCount; ?></span><br><small>Nombre de participations Ligue</small></div>
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= round($allPrevParticipantsCount / $allPrevCoursesTotal); ?></span><br><small>Nombre moyen par stage</small></div>
            </div>
            <div class="row">
                <?php $count = 0;
                    foreach ($allPrevParticipantsTotal as $p){
                        if($p->edc_subscription->edc_member->gender =='H'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Hommes</small></div>'; 
                ?>
                <?php $count = 0;
                    foreach ($allPrevParticipantsTotal as $p){
                        if($p->edc_subscription->edc_member->gender =='F'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Femmes</small></div>'; 
                ?>
            </div>
            <div class="row">
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $uniquePrevParticipantsCount; ?></span><br><small>Nombre de participants uniques</small></div>
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"></div>
            </div>
            <div class="row">
                <?php $count = 0;
                    foreach ($uniquePrevParticipants as $u){
                        if($u->edc_subscription->edc_member->gender =='H'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Hommes</small></div>'; 
                ?>
                <?php $count = 0;
                    foreach ($uniquePrevParticipants as $u){
                        if($u->edc_subscription->edc_member->gender =='F'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Femmes</small></div>'; 
                ?>
            </div>
        </div>
    </div>
    <div class="col s6 m4 l4">
        <div class="home_title">Bilan de la saison en cours</div>
        <div class="summary_stats">
            <div class="row">
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $allCurrentCoursesTotal; ?></span><br><small>Nombre total de stages</small></div>
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $allCurrentParticipantsCount; ?></span><br><small>Nombre de participations</small></div>
            </div>
            <div class="row">
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $allCurrentParticipantsHdfCount; ?></span><br><small>Nombre de participations Ligue</small></div>
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= round($allCurrentParticipantsCount / $allCurrentCoursesTotal); ?></span><br><small>Nombre moyen par stage</small></div>
            </div>
            <div class="row">
                <?php $count = 0;
                    foreach ($allCurrentParticipantsTotal as $p){
                        
                        if($p->edc_subscription->edc_member->gender =='H'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Hommes</small></div>'; 
                ?>
                <?php $count = 0;
                    foreach ($allCurrentParticipantsTotal as $p){
                        if($p->edc_subscription->edc_member->gender =='F'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Femmes</small></div>'; 
                ?>
            </div>
            <div class="row">
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"><?= $uniqueParticipantsCount; ?></span><br><small>Nombre de participants uniques</small></div>
                <div class="col s6" style="text-align: center;"><span style="font-size:2em;"></div>
            </div>
            <div class="row">
                <?php $count = 0;
                    foreach ($uniqueParticipants as $u){
                        if($u->edc_subscription->edc_member->gender =='H'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Hommes</small></div>'; 
                ?>
                <?php $count = 0;
                    foreach ($uniqueParticipants as $u){
                        if($u->edc_subscription->edc_member->gender =='F'){
                            $count = $count + 1;    
                        }
                    }
                    echo '<div class="col s6" style="text-align: center;"><span style="font-size:2em;">'.$count.'</span><br><small>Femmes</small></div>'; 
                ?>
            </div>
        </div>
    </div>
    <div class="col s6 m4 l4 hide-on-small-only">
        <div class="home_title">Top 10 participations</div>
        <div class="topten_stats">
            <table>
            <?php foreach($firstAttendant as $f){
                echo '<tr><td>' . $f->full_name .'</td><td>'. $f->course_count.'</td></tr>';
                } ?>
            </table>
        </div>
    </div>
</div>
<div id="dashboard_home2" class="row hide-on-small-only">
    <script>
            google.charts.load('current', {'packages':['corechart']});
            google.charts.setOnLoadCallback(drawChart);
            function drawChart() {
                var data = new google.visualization.arrayToDataTable([
                    ["stages", "participants", { role: "style" } ],
                    <?php 
                    foreach ($allParticipants as $key) {  
                        echo'["'.$key->date->format('d-m-Y') .'",'.($key->count).',"coral"],';
                    }
                    ?>
                ]);
                var view = new google.visualization.DataView(data);
                view.setColumns([0, 1,
                        { 
                            calc: "stringify",
                            sourceColumn: 1,
                            type: "string",
                            role: "annotation" 
                        },
                        2]);
                var options = {
                    title: "",
                    width: 600,
                    height: 400,
                    bar: {groupWidth: "75%"},
                    legend: { position: "none" },
                    
                };
                var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
                chart.draw(view, options);
            }
    </script>
        <div class="home_title">Participations aux stages</div>
        <div id="columnchart_values"></div>
   
</div>