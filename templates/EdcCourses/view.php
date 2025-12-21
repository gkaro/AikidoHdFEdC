<div id="top">
    <?= $this->Html->link(
        '<i class="material-icons left">keyboard_return</i>Retour à la liste des stages',
        ['controller' => 'EdcCourses', 'action' => 'index'],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat']
    ) ?>
</div>

<div class="row section hide-on-small-only mt-2" >
    <div class="col s7">
        <h2 id="title">
            <?= h($course->edc_type?->name); ?> 
            <?= $this->Html->link(
                '<i class="material-icons grey-text text-darken-4">edit</i>',
                ['controller' => 'EdcCourses', 'action' => 'edit', $course->id],
                ['escape' => false]
            ) ?> 
        </h2>
    </div>

    <div class="col s3">
        <div class="row border_right" >
            <div class="col s12 details_course">
                <i class="material-icons left">place</i><?= h($course->edc_place?->name ?? '---') ?>
            </div>
            <div class="col s12 details_course"> 
                <i class="material-icons left">event</i><?= $course->date?->format('d-M-Y') ?? '---' ?>
            </div>
            <div class="col s12 details_course">
                <i class="material-icons left">face</i>
                <?= !empty($course->edc_teachers)
                    ? implode('<br>', array_map(fn($t) => $t->name, $course->edc_teachers))
                    : '---'
                ?>
            </div>
            <div class="col s12 details_course">
                <i class="material-icons left">euro_symbol</i><?= h($course->price ?? '---') ?>
            </div>
        </div>
    </div>
    <div class="col s2">
        <div class="row" >
            <?php
            $male   = $coursesParticipants->filter(fn($p) => $p->edc_subscription?->edc_member?->gender === 'H')->count();
            $female = $coursesParticipants->filter(fn($p) => $p->edc_subscription?->edc_member?->gender === 'F')->count();
            ?>
            <div class="col s12 details_stats">
                <span><?= $course->count ?></span><br>
                <small>Inscrits</small>
            </div>
            <div class="col s12 details_stats"> 
                <span><?= $male ?></span> <br>
                <small>Hommes</small>            
            </div>
            <div class="col s12 details_stats">
                <span><?= $female ?></span> <br>
                <small>Femmes</small>
            </div>
            <div class="col s12 details_stats">
                <span><?= $sum ?>€ </span><br>
                <small>Total paiements</small>            
            </div>
        </div>
    </div>
</div>


<!-- affichage mobile -->
<div class="row show-on-small-only hide-on-med-and-up" >
    <div class="col s12" style="border-right: 1px solid lightgray;">
        <div class="row" >
            <div class="col s4">
                <span class="details_course">Lieu</span>
            </div>
            <div class="col s8"> 
                <?= $course->edc_place?->name ?? '---' ?>
            </div>
        </div>
        <div class="row" >
            <div class="col s4">
                <span class="details_course">Date</span> 
            </div>
            <div class="col s8">
                <?= $course->date?->format('d-M-Y') ?? '---' ?>
            </div>
        </div>
        <div class="row" >
            <div class="col s4">
                <span class="details_course">Intervenant(s)</span> 
            </div>
            <div class="col s8"> 
                <?= !empty($course->edc_teachers)
                    ? h(implode('<br>', array_map(fn($t) => $t->name, $course->edc_teachers)))
                    : '---'
                ?>
            </div>
            <div class="col s4">
                <span class="details_course">Tarifs</span> 
            </div>
            <div class="col s8">
                <?= $course->price ?? '---' ?>
            </div>
        </div>
    </div>
</div>  

<!-- boutons action -->
 <!--uniquement desktop-->
