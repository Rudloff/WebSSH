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
require_once "classes/WebSSH.php";
session_start();
?>
<script src="scroll.js"></script>
<?php
if (isset($_POST["host"]) || isset($_SESSION["host"])) {
    $host=isset($_POST["host"])?$_POST["host"]:$_SESSION["host"];
    $ssh=new WebSSH($host);
    if ($ssh->auth() && isset($_POST["cmd"])) {
        $ssh->exec($_POST["cmd"]);
    }
    ?>
    <div id="shell" class="sell">
    <pre><?php echo $_SESSION["shell"]; ?></pre>
    </div>
    <form method="post" action="index.php">
        <label for="host">Command:</label>
        <input type="text" id="cmd" name="cmd" focus />
        <input type="submit" />
    </form>
    <?php
    
} else {
    ?>
    <form method="post" action="index.php">
        <label for="host">Host:</label>
        <input type="text" id="host" name="host" focus />
        <input type="submit" />
    </form>
    <?php
}
?>
