<?php
abstract class Database_Base
{
	private static $dbconnect;
	private $dbhost, $dbname, $dbuser, $dbpassword;
	public $error = NULL;
	public function __construct($dbhost=DB_HOST, $dbname=DB_NAME, $dbuser=DB_USER, $dbpassword=DB_PASSWORD)
	{
		$this->dbhost = $dbhost;
		$this->dbname = $dbname;
		$this->dbuser = $dbuser;
		$this->dbpassword = $dbpassword;
	}
	public function dbConnect()
	{
		if (!self::$dbconnect)
		{
			try
			{
				self::$dbconnect = new PDO('mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname, $this->dbuser, $this->dbpassword, array(PDO::ATTR_PERSISTENT=>true));
			}
			catch(PDOException $e)
			{
				$this->$error = 'Connection failed: '.$e->getMessage();
			}
		}
		return self::$dbconnect;
	}
	public function execStoredProcedure($sp, array $params)
	{
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);

		$paramStr = '';
		foreach($params as $param)
		{
			$paramStr .= "'" . addslashes(trim($purifier->purify($param))) . "',";
		}
		$paramStr = substr($paramStr, 0, -1);
		$sql = " CALL " . $sp . "(" . $paramStr . ") ";

		$c = $this->dbConnect();
		$q = $c->prepare($sql);
		$q->execute();
		return $q->fetchAll();
	}
}
?>