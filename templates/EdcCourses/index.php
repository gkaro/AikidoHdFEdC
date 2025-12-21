<h2 id="title">
    Liste des stages 
    <?= $this->Html->link(
        '<i class="material-icons left">add</i>Ajouter un stage',
        ['controller' => 'EdcCourses', 'action' => 'add'],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-large yellow darken-2', 'style' => 'margin:2% 0']
    ) ?>
</h2>

<table class="table highlight responsive-table" id="graytable">
    <tbody>
    <?php foreach ($courses as $c): ?>  
        <tr> 
            <td>
                <?= $c->date->format('d-M-Y') ?>
            </td>
            <td>
                <?= $this->Html->link($c->full_name, ['action' => 'view', $c->id],['class'=>"list-link"]) ?>
            </td>
            <td>
                <?php //affichage de la liste des enseignants du stage différent selon nombre d'intervenants
                if (!empty($c->edc_teachers)): ?>
                        <?= h(implode(' - ', array_map(fn($t) => $t->name, $c->edc_teachers))) ?>
                    <?php else: ?>
                        ---
                    <?php endif; ?>
            </td>
            <td>
                <?= $this->Html->link("Liste des participants", ['action' => 'listparticipants', $c->id],['class'=>'btn red darken-4']) ?> <!-- lien vers liste des participants  -->
            </td>
            <td>
                <?= $this->Html->link("XLS", ['action' => 'exportparticipants', $c->id],['class'=>'btn grey lighten-1']) ?><!-- export Excel liste des participants  -->
            </td>
            <td>
                 <?= $this->Html->link(
                        '<i class="material-icons">insert_chart</i>',
                        ['controller' => 'EdcCourses', 'action' => 'stats', $c->id],
                        ['escape' => false, 'class' => 'waves-effect waves-light btn grey lighten-1']
                    ) ?>
            </td>
        
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<ul class="pagination">
    <?= $this->Paginator->prev("<<") ?>
    <?= $this->Paginator->numbers() ?>
    <?= $this->Paginator->next(">>") ?>
</ul>