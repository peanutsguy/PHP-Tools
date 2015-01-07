<?php
class data_sql {
	protected $conexion;
	public function __construct($dbase) {
		$this->conexion = $this->connect ( $dbase ["type"], $dbase );
	}
	private function connect($type, $conf) {
		$result ["TYPE"] = $type;
		
		switch ($type) {
			case "mysql" :
				$result ["CONEXION"] = mysqli_connect ( $conf ["host"], $conf ["user"], $conf ["pwd"], $conf ["schema"] );
				break;
			case "mssql" :
				$db_string = $conf ["host"] . "/" . $conf ["schema"];
				$result ["CONEXION"] = mssql_connect ( $db_string, $conf ["user"], $conf ["pwd"] );
				break;
			case "oracle" :
				$db_string = "(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=" . $conf ["host"] . ")(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED)(SID=" . $conf ["schema"] . ")))";
				
				$result ["CONEXION"] = oci_connect ( $conf ["user"], $conf ["pwd"], $db_string );
				break;
			case "postgresql" :
				$result ["CONEXION"] = pg_connect ( "host=" . $conf ["host"] . " dbname=" . $conf ["schema"] . " user=" . $conf ["user"] . " password=" . $conf ["pwd"] );
				break;
			default :
				$result ["TYPE"] = - 1;
				break;
		}
		
		return $result;
	}
	public function select($query) {
		$con = $this->conexion ["CONEXION"];
		$type = $this->conexion ["TYPE"];
		
		$result ["CODE"] = 0;
		$result ["MSG"] = "NO ACTION";
		$result ["DATA"] = array ();
		
		switch ($type) {
			case "mysql" :
				$select = mysqli_query ( $con, $query );
				if (! $select) {
					$result ["CODE"] = - 1;
					$result ["MSG"] = mysqli_error ( $con );
				} else {
					$n_s = mysqli_num_rows ( $select );
					if ($n_s > 0) {
						$result ["CODE"] = 1;
						$result ["MSG"] = "SUCCESS!";
						while ( $data = mysqli_fetch_assoc ( $select ) ) {
							$result ["DATA"] [] = $data;
						}
					} else {
						$result ["CODE"] = 2;
						$result ["MSG"] = "NO DATA";
					}
				}
				break;
			case "mssql" :
				break;
			case "oracle" :
				$parse = oci_parse ( $con, $query );
				$select = oci_execute ( $parse );
				if (! $select) {
					$result ["CODE"] = - 1;
					$result ["MSG"] = oci_error ( $parse );
				} else {
					$n_s = oci_num_rows ( $parse );
					if ($n_s > 0) {
						$result ["CODE"] = 1;
						$result ["MSG"] = "SUCCESS!";
						while ( $data = oci_fetch_assoc ( $parse ) ) {
							$result ["DATA"] [] = $data;
						}
					} else {
						$result ["CODE"] = 2;
						$result ["MSG"] = "NO DATA";
					}
				}
				oci_free_statement ( $parse );
				break;
			case "postgresql" :
				break;
			default :
				break;
		}
		return $result;
	}
	public function insert($query) {
		$con = $this->conexion ["CONEXION"];
		$type = $this->conexion ["TYPE"];
		
		$result ["CODE"] = 0;
		$result ["MSG"] = "NO ACTION";
		
		switch ($type) {
			case "mysql" :
				$select = mysqli_query ( $con, $query );
				if (! $select) {
					$result ["CODE"] = - 1;
					$result ["MSG"] = mysqli_error ( $con );
				} else {
					$result ["CODE"] = 1;
					$result ["MSG"] = "SUCCESS!";
				}
				break;
			case "mssql" :
				break;
			case "oracle" :
				break;
			case "postgresql" :
				break;
			default :
				break;
		}
		return $result;
	}
	public function update($query) {
		$con = $this->conexion ["CONEXION"];
		$type = $this->conexion ["TYPE"];
		
		$result ["CODE"] = 0;
		$result ["MSG"] = "NO ACTION";
		$result ["DATA"] = array ();
		
		switch ($type) {
			case "mysql" :
				break;
			case "mssql" :
				break;
			case "oracle" :
				break;
			case "postgresql" :
				break;
			default :
				break;
		}
	}
	public function delete($query) {
		$con = $this->conexion ["CONEXION"];
		$type = $this->conexion ["TYPE"];
		
		$result ["CODE"] = 0;
		$result ["MSG"] = "NO ACTION";
		$result ["DATA"] = array ();
		
		switch ($type) {
			case "mysql" :
				break;
			case "mssql" :
				break;
			case "oracle" :
				break;
			case "postgresql" :
				break;
			default :
				break;
		}
	}
}

?>