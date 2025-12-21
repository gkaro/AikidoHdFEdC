<script>
    $(document).ready(function(){
        $('.tabs').tabs();
        $('.fixed-action-btn').floatingActionButton();
    });
    document.addEventListener('DOMContentLoaded', function() {
        var elems = document.querySelectorAll('.fixed-action-btn');
        var instances = M.FloatingActionButton.init(elems, {
        direction: 'right'
    });
  });
</script>

<h2 id="title">Configuration</h2>

<div class="row" style="margin-top:5%" id="stats">
    <div class="col s12">
        <ul class="tabs">
            <li class="tab col"><a class="active" href="#types">Types de stage</a></li>
            <li class="tab col"><a href="#clubs">Clubs</a></li>
            <li class="tab col"><a href="#teachers">Intervenants</a></li>
            <li class="tab col"><a href="#places">Lieux</a></li>
            <li class="tab col"><a href="#saisons">Saisons</a></li>
        </ul>
    </div>
    <div id="types" class="col s12">
        <span >
            <?= $this->Html->link(
                '<i class="material-icons left">add</i>Ajouter',
                ['action' => 'addtype'],
                ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-large yellow darken-2', 'style' => 'margin:2% 0']
            ) ?>
        </span>
        <table class="table highlight responsive-table" id="graytable">
            <tbody>
            <?php foreach ($types as $t): ?>
            <tr>
                <td>
                    <?= $t->name ?>
                </td>
                <td>
                    <?= $this->Html->link(
                        '<i class="material-icons">edit</i>',
                        ['action' => 'edittype', $t->id],
                        ['escape' => false, 'class' => 'waves-effect waves-light btn-flat grey-text text-darken-4']
                    ) ?>
                </td>
                <td>
                    <?= $this->Form->postLink(
                            '<i class="material-icons red-text text-darken-4">delete</i>',
                            [ 'action' => 'deletetype', $t->id],
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
    <div id="clubs" class="col s12">
        <span >
            <?= $this->Html->link(
                '<i class="material-icons left">add</i>Ajouter un club',
                ['action' => 'addclub'],
                ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-large yellow darken-2', 'style' => 'margin:2% 0']
            ) ?>
        </span>

        <table class="table highlight responsive-table" id="graytable">
            <thead>
                <tr>
                    <td>nom</td>
                    <td>ville</td>
                    <td>Map</td>
                    <td>nom complet</td>
                    <td></td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($clubs as $c): ?>
                <tr>
                    <td><?= $c->name ?></td>
                    <td><?= $c->city ?></td>
                    <td><?= $c->map ?></td>
                    <td><?= $c->complete_name ?></td>
                    <td>
                     <?= $this->Html->link(
                                '<i class="material-icons">edit</i>',
                                ['action' => 'editclub', $c->id],
                                ['escape' => false, 'class' => 'waves-effect waves-light btn-flat grey-text text-darken-4']
                            ) ?>    
                    </td>
                    <td>
                        <?= $this->Form->postLink(
                            '<i class="material-icons red-text text-darken-4">delete</i>',
                            [ 'action' => 'deleteclub', $c->id],
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
    <div id="teachers" class="col s12">
        <span>
            <?= $this->Html->link(
                '<i class="material-icons left">add</i>Ajouter',
                ['action' => 'addteacher'],
                ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-large yellow darken-2', 'style' => 'margin:2% 0']
            ) ?>
        </span>
        <table class="table highlight responsive-table" id="graytable">
            <tbody>
            <?php foreach ($teachers as $t): ?>
                <tr>   
                    <td>
                        <?= $t->name ?>
                    </td>
                    <td>
                        <?= $this->Html->link(
                            '<i class="material-icons">editteacher</i>',
                            ['action' => 'editteacher', $t->id],
                            ['escape' => false, 'class' => 'waves-effect waves-light btn-flat grey-text text-darken-4']
                        ) ?>
                    </td>
                    <td>
                        <?= $this->Form->postLink(
                            '<i class="material-icons red-text text-darken-4">delete</i>',
                            [ 'action' => 'deleteteacher', $t->id],
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
    <div id="places" class="col s12">
        <span>
            <?= $this->Html->link(
                '<i class="material-icons left">add</i>Ajouter',
                ['action' => 'addplace'],
                ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-large yellow darken-2', 'style' => 'margin:2% 0']
            ) ?>
        </span>
        <table class="table highlight responsive-table" id="graytable">
            <tbody>
            <?php foreach ($places as $p): ?>
                <tr>
                    <td>
                        <?= $p->name ?>
                    </td>
                    <td>
                       <?= $this->Html->link(
                            '<i class="material-icons">edit</i>',
                            ['action' => 'editplace', $p->id],
                            ['escape' => false, 'class' => 'waves-effect waves-light btn-flat grey-text text-darken-4']
                        ) ?>
                    </td>
                    <td>
                        <?= $this->Form->postLink(
                            '<i class="material-icons red-text text-darken-4">delete</i>',
                            [ 'action' => 'deleteplace', $p->id],
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
     <div id="saisons" class="col s12">
        <span>
            <?= $this->Html->link(
                '<i class="material-icons left">add</i>Ajouter',
                ['action' => 'addseason'],
                ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-large yellow darken-2', 'style' => 'margin:2% 0']
            ) ?>
        </span>
        <table class="table highlight responsive-table" id="graytable">
            <tbody>
            <?php foreach ($seasons as $s): ?>
                <tr>
                    <td>
                        <?= $s->name ?>
                    </td>
                    <td>
                       <?= $this->Html->link(
                            '<i class="material-icons">edit</i>',
                            ['action' => 'editseason', $s->id],
                            ['escape' => false, 'class' => 'waves-effect waves-light btn-flat grey-text text-darken-4']
                        ) ?>
                    </td>
                    <td>
                        <?= $this->Form->postLink(
                            '<i class="material-icons red-text text-darken-4">delete</i>',
                            [ 'action' => 'deleteseason', $s->id],
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