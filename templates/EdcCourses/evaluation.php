<h2 id="title">Evaluations du stage</h2>

<div class="row" style="margin-top:5%" id="stats">
    <?= $this->Html->link("Lien vers le formulaire", ['action' => 'form',$course->id],['class'=>'btn red darken-4']);?> <!-- Lien vers le formulaire pour envoi aux participants -->
    <?= $this->Html->link("Export XLS", ['action' => 'exporteval',$course->id],['class'=>'btn grey lighten-1']);?> <!-- Export des réponses des participants -->
    
    <table class="table table-hover responsive-table">
        <thead class="table-light">
            <tr>
                <th style="text-align:center;width:20%">Qualité de l'organisation<br><small>(inscription, accueil, information, conditions matérielles...)</small></th>
                <th style="text-align:center;width:20%">Qualité de l'animation, de l'intervention des animateurs</th>
                <th style="text-align:center;width:20%">Qualité et pertinence du contenu proposé, des ressources mises à disposition</th>
                <th style="text-align:center;width:40%">Commentaires</th> 
            </tr>
        </thead>
        <tbody>
        <?php foreach ($evaluations as $e): ?>
            <tr>
                <td>
                    <?= $e->question1; ?>
                </td>
                <td>
                    <?= $e->question2; ?>
                </td>
                <td>
                    <?= $e->question3; ?>
                </td>
                <td>
                    <?= $e->comments; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>