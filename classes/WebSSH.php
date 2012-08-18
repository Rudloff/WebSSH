<?php
/**
 * WebSSH class
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  WebSSH
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     https://code.google.com/p/php-ssh-tools/
 */
 
 /**
 * Class to manage SSH shell
 *
 * PHP Version 5.3.6
 * 
 * @category Class
 * @package  WebSSH
 * @author   Pierre Rudloff <contact@rudloff.pro>
 * @license  http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License
 * @link     https://code.google.com/p/php-ssh-tools/
 */
class WebSSH
{
    /**
     * WebSSH constructor
     * 
     * @param string $host Host to connect to
     * 
     * @return void
     * */
    function __construct($host)
    {
        $this->host=$host;
        $this->connection=ssh2_connect($host);
    }
    
    /**
     * Send a command via SSH
     * 
     * @param string $cmd Command to execute
     * 
     * @return string Output or error
     * */
    function cmd($cmd)
    {
        $stream=ssh2_exec($this->connection, $cmd);
        stream_set_blocking($stream, true);
        return stream_get_contents($stream).
        stream_get_contents(ssh2_fetch_stream($stream, SSH2_STREAM_STDERR));
    }
    
    /**
     * Send authentication headers
     * 
     * @return void
     * */
    function authHeader()
    {
        header("WWW-Authenticate: Basic realm=\"".$this->host." (".ssh2_fingerprint($this->connection).")\"");
        header("HTTP/1.0 401 Unauthorized");
        header("Refresh: 0; url=index.php");
    }
    
    /**
     * Authenticate
     * 
     * @return bool
     * */
    function auth()
    {
        if (!isset($_SERVER["PHP_AUTH_USER"])) {
            $this->authHeader();
        }

        if (ssh2_auth_password(
            $this->connection, $_SERVER["PHP_AUTH_USER"],
            $_SERVER["PHP_AUTH_PW"]
        )) {
            $_SESSION["host"]=$this->host;
            if (empty($_SESSION["shell"])) {
                $_SESSION["shell"]=self::displayLine();
            }
            return true;
        } else {
            $this->authHeader();
            return false;
        }
    }
    
    /**
     * Display command line
     * 
     * @param string $cmd Command to execute
     * 
     * @return string
     * */
    function displayLine($cmd)
    {
        return $_SERVER["PHP_AUTH_USER"]."@".trim(self::cmd("hostname")).
        ":".trim(self::cmd("pwd"))."$&nbsp;".$cmd.PHP_EOL;
    }
    
    /**
     * Execute a command
     * 
     * @param string $cmd Command to execute
     * 
     * @return string Shell output
     * */
    function exec($cmd)
    {
        $output=self::displayLine($cmd).self::cmd($cmd);
        $_SESSION["shell"].=$output;
        return $_SESSION["shell"];
    }
}
?>
