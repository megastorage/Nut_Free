<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; witfhout even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *f
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


set_include_path(get_include_path() . get_include_path().'/phpseclib');
include('Net/SSH2.php');
include('Crypt/RSA.php');
include('autoload.php');

use phpseclib\Net\SSH2;

class Nut_free extends eqLogic {

	public static $_infosMap = array(
		// on cree un tableau contenant la liste des infos a traiter 
		// chaque info a un sous tableau avec les parametres 
		// dans postSave() il faut le parcourir pour creer les cmd
		// 		array(
		// 			'name' =>'Nom de l\'equipement infol'
		// 			'logicalId'=>'Id de l\'quipement',
		// 			'type'=>'info', //on peu ne  pas specifie cette valeur et alors dans la boucle mettre celle par default
		// 			'subType'=>'string', //idem
		// 			'order' => 1, // ici on pourrai utiliser l'index du tableau et l'ordre serait le meme que ce tableau
		// 			'template_dashboard'=> 'line'
		//			'cmd' => 'ups.status', //commande a executer
		//			'order' => ordre d'affichage de la commande dans l equipement
		//			'enabled' => pour usage futur si on a besoin de toggler l'une ou l'autre commande
		// 		),
	
		array(
			'name' =>'Marque_Model',
			'logicalId'=>'Marque',
			'template_dashboard'=> 'line',
			'subtype'=>'string',
			'cmd'=>'device.mfr',
			'order' => '1',
			'enabled' => 'true',
		),
		array(
			'name' =>'Model',
			'logicalId'=>'Model',
			'subtype'=>'string',
			'cmd'=>'device.model',
			'order' => '2',
			'enabled' => 'true',
		),
		array(
			'name' =>'Serial',
			'logicalId'=>'ups_serial',
			'cmd' => 'ups.serial',
			'subtype'=>'string',
			'order' => '3',
			'enabled' => 'true',
		),
		array(
			'name' =>'UPS MODE',
			'logicalId'=>'ups_line',
			'cmd' => 'ups.status',
			'subtype'=>'string',
			'order' => '4',
			'enabled' => 'true',
		),
		array(
			'name' =>'Tension en entrée',
			'logicalId'=>'input_volt',
			'cmd'=>'input.voltage',
			'unite'=>'V',
			'order' => '5',
			'enabled' => 'true',
		),
		array(
			'name' =>'Fréquence en entrée',
			'logicalId'=>'input_freq',
			'cmd'=>'input.frequency',
			'unite'=>'Hz',
			'order' => '6',
			'enabled' => 'true',
		),
		array(
			'name' =>'Tension en sortie',
			'logicalId'=>'output_volt',
			'cmd'=>'output.voltage',
			'unite'=>'V',
			'order' => '7',
			'enabled' => 'true',
		),
		array(
			'name' =>'Fréquence en sortie',
			'logicalId'=>'output_freq',
			'cmd'=>'output.frequency',
			'unite'=>'Hz',
			'order' => '8',
			'enabled' => 'true',
		),
		array(
			'name' =>'Puissance en sortie',
			'logicalId'=>'output_power',
			'cmd'=>'ups.power',
			'unite'=>'VA',
			'order' => '9',
			'enabled' => 'true',
		),
		array(
			'name' =>'Puissance en sortie réel',
			'logicalId'=>'output_real_power',
			'cmd'=>'ups.realpower',
			'unite'=>'W',
			'order' => '10',
			'enabled' => 'true',
		),
		array(
			'name' =>'Niveau de charge batterie',
			'logicalId'=>'batt_charge',
			'cmd'=>'battery.charge',
			'unite'=>'%',
			'order' => '11',
			'enabled' => 'true',
		),
		array(
			'name' =>'Tension de la batterie',
			'logicalId'=>'batt_volt',
			'cmd'=>'battery.voltage',
			'unite'=>'V',
			'order' => '12',
			'enabled' => 'true',
		),
		array(
		  	'name'      => 'Température de la batterie',
		  	'logicalId' => 'batt_temp',
		  	'cmd'       => 'battery.temperature',
		  	'unite'     => '°C',
			'order' => '13',
			'enabled' => 'true',
		),
		array(
			'name'      => 'Température ups',
			'logicalId' => 'ups_temp',
			'cmd'       => 'ups.temperature',
			'unite'     => '°C',
			'order' => '14',
			'enabled' => 'true',
	  ),
		array(
			'name' =>'Charge onduleur',
			'logicalId'=>'ups_load',
			'cmd'=>'ups.load',
			'unite'=>'%',
			'order' => '15',
			'enabled' => 'true',
		),
		array(
			'name' =>'Temps restant sur batterie en s',
			'logicalId'=>'batt_runtime',
			'cmd'=>'battery.runtime',
			'unite'=>'s',
			'order' => '16',
			'enabled' => 'true',
		),
     		 array(
			'name' =>'Temps restant sur batterie en min',
			'logicalId'=>'batt_runtime_min',
			'cmd'=>'battery.runtime',
			'unite'=>'min',
			'order' => '17',
			'enabled' => 'true',
		),
		array(
			'name' =>'Temps restant avant arrêt en s',
			'logicalId'=>'timer_shutdown',
			'cmd'=>'ups.timer.shutdown',
			'unite'=>'s',
			'order' => '18',
			'enabled' => 'true',
		),
      		array(
			'name' =>'Temps restant avant arrêt en min',
			'logicalId'=>'timer_shutdown_min',
			'cmd'=>'ups.timer.shutdown',
			'unite'=>'min',
			'order' => '19',
			'enabled' => 'true',
		),
		array(
			'name' =>'Beeper',
			'logicalId'=>'beeper_stat',
			'subtype'=>'string',
			'cmd'=>'ups.beeper.status',
			'order' => '20',
			'enabled' => 'true',
		),
		array(
			'name' =>'SSH OPTION',
			'logicalId'=>'ssh_op',
			'order' => '21',
			'enabled' => 'true',
		),
		array(
			'name' =>'Statut cnx SSH Scénario',
			'logicalId'=>'cnx_ssh',
			'subtype'=>'string',
			'order' => '22',
			'enabled' => 'true',
		),
	);

