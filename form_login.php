<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: User Login Form
//- Description: form used for logging users in
//- Author: Aaron Mann
//- Updated: 14 June 2018


//- define form settings
$Form_Settings = array(

	//- form attributes
	'set'		=> array(
		'id'		=> 'user_login_form',
		'name'		=> 'user_login_form',
		'class'		=> '',
		'action'	=> '', //- htmlentities($_SERVER['PHP_SELF']),
		'method'	=> 'post',
		'enctype'	=> 'application/x-www-form-urlencoded' //- multipart/form-data is only needed for file uploads, otherwise use: application/x-www-form-urlencoded or nothing
	), //- END: form settings

	//- form options
	'option'	=> array( //- use zero for false, any other integer for true
		'reload'	=> 0, //- will reload the form to the screen with values filled after post processing
		'auto'		=> 1, //- tells the form generator if this is an auto form or not. auto forms are automatically loaded into pages.
		'onsubmit'	=> '', //- same as onsubmit="javascript" in the <form> tag
		'redirect'	=> '', //- will redirect to the specified location after submission
		'send_email'=> 0, //- will send the form data to email
		'mail_to'	=> array(''), //- who to send the data to - expects array but please set no more than 10 addresses (the mail function is slow)
		'mail_from'	=> 'no-reply@nowhere.void', //- email address to set as reply-to
		'mail_name'	=> 'System Emailer', //- shown as sender name in the letter
		'mail_subj'	=> 'Site Email', //- subject / title of letter
		'mail_body'	=> 'This email was automatically sent by the site system.<br>', //- email contents
		'save_file'	=> 0, //- will save the data to a file on the server
		'file_path'	=> '', //- set location to save file to
		'file_name'	=> '', //- set file name
		'file_ext'	=> '', //- set file extension
		'file_over'	=> 0, //- overwrite if exists?
		'file_inc'	=> 0, //- include file after saving? (only works for .php extension)
		'stop_xss'	=> true, //- setting true will strip all scripting from data to prevent cross-site scripting attacks
	), //- END: form options

	//- form elements
	'element'	=> array( 0 => 

		array( //- fieldset
			'type'		=> 'fieldset',
			'title'		=> 'Login Form'
		),

		array( //- text
			'type'		=> 'text',
			'title'		=> 'Handle ',
			'name'		=> 'pub_login_handle',
			'id'		=> 'pub_login_handle',
			'class'		=> '',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> 'required',
			'readonly'	=> '',
			'placehold'	=> 'ID#, email address or alias',
			'follow'	=> 'Enter the login handle (ID#, alias or email address) that you registered with or have set in account options.',
			'value'		=> ''
		),

		array( //- password
			'type'		=> 'password',
			'title'		=> 'Password ',
			'name'		=> 'pub_login_password',
			'id'		=> 'pub_login_password',
			'class'		=> '',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> 'required',
			'readonly'	=> '',
			'placehold'	=> '',
			'follow'	=> 'Enter your pass word.',
			'value'		=> ''
		),

		array( //- submit
			'type'		=> 'submit',
			'name'		=> 'submit_login',
			'id'		=> 'submit_login',
			'class'		=> 'submit',
			'src'		=> '',
			'alt'		=> '',
			'js'		=> '',
//			'disabled'	=> 'disabled',
			'follow'	=> '',
			'value'		=> 'Log In'
		),

		array( //- fieldset
			'type'		=> 'fieldset'
		),

	), //- END: settings

); //- END: $Form_Settings

?>