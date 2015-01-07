<?php

/**
 * Upload and manage file from form
 */
class form_file {
	/**
	 * Path to the file
	 *
	 * @var string $file
	 */
	public $file;
	
	/**
	 * File extension
	 * 
	 * @var string $type
	 */
	public $type;
	
	/**
	 * Checks the filetype and uploads the file to the desired path.
	 *
	 * @param file $source
	 *        	POST source sent by the form
	 * @param string $type
	 *        	permited file extension
	 * @param string $path
	 *        	destination path
	 *        	
	 *        	@retval array Returns a 2D array containing CODE (1 = Success, 0 = Failure) and DATA (file path or error message)
	 */
	public function __construct($source, $type, $path) {
		$utp = strtoupper ( $type );
		$ct = $this->check_type ( $source, $type );
		if ($ct) {
			$uf = $this->upload_file ( $source, $path );
			if ($uf ["CODE"] == 1) {
				$target_file = $uf ["DATA"];
				$uploadOk = 1;
			} else {
				$target_file = "Sorry, there was an error uploading your file.";
				$uploadOk = - 1;
			}
		} else {
			$target_file = "Sorry, only $utp files are allowed.";
			$uploadOk = 0;
		}
		
		$this->file = $target_file;
		$this->type = strtolower ( $utp );
		
		$result ["CODE"] = $uploadOk;
		$result ["DATA"] = $target_file;
		
		return $result;
	}
	
	/**
	 * Deletes the file from the server
	 *
	 * @retval int Returns 1 in case it deleted the file succesfully, 0 otherwise.
	 */
	public function delete() {
		if (unlink ( $this->file )) {
			$result = 1;
		} else {
			$result = 0;
		}
		return $result;
	}
	
	/**
	 * Parses the file to a string
	 *
	 * @retval array Returns a 2D array containing CODE (1 = Success, 0 = Failure) and DATA (or error message)
	 */
	public function f2s() {
		$result ["CODE"] = 1;
		$result ["DATA"] = file_get_contents ( $this->file );
		if ($result === FALSE) {
			$result ["CODE"] = 0;
			$result ["DATA"] = "Error parsing file";
		}
		return $result;
	}
	
	/**
	 * Checks the file's extension against the permitted extension as defined by the user
	 *
	 * @param file $src
	 *        	The POST source sent by the form
	 * @param string $tp
	 *        	Permitted file extension
	 *        	
	 *        	@retval boolean Returns TRUE in case the file has de desired extension, FALSE if otherwise
	 */
	private function check_type($src, $tp) {
		$result = FALSE;
		$sfl = strtolower ( basename ( $src ["name"] ) );
		$stp = pathinfo ( $sfl, PATHINFO_EXTENSION );
		$dtp = strtolower ( $tp );
		if ($stp == $dtp)
			$result = TRUE;
		return $result;
	}
	
	/**
	 * Uploads the file to the defined path
	 *
	 * @param file $source
	 *        	The POST source sent by the form
	 * @param string $path
	 *        	Destination path where the file will be stored
	 *        	
	 *        	@retval array Returns a 2D array containing CODE (1 = Success, 0 = Failure) and DATA (or error message)
	 */
	private function upload_file($source, $path) {
		$target_dir = "$path/";
		$target_file = $target_dir . basename ( $source ["name"] );
		$uploadOk = 0;
		
		if (move_uploaded_file ( $source ["tmp_name"], $target_file )) {
			$uploadOk = 1;
		}
		
		$result ["CODE"] = $uploadOk;
		$result ["DATA"] = $target_file;
		
		return $result;
	}
}
class csv_file extends form_file {
	
	/**
	 * Parses a CSV file to an array
	 *
	 * @retval array Returns a mixed array with CODE (1 = success, 0 = error) and DATA (array or error message)
	 */
	public function get_csv() {
		if ($this->type == "csv") {
			$handle = fopen ( $this->file, "r" );
			$result ["CODE"] = 1;
			$result ["DATA"] = fgetcsv ( $handle );
		} else {
			$result ["CODE"] = 0;
			$result ["DATA"] = "NOT A CSV FILE";
		}
		return $result;
	}
}
?>
