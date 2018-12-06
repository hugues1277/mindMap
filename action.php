<?php 
function connexion()
{ 
    try
    {
      
//************************************************************************** SERVER INFOS *****************************
      
  $VALEUR_hote='localhost';                 // database adress
  $VALEUR_port='3306';                      // database port (default 3306)
  $VALEUR_nom_bd='mindMap';                 // database name

  $VALEUR_user='yourUser';                  // user name
  $VALEUR_mot_de_passe='YourPass';          // password
      
//************************************************************************** SERVER INFOS *****************************

  $bdd = new PDO('mysql:host='.$VALEUR_hote.';port='.$VALEUR_port.';dbname='.$VALEUR_nom_bd, $VALEUR_user, $VALEUR_mot_de_passe, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')); // MySQL connection
    }
    catch(Exception $e)
    { 
    die('------------------------------------- Erreur : db ------------------------------------------');
  }
return $bdd;
}

function afficher_database_table( $table, $order, $where )    // display data
   {
    if ($order){
        $reponse = connexion()->query("SELECT * FROM $table ORDER BY +$order");
    } else {
        $reponse = connexion()->query("SELECT * FROM $table");
    }
  
    $retours = array();
    while ($donnees = $reponse->fetch())
        {
         $retours[]=$donnees;
        }
    $reponse->closeCursor(); // request end
    return $retours;
    }


if($_POST['action']){
  
function secure($string){
	return addslashes( htmlentities($string,NULL,'UTF-8'));  //addslashes
}

$_ = array();
foreach($_POST as $key=>$val){
	$_[$key]=secure($val);
}
foreach($_GET as $key=>$val){
	$_[$key]=secure($val);
}

$result['state']  = 'No response';

switch($_['action']){
    
  case 'inserer_mindMap':		
    
    $id = $_['id'];
    $name = $_['name'];
    $url_name = $_['url_name'];
    $json = '{
              "setting":{"bubbleColor": "rgb(0, 105, 181)","lineColor": "rgb(0, 0, 0)"},
              "bubble":[
                {
                  "id":"0",
                  "x":4000,
                  "y":4000,
                  "title":"'.$url_name.'",
                  "text":"",
                  "url":"",
            "color":"rgb(0,105,181)"
                }
              ],
              "link":[]
            }';
    
    $rep = connexion()->exec("INSERT INTO `mindMap` (`id`, `name`, `json`) VALUES ('$id', '$name', '$json');");
    if($rep){
      $result['state']  = $id; //'MindMap insert';
    }		
    
  break;
  case 'remove_mindMap':	
    
    $id=$_['id'];
    $rep = connexion()->exec("DELETE FROM `mindMap` WHERE `mindMap`.`id` = $id");
    if($rep) 			
      $result['state']  = 'MindMap remove';
  break;	
  case 'update_mindMap':		
    
    $id=$_['id'];
    $json=$_['json'];
    $json = str_replace("null,","",$json);                  // rm null var
    $json = str_replace(",null","",$json);                  // rm null var
    $rep = connexion()->exec("UPDATE `mindMap` SET `json` = '$json' WHERE `mindMap`.`id` = $id");
    if($rep) 			
      $result['state']  = 'MindMap update';
  break;
  case 'rename_mindMap':		
    
    $id=$_['id'];
    $name=$_['name'];
    $rep = connexion()->exec("UPDATE `mindMap` SET `name` = '$name' WHERE `mindMap`.`id` = $id");
    if($rep) 			
      $result['state']  = $id;
  break;
}

$result['mysql'] = json_encode( connexion()->errorInfo() );
if( $result['state']!= "none")
	echo '{"result":"'.$result['state'].'","mysql":'.$result['mysql'].' }';
  
}
?>
