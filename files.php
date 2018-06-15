<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: File uploader
//- Description: this file handles file uploads which were initiated through a form
//- Author: Aaron Mann
//- Updated: 09 January 2014

//- Notes: 
/*
right now error messages are being created but they are not being printed anywhere except DEV:DEBUG
*/

//- File Upload Class
class Fetch_Files {

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- variables
	public $Cfg= array(); //- your file settings
	public $Files= array(); //- the data submitted to a form
	public $File= array(); //- the data reformatted
	public $Msg= array(); //- an error message array

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- construct / execute
	public function __construct($cfg,$files) {

//-		global $Form_Settings;

		//- import settings and data arrays
		$this->Cfg = $cfg;
		$this->Files = $files;
		$indx = 0;

		//- cycle the array
		foreach( $this->Files as $field_name => $filedat) {

			//- collect file data to reformatted array
			$this->File[$indx]['name']		= $filedat['name'];
			$this->File[$indx]['tmp_name']	= $filedat['tmp_name'];
			$this->File[$indx]['type']		= $filedat['type'];
			$this->File[$indx]['size']		= $filedat['size'];
			$this->File[$indx]['error']		= $filedat['error'];
			$this->File[$indx]['ext']		= strtolower(strrchr($filedat['name'],'.'));
			$this->File[$indx]['full']		= $this->Cfg['uploads'].$filedat['name'];
			$this->File[$indx]['passed']	= 0;

			//- inspection:
			if( $this->File[$indx]['error'] === UPLOAD_ERR_OK ) {

/* add this check - makes sure a file was uploaded and not some erroneous data. you can grab the size at the same time and verify that later if desired
if (is_uploaded_file($filename)) {
	$size = filesize($filename);
}
*/
				//- check if extension is allowed
				if( in_array( $this->File[$indx]['ext'], $this->Cfg['allowed'] ) ) {

					//- next check size limit
					if( $this->File[$indx]['size'] <= $this->Cfg['max_bytes'] ) {

						//- next check if file exists
						if( file_exists($this->File[$indx]['full']) ) {

							//- do a switch / case [replace, rename, reject]
							switch ($this->Cfg['allowed']) {

								case 'replace': //- replace is PHP's default action
									$this->File[$indx]['passed']=1;
									break;

								case 'rename':
									$md5dt=date().time();
									$this->File[$indx]['name']= md5($md5dt).rand().$this->File[$indx]['ext'];
									$this->File[$indx]['passed']=1;
									break;

								default: //- reject is our new default action
									$this->Msg[]='Error: File rejected, file name already in use. Rename the file and try again. ['.$this->File[$indx]['name'].']';

							} //- END: switch

						} else //- file does not exist
							$this->File[$indx]['passed']= 1;

					} else 
						$this->Msg[]='Error: File size limit exceeded. ['.$this->File[$indx]['name'].']';

				} else 
					$this->Msg[]='Error: File extension disallowed by site. ['.$this->File[$indx]['name'].']';

			} else 
				$this->Msg[]=Fetch_Files::file_upload_error($this->File[$indx]['error']);

			//- incriment the index counter
			$indx++;

		} //- END: foreach loop

		//- do another foreach loop (on the new array) to do the actual file moving (exclude where 'passed' still == 0)
		foreach( $this->File as $idx => $theFile ) {

			if( $theFile['passed'] === 1 ) {

				$File_Uploaded=@move_uploaded_file($theFile['tmp_name'],$theFile['full']);

				if(!$File_Uploaded) 
					$this->Msg[]='Error: File upload failed: might exceed server limit such as size, permission is denied or upload folder may not exist (path could be incorrect).';
			}

		} //- END: foreach loop

	} //- END: construct

	public function file_upload_error($err) {
		switch ($err) {
			case UPLOAD_ERR_INI_SIZE:
				return 'The uploaded file exceeds the "upload_max_filesize" directive in php.ini.';
			case UPLOAD_ERR_FORM_SIZE:
				return 'The uploaded file exceeds the "MAX_FILE_SIZE" attribute that was specified in the HTML form.';
			case UPLOAD_ERR_PARTIAL:
				return 'The uploaded file was only partially uploaded.';
			case UPLOAD_ERR_NO_FILE:
				return 'No file was selected for upload.';
			case UPLOAD_ERR_NO_TMP_DIR:
				return 'Missing a temporary upload folder.';
			case UPLOAD_ERR_CANT_WRITE:
				return 'Failed to write file to disk. Check permissions.';
			case UPLOAD_ERR_EXTENSION:
				return 'File upload stopped by extension.';
			default:
				return 'Unknown upload error.';
		}
	}

} //- END: class

