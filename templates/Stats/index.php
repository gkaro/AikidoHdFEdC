<table class="table highlight responsive-table" style="margin-top:2%">
            <thead class="table-light">
            <tr>
                <th></th>
                <?php foreach ($seasons as $s) : ?>
                <th style="text-align:center;"><?= $s->name; ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                <strong>Inscriptions uniques</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count = 0;
                    foreach ($subscriptions as $sub){
                        if($sub->id_season == $s->id){
                            $count = $count + 1;
                        }               
                    }
                    echo '<strong>'.$count.'</strong>';
                ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <tr style="border-top: 2px solid black;"></tr><!--Pour faire une séparation au milieu du tableau-->
            <tr>
                <td>
                <strong>Inscriptions uniques Hommes</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count_total = 0;
                    foreach ($subscriptions as $sub){
                        if($sub->id_season == $s->id){
                            $count_total = $count_total + 1;
                        }               
                    }

                    $count_male = 0;
                    foreach ($subscriptions as $sub){
                        if($sub->id_season == $s->id && $sub->edc_member?->gender == 'H'){
                            $count_male = $count_male + 1;
                        }               
                    }
                    $percentage_male = round($count_male/$count_total*100);
                    echo '<strong>'.$count_male.'</strong> (' . $percentage_male . '%)' ;
                ?>
                </td>
                <?php endforeach; ?>
                <tr>
                <td>
                <strong>Inscriptions uniques Femmes</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count_total = 0;
                    foreach ($subscriptions as $sub){
                        if($sub->id_season == $s->id){
                            $count_total = $count_total + 1;
                        }               
                    }

                    $count_female = 0;
                    foreach ($subscriptions as $sub){
                        if($sub->id_season == $s->id && $sub->edc_member?->gender == 'F'){
                            $count_female = $count_female + 1;
                        }               
                    }
                    $percentage_female = round($count_female/$count_total*100);
                    echo '<strong>'.$count_female.'</strong> (' . $percentage_female . '%)';
                ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <tr style="border-top: 2px solid black;"></tr><!--Pour faire une séparation au milieu du tableau-->
            <tr>
                <td>
                <strong>Participations Stages (Total)</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id){
                            $count = $count + 1;
                        }               
                    }
                    echo '<strong>'.$count.'</strong>';
                ?>
                </td>
                <?php endforeach; ?>
            </tr>

            <tr style="border-top: 2px solid black;"></tr><!--Pour faire une séparation au milieu du tableau-->

            <tr>
                <td>
                <strong>Participations Hommes</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count_total = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id){
                            $count_total = $count_total + 1;
                        }               
                    }

                    $count_male = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id && $q->edc_subscription?->edc_member?->gender == 'H'){
                            $count_male = $count_male + 1;
                        }               
                    }
                    $percentage_male = round($count_male/$count_total*100);
                    echo '<strong>'.$count_male.'</strong> (' . $percentage_male . '%)' ;
                ?>
                </td>
                <?php endforeach; ?>
                <tr>
                <td>
                <strong>Participations Femmes</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count_total = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id){
                            $count_total = $count_total + 1;
                        }               
                    }

                    $count_female = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id && $q->edc_subscription?->edc_member?->gender == 'F'){
                            $count_female = $count_female + 1;
                        }               
                    }
                    $percentage_female = round($count_female/$count_total*100);
                    echo '<strong>'.$count_female.'</strong> (' . $percentage_female . '%)';
                ?>
                </td>
                <?php endforeach; ?>
            </tr>
            
            <tr style="border-top: 2px solid black;"></tr><!--Pour faire une séparation au milieu du tableau-->

            <tr>
                <td>
                <strong>Participations Kyudansha</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count_total = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id){
                            $count_total = $count_total + 1;
                        }               
                    }

                    $count_kyudansha = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id && $q->edc_subscription?->grade < '8'){
                            $count_kyudansha = $count_kyudansha + 1;
                        }               
                    }
                    $percentage_kyudansha = round($count_kyudansha/$count_total*100);
                    echo '<strong>'.$count_kyudansha.'</strong> (' . $percentage_kyudansha . '%)';
                ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td>
                <strong>Participations Yudansha</strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count_total = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id){
                            $count_total = $count_total + 1;
                        }               
                    }

                    $count_yudansha = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id && $q->edc_subscription?->grade > '7'){
                            $count_yudansha = $count_yudansha + 1;
                        }               
                    }
                    $percentage_yudansha = round($count_yudansha/$count_total*100);
                    echo '<strong>'.$count_yudansha.'</strong> (' . $percentage_yudansha . '%)';
                ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <tr style="border-top: 2px solid black;"></tr><!--Pour faire une séparation au milieu du tableau-->

            <!--Participations par types de stage-->
            <?php foreach ($types as $t) : ?>
            <tr>
                <td>
                <strong><?= $t->name; ?></strong>
                </td>
                <?php foreach ($seasons as $s) : ?>
                <td style="text-align:center;">
                <?php 
                    $count_total = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id){
                            $count_total = $count_total + 1;
                        }               
                    }

                    $count_type = 0;
                    foreach ($participants as $q){
                        if($q->edc_course?->id_season == $s->id && $q->edc_course->id_type == $t->id){
                            $count_type = $count_type + 1;
                        }               
                    }
                    $percentage_type = round($count_type/$count_total*100);
                    echo '<strong>'.$count_type.'</strong> (' . $percentage_type . '%)';
                ?>
                </td>
                <?php endforeach; ?>
            </tr>
            <?php endforeach; ?>
            </tbody>

        </table>