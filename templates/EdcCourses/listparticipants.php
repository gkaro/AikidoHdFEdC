<div id="top">
    <?= $this->Html->link(
            '<i class="material-icons left">keyboard_return</i>Retour à la liste des stages',
            ['controller' => 'EdcCourses', 'action' => 'index'],
            ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
        ) ?>    
</div>

<h4 class="title">Liste des participants / <?= $course->full_name; ?></h4> 

<table class="table  highlight responsive-table">
    <thead class="table-light ">
        <tr>
            <th>Nom</th>
            <th>Club</th>
            <th style="text-align:center;">Grade</th>
            <th style="text-align:center;">Diplôme</th>
            <th style="text-align:center;">Paiement</th>
            <th style="text-align:center;">Km</th>
            <th style="text-align:center;">EdC</th>
            <th style="text-align:center;">Samedi Matin</th>
            <th style="text-align:center;">Samedi Après-midi</th>
            <th style="text-align:center;">Dimanche</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($participants as $c): ?>
        <tr>
            <td>
                <?= $c->edc_subscription?->edc_member?->full_name; ?>
            </td>
            <td>
                <?= $c->edc_subscription?->edc_club?->name; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->edc_subscription?->edc_grade?->label; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->edc_subscription?->degree; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->payment; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->km; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->edc; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->saturday_am; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->saturday_pm; ?>
            </td>
            <td style="text-align:center;">
                <?= $c->sunday_am; ?>
            </td>
            <td>
               <?= $this->Html->link(
                    '<i class="material-icons">edit</i>',
                    ['controller' => 'EdcParticipants', 'action' => 'edit', $c->id],
                    ['escape' => false,'class' => 'waves-effect waves-light btn-flat grey-text text-darken-4']
                ) ?> 
            </td>
            <td>
                <?= $this->Form->postLink($this->Html->tag('i','delete',array('class'=>'material-icons red-text text-darken-4')),
                    ['controller'=>'edc-participants','action' => 'delete', $c->id],
                    ['escape' => false,'confirm' => 'Êtes-vous sûr de vouloir supprimer?', "class"=>"waves-effect waves-light btn-flat"])
                    ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>