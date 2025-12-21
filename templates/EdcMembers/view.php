<div id="top">
    <?= $this->Html->link(
        '<i class="material-icons left">keyboard_return</i>Retour à la liste des inscrits',
        ['controller' => 'EdcMembers', 'action' => 'index'],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
    ) ?>
</div>
<h2 id="title">
    <?=  $member->full_name; ?> 
    <?= $this->Html->link(
        '<i class="material-icons grey-text text-darken-4">edit</i>',
        ['controller' => 'EdcMembers', 'action' => 'edit',$member->id],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat', 'style' => 'margin:2% 0']
    ) ?>
</h2>

<p><strong>Date de naissance</strong> : <?= h($member->dob) ?></p>
<p><strong>Email</strong> : <?= h($member->email) ?> </p>
<p><strong>Téléphone</strong> : <?= h($member->phone) ?></p>

<h4>Inscriptions 
    <?= $this->Html->link(
        '<i class="material-icons">add</i>',
        ['controller' => 'EdcSubscriptions', 'action' => 'renew',$member->id],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-small yellow darken-2', 'style' => 'margin:2% 0']
    ) ?>
</h4> 
<table class="table table-hover" id="graytable"> 
    <tbody>
    <?php foreach ($member->edc_subscriptions as $s): ?>
        <tr>
            <td>
                <strong><?= $s->created?->format('d-m-Y') ?></strong><br><small>Date d'inscription</small>
            </td>
            <td>
                <strong><?= $s->edc_club?->name ?></strong><br><small>Club</small>
            </td>
            <td>
                <strong><?= $s->edc_grade?->label ?></strong> <br><small>Grade</small>
            </td>
            <td>
                <strong><?= $s->degree ?></strong><br><small>Diplôme</small>
            </td>
            <td>
                <strong><?= $s->edc ?></strong><br><small>EdC</small>
            </td>
            <td>
                <strong><?= $s->edc_season?->name ?></strong>
            </td>
            <td>
                <?= $this->Html->link(
                    '<i class="material-icons grey-text text-darken-4">edit</i>',
                    ['controller' => 'EdcSubscriptions', 'action' => 'edit', $s->id],
                    ['escape' => false, "class"=>"waves-effect waves-light btn-flat"]
                ) ?> 
                <?= $this->Form->postLink($this->Html->tag('i','delete',array('class'=>'material-icons red-text text-darken-4')),
                    ['controller'=>'EdcSubscriptions','action' => 'delete', $s->id],
                    ['escape' => false,'confirm' => 'Êtes-vous sûr de vouloir supprimer?', "class"=>"waves-effect waves-light btn-flat"])
                ?>
                <?= $this->Html->link(
                    '<i class="material-icons grey-text text-darken-4">list</i>',
                    ['controller' => 'EdcParticipants', 'action' => 'list', $s->id],
                    ['escape' => false, "class"=>"waves-effect waves-light btn-flat"]
                ) ?> 
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