    public static function cron() {
		foreach (eqLogic::byType('Nut_free') as $Nut_free) {
			//Rafraichissement des valeurs pour chaque equipement
			$Nut_free->getInformations();

			//Nettoyage du cache
			$cacheMobile = cache::byKey('Nut_freeWidgetmobile' . $Nut_free->getId());
			$cacheWidget = cache::byKey('Nut_freeWidgetdashboard' . $Nut_free->getId());
			$cacheMobile->remove();
			$cacheWidget->remove();

			//Rafraichissement du UI
			$Nut_free->toHtml('mobile');
			$Nut_free->toHtml('dashboard');
			$Nut_free->refreshWidget();
		}
	}

	public static function dependancy_info() {
		$return = array();
		$return['log'] = 'Nut_free_update';
		$return['progress_file'] = '/tmp/dependancy_Nut_free_in_progress';
		if (file_exists('/tmp/dependancy_Nut_free_in_progress')) {
			$return['state'] = 'in_progress';
		} else {
			if (exec('dpkg-query -l nut-client | wc -l') != 0) {
				$return['state'] = 'ok';
			} else {
				$return['state'] = 'nok';
			}
		}
		return $return;
	}

	public static function dependancy_install() {
		//Si installation deja en cours, sortie de fonction
		if (file_exists('/tmp/compilation_Nut_free_in_progress')) {
			return;
		}

		//Nettoyage du log
		log::remove('Nut_free_update');
		$cmd = 'sudo /bin/bash ' . dirname(__FILE__) . '/../../ressources/install.sh';

		//Redirection de la ligne de commande d installation vers le fichier log
		$cmd .= ' >> ' . log::getPathToLog('Nut_free_update') . ' 2>&1 &';

		//Installation des dependences
		exec($cmd);
	}	

	public function postSave() {				
		//Boucler sur le tableau contenant les equipements
		//La variable $idx représente l'index de l equipement en base 0
		//La variable $info qui lui est associée est un tableau contenant les valeurs des commandes de cet equipement

		$idx = 0;

		foreach(self::$_infosMap as $idx=>$info)
		{
			//Recuperation des informations sur base du logicalId pour chaque equipement
			$Nut_freeCmd = $this->getCmd(null, $info['logicalId']);
			
			if (!is_object($Nut_freeCmd)) {
				$Nut_freeCmd = new Nut_freeCmd();
				$Nut_freeCmd->setLogicalId( $info['logicalId']);
				$Nut_freeCmd->setName(__( $info['name'], __FILE__));
					if(isset($info['unite'])){
						$Nut_freeCmd->setUnite($info['unite']);
				}

				$Nut_freeCmd->setOrder($info['order']);
				
				//Assignation du template si defini
				if(isset($info['template_dashboard'])) 
					$Nut_freeCmd->setTemplate('dashboard', $info['template_dashboard']);		
			}
			
			$Nut_freeCmd->setType($params['type'] ?: 'info');
			
			if(isset($info['subtype'])){
					$Nut_freeCmd->setSubType($info['subtype']);
			}else{
				$Nut_freeCmd->setSubType('numeric', $info['subtype']);
			}
			
			if(isset($info['isVisible']))
			$Nut_freeCmd->setIsVisible($params['isVisible']);
			$Nut_freeCmd->setEqLogic_id($this->getId());	
			$Nut_freeCmd->save();
		}

		//Rafraichir immediatement les informations apres la sauvegarde de l equipement
		$this->getInformations();
	}
	
