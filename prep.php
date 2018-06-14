<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: Contact Form Prepend Settings
//- Description: settings that precede the operation of formgen
//- Author: Aaron Mann
//- Updated: 14 June 2018


//- - - - - - - - - - - - - - - - - - - - - - - -
//- sort end-of-line syntax
if( !defined('PHP_EOL') ) { 
	if(defined(PHP_OS)) { 
		switch( strtoupper( substr(PHP_OS,0,3) ) ) {
			case 'WIN': define('PHP_EOL',"\r\n"); break;
			case 'DAR': define('PHP_EOL',"\r"); break;
			default: define('PHP_EOL',"\n"); 
		}
	} else define('PHP_EOL',"\n"); 
}
$n = PHP_EOL;
$t = "\t"; 


//- - - - - - - - - - - - - - - - - - - - - - - -
//- Site File Handling Defaults (may not be needed here at all)
$Filing_Settings = array(
	'allowed'	=> array('png','jpg','jpeg','gif','txt','htm','html','tpl','ini','php','zip','rar'), //- list allowed file types to an array
	'uploads'	=> 'data/', //- specify the default directory to upload files to (use trailing slash, dir must exist!)
	'exists'	=> 'reject', //- enter: 'replace', 'rename', or 'reject' (!files are overwritten by default!)
	'max_bytes'	=> 102400 //- integer in bytes: file size limit (1k = 1024, 10k = 10240, 1MB = 1024000)
); //- END: Filing_Settings array


//- - - - - - - - - - - - - - - - - - - - - - - -
//- Default Form Settings - set elements to a seperate file
$Form_Settings = array(
	//- form attributes
	'set'	=> array(
		'id'		=> '',
		'name'		=> '',
		'class'		=> '',
		'action'	=> '', 
		'method'	=> 'post',
		'enctype'	=> 'application/x-www-form-urlencoded'
	), //- END: form settings
	//- form options
	'option'	=> array( //- use zero for false, any other integer for true
		'reload'		=> 0, //- will reload the form to the screen with values filled after post processing
		'onsubmit'		=> '', //- same as onsubmit="javascript" in the <form> tag
		'auto'			=> 0, //- tells the form generator if this is an auto form or not. auto forms are automatically loaded into pages. zero (off) by default. some system forms will enable this when loaded
		'redirect'		=> '', //- will redirect to the specified location after submission
		'send_email'	=> 0, //- will send the form data to email
		'mail_to'		=> array(''), //- who to send the data to - expects array but please set no more than 10 addresses (the mail function is slow)
		'mail_from'		=> 'no-reply@nowhere.xom', //- email address to set as reply-to
		'mail_name'		=> 'System Emailer', //- shown as sender name in the letter
		'mail_subj'		=> 'Site Email', //- subject / title of letter
		'mail_body'		=> 'This email was automatically sent by the site system.', //- email contents
		'save_file'		=> 0, //- will save the data to a file on the server
		'file_path'		=> 'temp/', //- set location to save file to
		'file_name'		=> '', //- set file name
		'file_ext'		=> '', //- set file extension
		'file_over'		=> 0, //- overwrite if exists?
		'file_rename'	=> true, //- rename if file exists?
		'file_inc'		=> 0, //- include file after saving? (only works for .php extension)
		'use_recaptcha'	=> false, //- use recaptcha plugin?
		'stop_xss'		=> true //- setting true will strip all scripting from data to prevent cross-site scripting attacks
	) //- END: form options
); //- END: Form_Settings array


?>
