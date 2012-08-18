<?php
/**
 * Index file
 *
 * PHP Version 5.3.6
 * 
 * @category SSH
 * @package  WebSSH
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     https://code.google.com/p/php-ssh-tools/
 */
require_once "classes/dom-enhancer/XMLDocument.php";
require_once "classes/WebSSH.php";
session_start();
$doc=new DOMenhancer_XMLDocument("WebSSH".(isset($_SESSION["host"])?" - ".$_SESSION["host"]:""), true);
$dom=$doc->DOM;
if (isset($_POST["host"]) || isset($_SESSION["host"])) {
    $dom->head->addElement("script", null, array("src"=>"scroll.js"));
    $host=isset($_POST["host"])?$_POST["host"]:$_SESSION["host"];
    $ssh=new WebSSH($host);
    if ($ssh->auth() && isset($_POST["cmd"])) {
        $ssh->exec($_POST["cmd"]);
       
    }
    $dom->body->addElement("div", null, array("id"=>"shell", "class"=>"shell"))->addElement("pre", $_SESSION["shell"]);
    $dom->body->addForm(
        "index.php", array(
            array("type"=>"text", "id"=>"cmd", "name"=>"cmd", "focus"=>true),
        ), "POST", false
    );
    
} else {
    $dom->body->addForm(
        "index.php",
        array(
            "label"=>_("Host:"), "type"=>"text", "id"=>"host", "name"=>"host", "focus"=>true
        ),
        "POST"
    );
}
$doc->display();
?>
