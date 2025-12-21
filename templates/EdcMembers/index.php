<h2 id="title">
    Liste des inscrits 
    <?= $this->Html->link(
        '<i class="material-icons left">add</i>Ajouter un stage',
        ['controller' => 'EdcSubscriptions', 'action' => 'add'],
        ['escape' => false, 'class' => 'waves-effect waves-light btn-floating btn-large yellow darken-2', 'style' => 'margin:2% 0']
    ) ?>
</h2> 

<!--recherche dans la table edc-member-->
<input type="text" name="searchmember" placeholder="Rechercher un pratiquant" id="searchmember" class="form-control"> 
<button type="submit" id="searchmemberbutton"><i class="material-icons">search</i></button>

<table class="table highlight responsive-table"> 
    <thead class="table-light">
        <tr>
            <th>Nom</th>
            <th>Club</th>
            <th>Saisons</th>
            <th>Nb de stages</th>
        </tr>
    </thead>
    <tbody id="listmembers">
    <?php foreach ($memberlist as $m): ?>
        <tr>
            <td>
                <? $name = strtoupper($m->last_name)." ".$m->first_name;
                echo $this->Html->link($name, ['action' => 'view', $m->id],['class'=>"list-link"]) ?>
            </td>
            <td id="listmembersclub">
                <?php foreach($m->edc_subscriptions as $e){
                    if($e->edc_club != NULL){
                        echo $e->edc_club->name. '<br />';
                    }else{
                        echo "Club non renseigné";
                    }
                }?>
            </td>
            <td id="listmembersseason">
                <?php foreach($m->edc_subscriptions as $e){
                        echo $e->edc_season->name;
                    if($e->edc == 'oui'){
                        echo ' <small>EdC</small>';
                    }
                        echo '<br />';
                }?>
            </td>
            <td>
                 <?php 
                $i = 0; 
                foreach($m->edc_subscriptions as $e){
                    foreach($e->edc_participants as $p)
                   $i ++ ;
                  
                }
                echo $i;
                ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table> 

<script>
/**rechercher un pratiquant dans la base edc_members */
$('#searchmemberbutton').click(function(){
	var name = $('#searchmember').val();
    const csrfToken = $('meta[name="csrfToken"]').attr("content");
	$.ajax({
		type: 'POST',
        headers : {"X-CSRF-Token": csrfToken},
		url: 'edc-members/result',
		data: {
			name: name,
		},
		dataType: 'text',
		success: function(response){
            $('#listmembers').html(response);
		},
		error: function(){
			alert("aucune réponse trouvée");
		}
	});
});
</script>