<div class="row hide-on-small-only">
    <div class="col">
        <?= $this->Html->link(
            '<i class="material-icons left">add</i>Ajouter une inscription',
            ['controller' => 'EdcParticipants', 'action' => 'add', $course->id],
            ['escape' => false, 'class' => 'waves-effect waves-light btn yellow darken-2']
        ) ?>
    </div>
    <div class="col">
        <?= $this->Html->link(
            '<i class="material-icons left">photo_camera</i>QR Code',
            ['controller' => 'EdcParticipants', 'action' => 'qrcode', $course->id],
            ['escape' => false, 'class' => 'waves-effect waves-light btn red darken-4']
        ) ?>
    </div>
    <div class="col">
        <!--on vérifie si helloasso configuré pour ce stage si oui on affiche le bouton sinon on affiche un bouton sans lien-->
        <?php if (!empty($course->hello_asso)): ?>
            <?= $this->Html->link(
                'Pré-inscriptions HelloAsso',
                ['controller' => 'EdcCourses', 'action' => 'helloasso', $course->hello_asso],
                ['class' => 'waves-effect waves-light btn red darken-4']
            ) ?>
        <?php endif; ?>
    </div>
    <div class="col">
        <?= $this->Html->link(
            '<i class="material-icons">insert_chart</i>',
            ['controller' => 'EdcCourses', 'action' => 'stats', $course->id],
            ['escape' => false, 'class' => 'waves-effect waves-light btn grey lighten-1']
        ) ?>
    </div>
    <div class="col">
         <?= $this->Html->link(
            'Liste des participants XLS',
            ['action' => 'exportparticipants', $course->id],
            ['class' => 'btn grey lighten-1']
        ) ?>
    </div>
    <div class="col">
        <?= $this->Html->link(
            'Évaluations',
            ['controller' => 'EdcCourses', 'action' => 'evaluation', $course->id],
            ['class' => 'waves-effect waves-light btn grey lighten-1']
        ) ?>
    </div>
    
</div>

<div class="card z-depth-1">
    <div class="card-content">
        <table class="table table-hover responsive-table">
            <thead class="table-light">
                <tr>
                    <th>Nom</th>
                    <th>Club</th>
                    <th>Grade</th>
                    <th>Diplôme</th>
                    <th>Paiement</th>
                    <th>Km</th>
                    <th>Edc</th>
                    <th colspan="2"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($coursesParticipants as $c): ?>
                    <tr>
                        <td>
                            <?= $c->edc_subscription?->edc_member?->full_name; ?>
                        </td>
                        <td>
                            <?= $c->edc_subscription?->edc_club?->name ?? 'NR'; ?>
                        </td>
                        <td>
                            <?= $c->edc_subscription?->edc_grade?->label ?? 'NR'; ?>
                        </td>
                        <td>
                            <?=  $c->edc_subscription?->degree ?? 'NR'; ?>
                        </td>
                        <td>
                            <?= $c->payment ?? 'NR'; ?>
                        </td>
                        <td>
                            <?= $c->km ?? 'NR'; ?>
                        </td>
                        <td>
                            <?= $c->edc ?? 'NR'; ?>
                        </td>
                        <td>
                            <?= $this->Html->link(
                                '<i class="material-icons">edit</i>',
                                ['controller' => 'EdcParticipants', 'action' => 'edit', $c->id],
                                ['escape' => false, 'class' => 'waves-effect waves-light btn-flat grey-text text-darken-4']
                            ) ?>
                        </td>
                        <td>
                            <?= $this->Form->postLink(
                                '<i class="material-icons red-text text-darken-4">delete</i>',
                                ['controller' => 'EdcParticipants', 'action' => 'delete', $c->id],
                                [
                                    'escape' => false,
                                    'confirm' => 'Êtes-vous sûr de vouloir supprimer ?',
                                    'class' => 'waves-effect waves-light btn-flat'
                                ]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
 <!--uniquement mobile-->
<div class="row show-on-small-only hide-on-med-and-up">
    <div class="col">
        <?= $this->Html->link(
            '<i class="material-icons left">photo_camera</i>QR Code',
            ['controller' => 'EdcParticipants', 'action' => 'qrcodemobile', $course->id],
            ['escape' => false, 'class' => 'waves-effect waves-light btn']
        ) ?>
    </div>
    <div class="col">
        <?= $this->Html->link(
            '<i class="material-icons">add</i>',
            ['controller' => 'EdcParticipants', 'action' => 'add', $course->id],
            ['escape' => false, 'class' => 'waves-effect waves-light btn']
        ) ?>
    </div>
</div>