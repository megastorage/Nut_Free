<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin = plugin::byId('Nut_free');
sendVarToJS('eqType', $plugin->getId());
$eqLogics = eqLogic::byType($plugin->getId());
?>

<div class="row row-overflow">
	<div class="col-xs-12 eqLogicThumbnailDisplay">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="eqLogicThumbnailContainer">
			<div class="cursor eqLogicAction logoPrimary" data-action="add">
				<i class="fas fa-plus-circle"></i>
				<br>
				<span>{{Ajouter}}</span>
			</div>
			<div class="cursor eqLogicAction logoSecondary" data-action="gotoPluginConf">
				<i class="fas fa-wrench"></i>
				<br>
				<span>{{Configuration}}</span>
			</div>
		</div>
		<legend><i class="icon meteo-soleil"></i> {{Mes Nut_free}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" id="in_searchEqlogic" />
		<div class="eqLogicThumbnailContainer">
			<?php
			foreach ($eqLogics as $eqLogic) {
				$opacity = ($eqLogic->getIsEnable()) ? '' : 'disableCard';
				echo '<div class="eqLogicDisplayCard cursor '.$opacity.'" data-eqLogic_id="' . $eqLogic->getId() . '">';
				echo '<img src="' . $plugin->getPathImgIcon() . '"/>';
				echo '<br>';
				echo '<span class="name">' . $eqLogic->getHumanName(true, true) . '</span>';
				echo '</div>';
			}
			?>
		</div>
	</div> 

	<div class="col-xs-12 eqLogic" style="display: none;">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-default btn-sm eqLogicAction roundedLeft" data-action="configure"><i class="fa fa-cogs"></i> {{Configuration avancée}}</a><a class="btn btn-default btn-sm eqLogicAction" data-action="copy"><i class="fas fa-copy"></i> {{Dupliquer}}</a><a class="btn btn-sm btn-success eqLogicAction" data-action="save"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a><a class="btn btn-danger btn-sm eqLogicAction roundedRight" data-action="remove"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a href="#" class="eqLogicAction" aria-controls="home" role="tab" data-toggle="tab" data-action="returnToThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#eqlogictab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Equipement}}</a></li>
			<li role="presentation"><a href="#commandtab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-list-alt"></i> {{Commandes}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="eqlogictab">
				<br/>
				<div class="row">
					<div class="col-xs-6">
						<form class="form-horizontal">
						<fieldset>
							<legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="returnToThumbnailDisplay"></i> {{Général}}</legend>
						<div class="form-group">
							<label class="col-md-4 control-label">{{Nom de l'équipement Nut_free}}</label>
							<div class="col-md-8">
								<input type="text" class="eqLogicAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="eqLogicAttr form-control" data-l1key="name" placeholder="{{Nom de l'équipement Nut_free}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label" >{{Objet parent}}</label>
							<div class="col-md-8">
								<select id="sel_object" class="eqLogicAttr form-control" data-l1key="object_id">
									<option value="">{{Aucun}}</option>
									<?php
										foreach (jeeObject::all() as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">{{Catégorie}}</label>
							<div class="col-sm-8">
								<?php
									foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
									echo '<label class="checkbox-inline">';
									echo '<input type="checkbox" class="eqLogicAttr" data-l1key="category" data-l2key="' . $key . '" />' . $value['name'];
									echo '</label>';
									}
								?>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-4 control-label"></label>
							<div class="col-md-8">
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isEnable" checked/>{{Activer}}</label>
								<label class="checkbox-inline"><input type="checkbox" class="eqLogicAttr" data-l1key="isVisible" checked/>{{Visible}}</label>
							</div>
						</div>
							
				<div id="Conf_IP">
				   <div class="form-group">
					  <label class="col-md-3 control-label">{{Adresse IP}}</label>   
					  <div class="col-md-4">
						 <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="addressip" type="text" placeholder="{{saisir l'adresse IP}}">
					  </div>
				   </div>
					
				<div class="form-group">
				   <label class="col-md-3 control-label">{{Auto detection UPS?}}</label>
				   <div class="col-md-4">
					 <select id="UPS_auto_select" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="UPS_auto_select"
					  onchange="if(this.selectedIndex == 1) document.getElementById('ups_auto').style.display = 'block';
					  else document.getElementById('ups_auto').style.display = 'none';">
						 <option value="0">{{Oui}}</option>
						 <option value="1">{{Non}}</option>
					  </select>
					  
				   </div>
				</div>
				<div id="ups_auto">
					<div class="form-group">
					  <label class="col-md-3 control-label">{{Nom de la configuration UPS}}</label>   
					  <div class="col-md-4">
						 <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="UPS" type="text" placeholder="{{saisir le nom de l'UPS du serveur. &quot;Resultat de UPSC -L&quot; sur le serveur UPS }}">
					  </div>
				   </div> 
				 </div> 
					
					<div class="form-group">
				   <label class="col-md-3 control-label">{{Avec Connexion SSH?}}</label>
				   <div class="col-md-4">
					 <select id="SSH_select" class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="SSH_select"
					  onchange="if(this.selectedIndex == 1) document.getElementById('SSH_op').style.display = 'block';
					  else document.getElementById('SSH_op').style.display = 'none';">
						 <option value="0">{{Non}}</option>
						 <option value="1">{{Oui}}</option>
					  </select>
					  
				   </div>
				</div>
				  
					  <div id="SSH_op">
					  
					   <div class="form-group">
						  <label class="col-md-3 control-label">{{Port SSH}}</label>   
						  <div class="col-md-4">
							 <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="portssh" type="text" placeholder="{{saisir le port SSH}}">
						  </div>
					   </div>
					   <div class="form-group">
						  <label class="col-md-3 control-label">{{Identifiant}}</label>   
						  <div class="col-md-4">
							 <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="user" type="text" placeholder="{{saisir le login}}">
						  </div>
					   </div>   
					   <div class="form-group">
						  <label class="col-md-3 control-label">{{Mot de passe}}</label>   
						  <div class="col-md-4">
							 <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="password" type="password" placeholder="{{saisir le password}}">
						  </div>
					   </div>         
					</div>
				</div>				
			</fieldset>
		</form>
		</div>
   </div>

</div>

<div role="tabpanel" class="tab-pane" id="commandtab">
				<br/>
				<legend><i class="fa fa-arrow-circle-left eqLogicAction cursor" data-action="add"></i> {{Commandes}}</legend>

				<table id="table_cmd" class="table table-bordered table-condensed">
					<thead>
						<tr>
							<th>{{Id}}</th>
							<th>{{Nom}}</th>
							<th>{{Unité}}</th>
							<th>{{Afficher/Historiser}}</th>
							<th>{{Action}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
	
</div>
</div>
</div>
<?php include_file('desktop', 'Nut_free', 'js', 'Nut_free'); ?>
<?php include_file('core', 'plugin.template', 'js'); ?>

