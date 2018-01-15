<?php
require_once('php/config.php');
require_once('php/Classes/Purify.class.php');
require_once('php/Classes/Database.class.php');
require_once('php/functions.php');

class DB extends Database_Base {}

$db = new DB();

session_start();
ob_start();
//ini_set('display_errors', 'off');
//error_reporting(0);
 
// Connection creation
$m = new Memcached();
$cacheAvailable = $m->addServer(MEMCACHED_HOST, MEMCACHED_PORT);
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
<title>The world's simplest social networking app!</title>

<link media="all" rel="stylesheet" type="text/css" href="/css/jms_styles.css?v=1" />
</head>

<body>

<header>
    <section>
        <h1>The world's simplest social networking app!</h1>
        <nav>
            <ul>
                <li><a href="/">HOME</a></li>
                <?php
                if(isset($_SESSION['user']))
                {
                ?>
                    <li><a href="/profile/">PROFILE</a></li>
                    <li><a href="/logout/">LOGOUT</a></li>
                <?php
                }
                else
                {
                ?>
                    <li><a href="/join/">JOIN</a></li>
                    <li><a href="/login/">LOGIN</a></li>
                <?php
                }
                ?>
            </ul>
        </nav>
    </section>
</header>

<main>

<?php
if(!empty($_GET['page']))
{
    $page = str_replace('/','',$_GET['page']) . '.php';
    if(file_exists('pages/' . $page ))
    {
        require_once('pages/' . $page);
    }
    else
    {
        require_once('error_404.php');
    }
}
else
{
    require_once('pages/home.php');
}
?>

</main>

<footer>
    <section>
        Copyright &copy; <?php echo date('Y'); ?> TWSSNA, All rights reserved.
    </section>
</footer>

</body>
</html>
<?php
ob_end_flush();
?>
