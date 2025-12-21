<div id="top">
    <?= $this->Html->link(
        '<i class="material-icons left">keyboard_return</i>Retour à la liste',
        ['controller' => 'EdcMembers', 'action' => 'view',$subscription->id_member],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
    ) ?>
</div>

<h4>Saison <?= $subscription->edc_season->name; ?></h4>
<table class="table table-hover" id="graytable"> 
    <tbody>
        <?php foreach($listCourses as $l) : ?>
        <tr>
            <td>
                <strong><?= $l->edc_course->full_name; ?></strong>
            </td>
        </tr>
         <?php endforeach; ?>
    </tbody>
</table>