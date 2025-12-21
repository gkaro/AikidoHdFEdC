<!--code à insérer dans la div id=listmembers de edc-member/index avec résultat de la requête AJAX -->
<?php foreach ($result as $m): ?>
    <tr>
        <td>
           <?php $name = strtoupper($m->last_name)." ".$m->first_name;
                echo $this->Html->link($name, ['action' => 'view', $m->id],['class'=>"list-link"]) ?>
        </td>
        <td id="listmembersclub">
            <?php foreach($m->edc_subscriptions as $e){
                echo $e->edc_club?->name. '<br />';
            }?>
        </td>
        <td id="listmembersseason">
            <?php foreach($m->edc_subscriptions as $e){
                echo $e->edc_season?->name. '<br />';
            }?>
        </td>
        <td>   
        </td>
    </tr>
<?php endforeach; ?>