 	public function toHtml($_version = 'dashboard')	{
		$replace = $this->preToHtml($_version);
		if (!is_array($replace)) {
			return $replace;
		}
		$_version = jeedom::versionAlias($_version);
		$cmd_html = '';
		$br_before = 0;
		
		foreach(self::$_infosMap as $idx=>$info)
		{
			$cmd = $this->getCmd(null,$info['logicalId']);
			$replace['#'.$info['logicalId'].'#'] = (is_object($cmd)) ? $cmd->execCmd() : '';
			$replace['#'.$info['logicalId'].'id#'] = is_object($cmd) ? $cmd->getId() : '';
			$replace['#'.$info['logicalId'].'_display#'] = (is_object($cmd) && $cmd->getIsVisible()) ? '#'.$info['logicalId'].'_display#' : "none";
		}

		foreach ($this->getCmd(null, null, true) as $cmd) {
			if (isset($replace['#refresh_id#']) && $cmd->getId() == $replace['#refresh_id#']) {
				continue;
			}
			if ($br_before == 0 && $cmd->getDisplay('forceReturnLineBefore', 0) == 1) {
				$cmd_html .= '<br/>';
			}
			if (isset($replace['#background-color#'])) {
			$cmd_html .= $cmd->toHtml($_version, '', $replace['#background-color#']);
			}
			$br_before = 0;
			if ($cmd->getDisplay('forceReturnLineAfter', 0) == 1) {
				$cmd_html .= '<br/>';
				$br_before = 1;
			}
		}
		
		$html = template_replace($replace, getTemplate('core', $_version, 'Nut_free','Nut_free'));
		
		return $html;
	}

