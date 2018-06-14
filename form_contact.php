<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: Contact Form
//- Description: definitions used in the creation of the contact form
//- Author: Aaron Mann
//- Updated: 14 June 2018


//- - - - - - - - - - - - - - - - - - - - - - - -
//- define form settings
$Form_Settings = array(

	//- form attributes
	'set'		=> array(
		'id'		=> 'site_contact_form',
		'name'		=> 'site_contact_form',
		'class'		=> '',
		'action'	=> '', //- htmlentities($_SERVER['PHP_SELF']),
		'method'	=> 'post',
		'enctype'	=> 'application/x-www-form-urlencoded' //- multipart/form-data is only needed for file uploads, otherwise use: application/x-www-form-urlencoded or nothing
	), //- END: form settings

	//- form options - * to be used only if formgen becomes handler also *
	'option'	=> array( //- use zero for false, any other integer for true
		'reload'		=> 0, //- will reload the form to the screen with values filled after post processing
		'auto'			=> 1, //- tells the form generator if this is an auto form or not. auto forms are automatically loaded into pages.
		'onsubmit'		=> '', //- same as onsubmit="javascript" in the <form> tag
		'redirect'		=> '', //- will redirect to the specified location after submission
		'send_email'	=> 0, //- will send the form data to email
		'mail_to'		=> array(''), //- who to send the data to - expects array but please set no more than 10 addresses (the mail function is slow)
		'mail_from'		=> 'no-reply@nowhere.xom', //- email address to set as reply-to
		'mail_name'		=> 'System Emailer', //- shown as sender name in the letter
		'mail_subj'		=> 'Site Email', //- subject / title of letter
		'mail_body'		=> 'This email was automatically sent by the site system.', //- email contents
		'save_file'		=> 0, //- will save the data to a file on the server
		'file_path'		=> '', //- set location to save file to
		'file_name'		=> '', //- set file name
		'file_ext'		=> '', //- set file extension
		'file_over'		=> 0, //- overwrite if exists?
		'file_inc'		=> 0, //- include file after saving? (only works for .php extension)
		'use_recaptcha'	=> true, //- use recaptcha plugin?
		'stop_xss'		=> true, //- setting true will strip all scripting from data to prevent cross-site scripting attacks
	), //- END: form options

	//- form elements
	'element'	=> array( 0 => 

		array( //- fieldset
			'type'		=> 'fieldset',
			'title'		=> 'Contact Form'
		),
		array( //- name
			'type'		=> 'text',
			'title'		=> 'Name: ',
			'name'		=> 'contact_name',
			'id'		=> 'contact_name',
			'class'		=> '',
			'size'		=> 40,
			'maxlength'	=> 60,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> 'Enter your name.',
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- email addy
			'type'		=> 'text',
			'title'		=> 'Email: ',
			'name'		=> 'contact_email',
			'id'		=> 'contact_email',
			'class'		=> '',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> 'Enter your email address.',
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- message title
			'type'		=> 'text',
			'title'		=> 'Title: ',
			'name'		=> 'contact_title',
			'id'		=> 'contact_title',
			'class'		=> '',
			'size'		=> 40,
			'maxlength'	=> 60,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> 'Enter your message title.',
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- message contents
			'type'		=> 'textarea',
			'title'		=> 'Message:',
			'name'		=> 'contact_message',
			'id'		=> 'contact_message',
			'class'		=> '',
			'cols'		=> 44,
			'rows'		=> 6,
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- submit
			'type'		=> 'submit',
			'name'		=> 'submit_contact_form',
			'id'		=> 'submit_contact_form',
			'class'		=> 'submit',
			'src'		=> '',
			'alt'		=> '',
			'js'		=> '',
//			'disabled'	=> 'disabled',
			'follow'	=> '',
//			'onclick'	=> 'toggle_this_element(\'site_contact_form\',\'submit_contact_form\');',
			'value'		=> 'Send'
		),
		array( //- cancel
			'type'		=> 'submit',
			'name'		=> 'cancel',
			'id'		=> 'cancel',
			'class'		=> 'cancel',
			'src'		=> '',
			'alt'		=> '',
			'js'		=> '',
			'follow'	=> '',
			'value'		=> 'Cancel'
		),

		array( //- fieldset
			'type'		=> 'fieldset'
		),

	), //- END: settings

); //- END: $Form_Settings


?>