/*
	//- create a function for saving the file
	function SaveThisFile($FileName, $The_Data) {
		global $n;
		$iFile=fopen($FileName, 'w');
		if( $iFile == true ) {
			fwrite($iFile, $The_Data);
			fclose($iFile);
			echo '<div class="info_mess" align="left"><b>+</b> Settings updated. You may click any visible button to navigate away from this page.</div>'.$n;
			return true;
		} else {
			echo '<div class="error_mess"><b>X</b> Update failed. Could not open file for writing.</div>'.$n;
			return false;
		}
	} //- END: function

		//- set file name
		$FileName = "inc/data/switches.php";

		//- create file contents
		$The_Data ='<?php'.$n.$n;
		$The_Data.='// HaL Switchboard Settings (Data)'.$n.$n;
		$The_Data.='// this file is saved out dynamically by a form process'.$n;
		$The_Data.='// all values should be binary zero or one'.$n.$n.$n;
		$The_Data.='// SECURITY: check for valid request or deny'.$n;
		$The_Data.='if( !defined(\'HAL_PUB_REQ\') ) {'.$n;
		$The_Data.=$t.'header("location:../../");'.$n;
		$The_Data.=$t.'exit(\'Invalid request. Please <a href="../../">back up</a> and try again.\');'.$n;
		$The_Data.='}'.$n.$n.$n;
		$The_Data.='// SETTINGS:'.$n.$n;
		$The_Data.='$HaL["set"]["lockdown"]         = '.$sw[1].';'.$n;
		$The_Data.='$HaL["set"]["error"]            = '.$sw[2].';'.$n;
		$The_Data.='$HaL["set"]["error_test"]       = '.$sw[3].';'.$n;

		//- * ADD NEW SWITCH: a new line will need duplicated here, like the line above but custom for the element *

		$The_Data.=$n.'?>';
		//- save file
		$R4M['file_saved'] = SaveThisFile($FileName, $The_Data);



//- - - - - - - - - - - - - - - - - - - - - - - - - - - - -
//- SAVE FILE FUNCTION:
//- - - - - - - - - - - - - - - - - - -
//- PHP and TXT only are currently supported
if(!function_exists('Save_This_File')) {
	//- pass in variables: path, file, extension, data
	function Save_This_File($p,$f,$e,$d) {
		//- import global variables
		global $ErrNum;
		global $ErrMsg;
		global $DAT;
		global $F_SET;
		$DaTa=""; //- define blank data string
		//- - - - - - - - -
		//- if PHP file selected, begin building data string with PHP open tag
		if(strtolower($e)=="php") {$DaTa .= "<?php \n";}
		//- cycle through the data
		for($Nbr=0; $Nbr < count($d); $Nbr++) {
			if(strtolower($e)=="txt") {
				if(!isset($DAT[$Nbr]["data"])) {$DAT[$Nbr]["data"]="";}
				if(isset($DAT[$Nbr]["file"])) {
					$DaTa .= "\n".'File Form = '.$DAT[$Nbr]["name"]."\n";
					$DaTa .= 'File Path = '.$DAT[$Nbr]["path"]."\n";
					$DaTa .= 'File Name = '.$DAT[$Nbr]["file"]."\n";
					$DaTa .= 'File Type = '.$DAT[$Nbr]["type"]."\n";
					$DaTa .= 'File Size = '.$DAT[$Nbr]["size"]."\n";
				} else {
					$DaTa .= $DAT[$Nbr]["name"].' = '.$DAT[$Nbr]["data"]."\n";
				} //- END: if is file
			} //- END: if TXT
			if(strtolower($e)=="php") {
				if(isset($DAT[$Nbr]["file"])) {
					if(!$Ctr){$Ctr=0;}
					$DaTa .= "\n".'$FF_['.$Ctr.']["FileForm"] = "'.$DAT[$Nbr]["name"].'"'.";\n";
					$DaTa .= '$FF_['.$Ctr.']["FilePath"] = "'.$DAT[$Nbr]["path"].'"'.";\n";
					$DaTa .= '$FF_['.$Ctr.']["FileName"] = "'.$DAT[$Nbr]["file"].'"'.";\n";
					$DaTa .= '$FF_['.$Ctr.']["FileType"] = "'.$DAT[$Nbr]["type"].'"'.";\n";
					$DaTa .= '$FF_['.$Ctr.']["FileSize"] = '.$DAT[$Nbr]["size"].";\n";
					++$Ctr;
				} else {
					$DaTa .= '$'.$DAT[$Nbr]["name"].' = "'.addslashes($DAT[$Nbr]["data"]).'";'."\n";
				} //- END: if is file
			} //- END: if PHP
		} //- END: for
		if(strtolower($e)=="php") {$DaTa .= "?> \n";} //- for PHP file, this closes the PHP with tag
		$file_=$p.$f.'.'.$e; //- assemble file name with path: $file_ = path/file.extension
		//- - - - - - - - -
		//- write out the file and check for overwrite setting
		if(!isset($F_SET["file"]["overwrite"])) {
			$DatFilename="idxnum.php";
			if(!file_exists($DatFilename)) {$dat_=@fopen($DatFilename,'w');@fwrite($dat_,'<?php $idxnum=0; ?>');@fclose($dat_);@chmod($DatFilename,0755);}
			if( file_exists($DatFilename)) {
				@include($DatFilename);
				++$idxnum;
				$dat_=@fopen($DatFilename,'w');
				$write_this='<?php $idxnum='.$idxnum.'; ?>';
				$_Write=@fwrite($dat_,$write_this);
				@fclose($dat_);
				if(!isset($_Write)){++$ErrNum; $ErrMsg[$ErrNum]="Failed to update the file index tracker.";}
				$file_=$p.$f.'_'.$idxnum.'.'.$e;
			} else {
				++$ErrNum; $ErrMsg[$ErrNum]="Unable to create index tracking file for preserving files. Attempting to overwrite master file instead ...";
			} //- END: if file exists
		} //- END: if do not overwrite
		//- - - - - - - - -
		//- we doublecheck to make sure we wrote file, if not, we try CHMOD then try again. some servers allow this, some do not
		$open_=@fopen($file_,'w');if(!isset($open_)){@chmod($file_,0755);$open_=@fopen($file_,'w');}
		if(isset($open_)){$write_=@fwrite($open_,$DaTa);@fclose($open_);
			if(!isset($write_)){@chmod($file_,0755);$write_=@fwrite($open_,$DaTa);@fclose($open_);
				if(!isset($write_)){++$ErrNum; $ErrMsg[$ErrNum]="Failed to write data to file [ $file_ ].";}
			} //- END: if file written
		} else {++$ErrNum; $ErrMsg[$ErrNum]="Failed to open file for writing [ $file_ ].";}
		if(function_exists('clearstatcache')){clearstatcache();}
		//- - - - - - - - -
	} //- END: function Save_This_File
} //- END: if function exist


		//- - - - - - - - - - - - - - - - - - -
		//- if selected to SAVE FILE, then save out the file
		if(isset($F_SET["file"]["save_file"]) and $F_SET["file"]["save_file"]==true) {
			$None_of_this_please=array("//","/","\\\\","\\",":","*","?",'"',"'","<",">","|",",",";","$","@","#","%","^","&");
			for($Nbr=0; $Nbr <= 1; $Nbr++) { //- I go over this stuff twice because i REALLY don't want any of this in the file names
				str_replace($None_of_this_please,"",$F_SET["file"]["file_name"]);
				str_replace($None_of_this_please,"",$F_SET["file"]["extension"]);
			} //- END: for
			Save_This_File($F_SET["file"]["path_location"],$F_SET["file"]["file_name"],$F_SET["file"]["extension"],$DAT);
			if(isset($F_SET["file"]["include"]) and strtolower($F_SET["file"]["extension"])=="php") {
				$INC_filename=$F_SET["file"]["path_location"].$F_SET["file"]["file_name"].'.'.$F_SET["file"]["extension"];
				if(!@include($INC_filename)) {++$ErrNum; $ErrMsg[$ErrNum]="Failed to include the saved file."; }
			} //- END: re-include saved file
		} //- END: save file
*/

?>
