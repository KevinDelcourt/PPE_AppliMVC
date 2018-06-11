<?php
/**
 * api.php utilisée par l'application android
 *
 * permet de mettre à jour les frais d'un visiteur depuis une requête POST effectuée par l'application android
 *
 * @category  PPE
 * @package   GSB
 * @author    Kévin Delcourt
 */

	require 'class.pdogsb.inc.php' ;
	require 'fct.inc.php';

	$pdo = PdoGsb::getPdoGsb();

	//Test login
	//var_dump($pdo->getInfosVisiteur('dandre', 'oppg5'));

        
	function isTheseParametersAvailable($params){
		 //assuming all parameters are available 
		 $available = true; 
		 $missingparams = ""; 
		 
		 foreach($params as $param){
			 if(!isset($_POST[$param]) || strlen($_POST[$param])<=0){
				 $available = false; 
				 $missingparams = $missingparams . ", " . $param; 
			 }
		 }
		 
		 //if parameters are missing 
		 if(!$available){
			 $response = array(); 
			 $response['error'] = true; 
			 $response['message'] = 'Parameters ' . substr($missingparams, 1, strlen($missingparams)) . ' missing';
			 
			 //displaying error
			 echo json_encode($response);
			 
			 //stopping further execution
			 die();
		 }
 	}

 	$response = array();
 
	//if it is an api call 
	if(isset($_GET['apicall'])){
	 
			isTheseParametersAvailable(array('login','mdp','fraisF','fraisHF'));

			$visiteur = $pdo->getInfosVisiteur( $_POST['login'],  $_POST['mdp']);

			$frais =  explode('/',$_POST['fraisF']);
			$lesFrais = array();
			foreach($frais as $f){
				$lesFrais[explode('.',$f)[0]] = explode('.',$f)[1];//Est au format de la classe pdo
			}

			$frais =  explode('%',$_POST['fraisHF'],-1);

			$mois = date('Ym');
			if(is_array($visiteur)){
				
				if($pdo->estPremierFraisMois($visiteur['id'],$mois)){
					$pdo->creeNouvellesLignesFrais($visiteur['id'],$mois);
				}

				$pdo->majFraisForfait($visiteur['id'],$mois,$lesFrais);

				foreach($frais as $f){
					$fHF = explode('!',$f);

					$date = $fHF[2].date('/m/Y');
					$pdo->creeNouveauFraisHorsForfait($visiteur['id'],$mois,$fHF[1], $date ,$fHF[0]);
				}

				$response['message'] = "Success";


			}else{

				 $response['error'] = true; 
				 $response['message'] = "Identifiant ou mot de passe incorrect";
			}
	 			
	}

	echo json_encode($response);