    public function getInformations() {
	    //Si l equipement n est pas actif, sortie de fonction, inutile de continuer le traitement
		if (!$this->getIsEnable()) return;

		$ip = $this->getConfiguration('addressip');
		$ups = $this->getConfiguration('UPS');
		$ssh_op = $this->getConfiguration('SSH_select');
		$cmd = $this->getCmd(null,'ssh_op');
		if(is_object($cmd)){
			$cmd->event($ssh_op);
		}
		
		$equipement = $this->getName();
		log::add('Nut_free', 'debug','Tentative de connexion sur l equipement ' .$equipement );

		//Connection en fonction de si on a choisi de passer par le ssh ou non
		// ssh_op = 0 : sans SSH
		// ssh_op = 1 : avec SSH
		if ($ssh_op == '0')
		{
			log::add('Nut_free', 'debug','Tentative de connexion non SSH' );
	
			//Si aucune configuration manuelle pour l UPS on passe en automatique
			if ($ups==''){
				$upscmd="upsc -l ".$ip." 2>&1 | grep -v '^Init SSL'";
				$ups = exec($upscmd);	
			}
		}else{
			$user = $this->getConfiguration('user');
			$pass = $this->getConfiguration('password');
			$port = $this->getConfiguration('portssh');
			$cmd = $this->getCmd(null,'cnx_ssh');

			log::add('Nut_free', 'debug','Tentative de connexion avec SSH');
			
			//Verification si la connexion SSH peut etre etablie
			if (!$sshconnection = new SSH2($ip,$port)){
				//SSH pas disponible sur l IP et le port indique
				if(is_object($cmd)){
					$cmd->event('KO');
				}

				log::add('Nut_free', 'error', 'Connexion SSH impossible');
				log::add('Nut_free', 'debug', 'Connexion SSH impossible');

				return false; // Sortie de fonction si on ne sait pas etablir la connexion
			}else{
				//Connexion SSH disponible sur l IP et le port indique, reste a s authentifier
				log::add('Nut_free', 'debug', 'Connexion SSH disponible, authentication en cours ...');
		
					if (!$sshconnection->login($user, $pass)){
						if(is_object($cmd)){
							$cmd->event('KO');							
						}

						log::add('Nut_free', 'error', 'Echec d authentification SSH');
						log::add('Nut_free', 'debug', 'Echec d Authentification SSH');

						return false; //Sortie de fonction car authentification echouee
					}else{
						//Authetification reussie
						//Si aucune configuration manuelle pour l UPS on passe en automatique
						if ($ups==''){
							$upscmd = "upsc -l 2>&1 | grep -v '^Init SSL'";					
							$ups_auto = $sshconnection->exec($upscmd); 
							$ups = substr($ups_auto, 0, -1);
						}
						
						if(is_object($cmd)){
							$cmd->event('OK');							
						}

						log::add('Nut_free', 'debug', 'Authentification SSH reussie');
					}
				}	
		}
		
		log::add('Nut_free', 'debug',' --------Debut du rafraichissement des valeurs--------' );
		foreach(self::$_infosMap as $idx=>$info)
		{
			if(isset($info['cmd']) && ($info['enabled']=='true'))
			{		
				//Execution des commandes pour recuperer les infos
				if ($ssh_op == '0')
				{
					//Mode non SSH
					/* 2>&1 permet de recuperer l'erreur et la traiter */
					$cmdline = "upsc ".$ups."@".$ip." ".$info['cmd']." 2>&1 | grep -v '^Init SSL'";
					$result = exec($cmdline);
					
				}else{
					//Mode SSH
					$cmdline = "upsc ".$ups." ".$info['cmd']." 2>&1 | grep -v '^Init SSL'";					
					$resultoutput = $sshconnection->exec($cmdline);
					$result = $resultoutput;
				}
              			
				//Si la commande retourne une erreur la fonctionalite n est pas dispo, on desactive la commande associee dans jeedom
				if (strstr($result,'not supported by UPS')){
					$cmd = $this->getCmd(null,$info['logicalId']);
					$cmd->setIsVisible(0);
					$cmd->setEqLogic_id($this->getId());
					$cmd->save();
					log::add('Nut_free', 'debug', $equipement.' UPS commande non supportee : '.$info['name'].' : '.$result);
					continue; //On skip la suite pour passer a la commande suivante
				}
				
				//Conversion pour concatener la marque et le modele
				if ($idx==0){
					$Marque = $result;
				}
				if ($idx==1){
					$result = $Marque.' '.$result;
				}
				
				//Si l UPS n est pas online on force la valeur du voltage d entree a 0
				if (($info['logicalId']=='input_volt') & (stristr($result,'OL')==False)){
					$result = 0;
				}
              
             	//Calcul du temps restant sur batterie en minutes
            	if (($info['logicalId']=='batt_runtime_min') ||($info['logicalId']=='timer_shutdown_min')){
					settype($result, "float");
					$result = (int)($result/60);
                }

				//Rafraichir la valeur de la commande dans jeedom via un post event
				$cmd = $this->getCmd(null,$info['logicalId']);
				if(is_object($cmd)){
					$cmd->event($result);				
				}				
			}
		}
		log::add('Nut_free', 'debug',' ---------Fin du rafrachissement des valeurs----------' );	
	}
	
	public function checkConnectivity() {
		$ip 		= $this->getConfiguration('addressip');
		$user 		= $this->getConfiguration('user');
		$pass 		= $this->getConfiguration('password');
		$port		= $this->getConfiguration('portssh');
		$equipement = $this->getName();
	
		if (!$ssh = new SSH2($ip,$port)) {
			log::add('Nut_free', 'error', 'connexion SSH KO pour '.$equipement);
		}else{
			if (!$ssh->login($user, $pass)){	
			log::add('Nut_free', 'error', 'Authentification SSH KO pour '.$equipement);
			}
		}
	}
}

class Nut_freeCmd extends cmd {
/*     * *************************Attributs****************************** */
	public static $_widgetPossibility = array('custom' => false);
	
/*     * *********************Methode d'instance************************* */
	public function execute($_options = null) {
		$eqLogic = $this->getEqLogic();

		if ( $this->GetType = "action" ) {
			$eqLogic->getCmd();
			$eqLogic->checkConnectivity();
		} else {
            throw new Exception(__('Commande non implémentée actuellement', __FILE__));
		}
        return true;
	}
}

?>
