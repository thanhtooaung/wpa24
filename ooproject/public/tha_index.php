<?php 

	class DB extends PDO
	{
		private $engine;
		private $host;
		private $database;
		private $user;
		private $pass;

		private $fields;
		private $tablename;

		private static $_instance;


		public function __construct()
		{
			$this->engine = 'mysql';
			$this->host = 'localhost';

			$this->database = 'wpa24_ci';
			$this->user = 'root';
			$this->pass = 'm123123';

	        $dns = $this->engine . ':dbname=' . $this->database . ";host=" . $this->host; 
	        parent::__construct($dns, $this->user, $this->pass); 

	        echo "Constructor Invoke ... <br /><br />";
		}

		public function __destruct()
		{
			echo "Destructor Invoke ...";
		}

		public function table($tablename)
		{
			if (!self::$_instance instanceof DB) {
				self::$_instance = new DB();
			}

			self::$_instance->fields = "*";
			self::$_instance->tablename = $tablename;

			return self::$_instance;
		}

		public function where(string ...$fields)
		{
			// Check whether the function has argument ...
			//
			if ($fields != null) 
			{
				$this->fields = implode(", ", $fields);
			}

			return self::$_instance;
		}

		public function get()
		{
			$querystring = "SELECT " . $this->fields . " FROM " . $this->tablename;
			echo "Query String -> " . $querystring . "<br/>";

			$prep = $this->prepare($querystring);
			$prep->execute();

			return $prep->fetchAll(PDO::FETCH_ASSOC);
		}

		public function save($data)
		{
			$fields = implode(", ", array_keys($data));
			$values = "'" . implode("', '", array_values($data)) . "'";

			$querystring = "INSERT INTO " . $this->tablename . " (" . $fields . ") VALUES (" . $values . ");";
			echo "Query String => " . $querystring . "<br/><br/>";

			// set connection properties ...
			$conn = new PDO("mysql:host=localhost;dbname=wpa24_ci", 'root', 'm123123');
		    
		    // set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    
		    // execute query
		    $conn->query($querystring);
		}

		public function delete($data)
		{
			$fields = implode(", ", array_keys($data));
			$values = "'" . implode("', '", array_values($data)) . "'";

			/*
			$querystring = "DELETE FROM " . $this->tablename . " WHERE (" . $fields . ") VALUES (" . $values . ");";
			echo "Query String => " . $querystring . "<br/><br/>";

			// set connection properties ...
			$conn = new PDO("mysql:host=localhost;dbname=wpa24_ci", 'root', 'm123123');
		    
		    // set the PDO error mode to exception
		    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		    
		    // execute query
		    $conn->query($querystring);
		    */
		}
	}

?>

<?php 

	$StudentList_1 = 	[
							'name'	=>	'Mya Mya',
							'email'	=>	'MyaMya@gmail.com'
						];

	$StudentList_2 = 	[
							'name'	=>	'Hla Hla', 
							'email'	=>	'HlaHla@gmail.com'
						];

	// insert data
	DB::table("students")->save($StudentList_1);

	// Students Table
	//
	$Students = DB::table("students")->get();

	foreach ($Students as $Student) 
	{
		echo $Student['id']. ' => ' . $Student['name'] . '<br />';
	}
	echo "<br /><br />";

	/*/ Users Table
	//
	$Users = DB::table("users")->where()->get();
	//$Users = DB::table("users")->where("id", "name", "email")->get();
	foreach ($Users as $User) 
	{
		echo $User['id'] . ', ' . $User['email'] . '<br />';
	}

	echo "<br/><br/>";
*/

	//DB::table("users")->save($StudentList_2);

?>
