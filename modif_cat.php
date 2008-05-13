<?

 session_start();

if (!isset($_SESSION['id'])) {
  include('error.php');
  die();
}


// Inclusion de la librairie
require_once('./smarty/Smarty.class.php');
require_once('./include/MySQL.php');

// Instanciation d'un l'objet Smarty
$oSmarty = new Smarty();

// MySQL
$mysql = new MySQL('db735.1and1.fr','dbo185303395','pUBWrNeN','db185303395');


// Modification
if(isset($_POST['modifier'])) {
	extract($_POST);
	$query = "SELECT * FROM categories WHERE id='$categorie'";
	$result = $mysql->query($query);
	$line = mysql_fetch_assoc($result);
	$oSmarty->assign("CAT_NAME",$line['name']);
	$oSmarty->assign("CAT_ID",$line['id']);
}





// Affichage du template après compilation
$oSmarty->debugging = false;
$oSmarty->display('modif_cat.html');
?>


