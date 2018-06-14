<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: Form Post Data Collector
//- Description: Fetches $_POST data submitted from forms and cleans it into a new array
//- Author: Aaron Mann
//- Updated: 14 June 2018

/* Notes: 
this file has no output unless there's dev debugging.

fetch currently is not handling file uploads. a new class was created for that
form options are not being applied, sending email, saving a formatted file all need handling still
*/


//- page template class
class Fetch_Data {

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- variables
	public $Setting		= array(); //- your form settings, elements, attributes and values
	public $Input		= array(); //- the data submitted to a form
	public $Clean		= array(); //- the sanitized data
	private $safe_pass	= 1; //- a tracker used for reCaptcha

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- construct / execute
	public function __construct( $settings, $data ) {

		//- import page and filing settings
		global $Page_Settings;
		global $Filing_Settings;
		global $Site_Set;
		global $Base;
		global $n;

		//- import form settings and post data
		$this->Setting = $settings;
		$this->Input = $data;

		//- check for file upload and handle them
		if( isset($_FILES) and count($_FILES) > 0 and isset($Filing_Settings) ) 
			$Fetch_Files = new Fetch_Files($Filing_Settings,$_FILES);

		//- check for recaptcha usage
		$Temp['recap_filename'] = $Base.'mods/recaptcha/recaptchalib.php';
		if( isset($this->Setting['option']['use_recaptcha']) 
		and $this->Setting['option']['use_recaptcha'] != false 
		and file_exists($Temp['recap_filename'])
		and isset($Site_Set['recap_pub_key'])
		and strlen($Site_Set['recap_pri_key']) > 1
		and $Site_Set['isLocal'] !== true
		) { 
			require_once($Temp['recap_filename']);
			//- send values to the check method
			$Temp['recap_resp'] = recaptcha_check_answer(
				$Site_Set['recap_pri_key'], 
				$_SERVER["REMOTE_ADDR"], 
				$_POST["recaptcha_challenge_field"], 
				$_POST["recaptcha_response_field"]
			);
			//- check for an invalid entry
			if( !$Temp['recap_resp']->is_valid ) {
				$Page_Settings['body']['message'] = 'The reCAPTCHA was not entered correctly. You will have to go back and try it again.';
				$this->safe_pass = 0;
			}

		}

		//- check for safe pass, will only be set to zero (fail) upon recaptcha being active and returning an incorrect match
		if( $this->safe_pass === 1 ) {

			//- cycle elements, check settings, filter each input as requested:
			foreach( $this->Input as $k => $v ) {

				//- first, get those magic quotes (or any other) slashes out of there. we want unaltered data to start with
				//- also, make sure there's no ASCII characters before we start filtering

				if( !is_array($v) ) $v = stripslashes($v);
				if( !is_array($v) ) $v = html_entity_decode($v, ENT_QUOTES); //- will convert both quotes
//-				$v = html_entity_decode($v); //- does not convert single quotes

				//- check for pre_op
				if( isset($this->Setting['option']['pre_op']) and $this->Setting['option']['pre_op'] != 0 ) {
					$v = str_ireplace($this->Setting['option']['pre_rem'], "", $v);
				} //- END: pre_op
	
				//- check for XSS filter toggle
				if( isset($this->Setting['option']['stop_xss']) and $this->Setting['option']['stop_xss'] === true ) {

					//- make sure definition file exists
					if( file_exists($Base.'js_attribs.php') ) {
						//- include javascript attribute definitions
						include $Base.'js_attribs.php';
						//- strip javascript attributes
						$v = str_ireplace($js_DefAttributes, "", $v);
					} 

					//- make sure definition file exists
					if( file_exists($Base.'script_types.php') ) {
						//- include javascript attribute definitions
						include $Base.'script_types.php';
						//- strip javascript attributes
						$v = str_ireplace($Def_ScriptTypes, "", $v);
					} 
					//- strip javascript tags
					$v = str_ireplace(array('<script>','<script','</script>',' type="javascript">'," type='javascript'>",'>',$n), "", $v);

				} //- END: stop_xss

				//- check tags
				if( isset($this->Setting['option']['use_html']) and $this->Setting['option']['use_html'] == 0 ) {

					if( isset($this->Setting['option']['use_php']) and $this->Setting['option']['use_php'] == 0 ) {
						$v = strip_tags($v, $this->Setting['option']['html_tags']);
					} else {
						$v = preg_replace("@<\?@", "#?#", $v); //- replace php tags so they do not get stripped 
						$v = strip_tags($v,$this->Setting['option']['html_tags']); //- strip tags normally
						$v = preg_replace("@#\?#@", "<?", $v); //- restore php and comment tags to their origial form
					} //- END: use_php

				} //- END: use_html

				//- check for extra removal
				if( isset($this->Setting['option']['rem_xtra']) and $this->Setting['option']['rem_xtra'] != 0 ) {
					$v = str_ireplace($this->Setting['option']['rem_this'], "", $v);
				} //- END: rem_xtra

				//- check for regular expression removal
				if( isset($this->Setting['option']['rem_regex']) and $this->Setting['option']['rem_regex'] != 0 ) {
					$v = preg_replace($this->Setting['option']['r_pattern'], $this->Setting['option']['r_replace'], $v);
				} //- END: regex remove

				//- check for post_op
				if( isset($this->Setting['option']['post_op']) and $this->Setting['option']['post_op'] != 0 ) {
					$v = str_ireplace($this->Setting['option']['post_rem'], "", $v);
				} //- END: post_op

				//- check for HTML encode 
				if( isset($this->Setting['option']['enc_html']) and $this->Setting['option']['enc_html'] != 0 ) {
					$v = htmlspecialchars($v);
				} //- END: enc_html

				//- add sanitized item to clean variable
				$this->Clean[$k] = $v;

			} //- END: foreach

		} //- END: if safe_pass check

		//- - - - - - - - - - - - - - - - - - - - - - - -

		//- redirect, if set
		if( isset($this->Setting['option']['redirect']) 
		and strlen($this->Setting['option']['redirect']) > 0 
		) {
			//- check if we can redirect from page_gen class
			if( isset($Page_Settings['redirect']) ) {
				if( strlen($this->Setting['option']['redirect']) >0 ) 
					$Page_Settings['redirect'] = $this->Setting['option']['redirect'];
			} else {
				//- page_gen not being used, redirect a different way

				/*
				if( !headers_sent() ) {
				header('Location: http://www.example.com/');
				exit;
				}
				*/

			}

		} //- END: redirect

		//- check for email op
		if( isset($this->Setting['option']['send_mail']) 
		and $this->Setting['option']['send_mail'] != 0 ) {

			/*
			'mail_to'	=> array(''), //- who to send the data to - expects array but please set no more than 10 addresses
			'mail_from'	=> 'no-reply@nowhere.xom', //- email address to set as reply
			'mail_name'	=> 'System Emailer', //- name to show as sender
			'mail_subj'	=> 'Site Email', //- subject / title of email
			'mail_body'	=> 'This email was automatically sent by the site system.', //- email contents
			*/
		} //- END: send_mail

		//- check for save file op
		if( isset($this->Setting['option']['save_file']) 
		and $this->Setting['option']['save_file'] != 0 ) {

			/*
			'file_path'	=> '', //- set location to save file to
			'file_name'	=> '', //- set file name
			'file_ext'	=> '', //- set file extension
			'file_over'	=> 0, //- overwrite if exists?
			'file_inc'	=> 0, //- include file after saving? (only works for .php extension)
			*/
		} //- END: save_file

		//- - - - - - - - - - - - - - - - - - - - - - - -

		/* REMAINING OPTIONS:
		'success'	=> '', //- will display this message on successful submission - leave blank for no message
		'use_msgs'	=> 1, //- use public display messages?
		*/

	//- - - - - - - - - - - - - - - - - - - - - - - -
	} //- END: function __construct

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- function to preserve PHP tags when requested ( for use with strip_tags() function )
	public function Names($parameter) {

	} //- END: function Names


	//- - - - - - - - - - - - - - - - - - - - - - - -
} //- END: class: Fetch_Data

?>
