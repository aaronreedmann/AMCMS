<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: Form Demo
//- Description: demo form includes at least one of each element and uses all common attributes at least once
//- Author: Aaron Mann
//- Updated: 14 June 2018

/* Notes: 
this array is declared with defaults in base/set/vars.php
this file is designed to intentionally overwrite those
*/


//- define form settings
$Form_Settings = array(

	//- form attributes
	'set'		=> array(
		'id'		=> 'form1_id',
		'name'		=> 'form1_name',
		'class'		=> 'form1_class',
		'action'	=> '',
		'method'	=> 'post',
		'enctype'	=> 'application/x-www-form-urlencoded' //- multipart/form-data is only needed for file uploads, otherwise use: application/x-www-form-urlencoded or nothing
	), //- END: form settings

	//- form options - * to be used only if formgen becomes handler also *
	'option'	=> array( //- use zero for false, any other integer for true
		'reload'	=> 1, //- will reload the form to the screen with values filled after post processing
		'onsubmit'	=> '', //- same as onsubmit="javascript" in the <form> tag
		'auto'		=> 1, //- tells the form generator if this is an auto form or not. auto forms are automatically loaded into pages.
		'redirect'	=> '', //- will redirect to the specified location after submission
		'send_email'=> 0, //- will send the form data to email
		'mail_to'	=> array(''), //- who to send the data to - expects array but please set no more than 10 addresses (the mail function is slow)
		'mail_from'	=> 'no-reply@nowhere.xom', //- email address to set as reply-to
		'mail_name'	=> 'System Emailer', //- shown as sender name in the letter
		'mail_subj'	=> 'Site Email', //- subject / title of letter
		'mail_body'	=> 'This email was automatically sent by the site system.', //- email contents
		'save_file'	=> 0, //- will save the data to a file on the server
		'file_path'	=> '', //- set location to save file to
		'file_name'	=> '', //- set file name
		'file_ext'	=> '', //- set file extension
		'file_over'	=> 0, //- overwrite if exists?
		'file_inc'	=> 0, //- include file after saving? (only works for .php extension)
		'stop_xss'	=> true, //- setting true will strip all scripting from data to prevent cross-site scripting attacks
		'use_recaptcha'	=> false, //- use recaptcha plugin?
	), //- END: form options

	//- form elements
	'element'	=> array( 0 => 

		array( //- fieldset
			'type'		=> 'fieldset',
			'title'		=> 'Non-input Elements'
		),
/*
		array( //- code
			'type'		=> 'code',
			'code'		=> '<table><tr><td>'
		),
*/
		array( //- textarea
			'type'		=> 'textarea',
			'title'		=> 'Text Area 1 ',
			'name'		=> 'textarea1',
			'id'		=> 'textarea1',
			'class'		=> 'txtarea',
			'cols'		=> 40,
			'rows'		=> 4,
			'follow'	=> 'some text follows the element',
			'value'		=> '[contents]'
		),
		array( //- select
			'type'		=> 'select',
			'title'		=> 'Select Menu 1 ',
			'name'		=> 'select1',
			'id'		=> 'select1',
			'class'		=> 'sel',
			'size'		=> 1,
			'follow'	=> '',
			'value'		=> '',
			'options'	=> array(
				'OptionA'	=> 'A',
				'OptionB'	=> 'B',
				'OptionC'	=> 'C'
			) //- END: options
		),
		array( //- select (NOTE: multiple selections do not work, only the last selection will be applied)
			'type'		=> 'select',
			'title'		=> 'Select Menu 2 ',
			'name'		=> 'select2',
			'id'		=> 'select2',
			'class'		=> 'sel',
			'multi'		=> 'multiple',
			'size'		=> 3,
			'value'		=> '',
			'follow'	=> '',
			'options'	=> array(
				'OptionA'	=> 'A',
				'OptionB'	=> 'B',
				'OptionC'	=> 'C'
			) //- END: options
		),
/*
		array( //- code
			'type'		=> 'code',
			'code'		=> '</td></tr></table>'
		),
*/
		array( //- fieldset
			'type'		=> 'fieldset',
			'title'		=> '' //- leave title blank to close field
		),
		array( //- fieldset
			'type'		=> 'fieldset',
			'title'		=> 'Input Elements (except buttons)'
		),
		array( //- text
			'type'		=> 'text',
			'title'		=> 'Text 1 ',
			'name'		=> 'text1',
			'id'		=> 'text1',
			'class'		=> 'text1',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> '',
			'readonly'	=> 'readonly',
			'placehold'	=> '',
			'follow'	=> '',
			'value'		=> 'i am read only. you can not edit me.'
		),

		array( //- text
			'type'		=> 'text',
			'title'		=> 'Text 2 ',
			'name'		=> 'text2',
			'id'		=> 'text2',
			'class'		=> 'text2',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> 'required',
			'readonly'	=> '',
			'placehold'	=> 'Text 1',
			'follow'	=> '',
			'value'		=> ''
		),

		array( //- password
			'type'		=> 'password',
			'title'		=> 'Password ',
			'name'		=> 'password',
			'id'		=> 'password',
			'class'		=> 'password',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> 'Password',
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- hidden
			'type'		=> 'hidden',
			'name'		=> 'hidden',
			'id'		=> 'hidden',
			'value'		=> '123'
		),
		array( //- file
			'type'		=> 'file',
			'title'		=> 'File1 ',
			'name'		=> 'file1',
			'id'		=> 'file1',
			'class'		=> 'file',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> '',
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- file
			'type'		=> 'file',
			'title'		=> 'File2 ',
			'name'		=> 'file2',
			'id'		=> 'file2',
			'class'		=> 'file',
			'size'		=> 40,
			'maxlength'	=> 250,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> '',
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- checkbox
			'type'		=> 'checkbox',
			'title'		=> 'Checkbox ',
			'name'		=> 'checkbox1',
			'id'		=> 'checkbox1',
			'class'		=> 'checkbox',
			'size'		=> 0,
			'maxlength'	=> 0,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> '',
			'follow'	=> '',
			'append'	=> '',
			'checked'	=> '',
			'value'		=> ''
		),
		array( //- checkbox
			'type'		=> 'checkbox',
			'title'		=> 'Checkbox ',
			'name'		=> 'checkbox2',
			'id'		=> 'checkbox2',
			'class'		=> 'checkbox',
			'size'		=> 0,
			'maxlength'	=> 0,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> '',
			'follow'	=> '',
			'append'	=> '',
			'checked'	=> 'checked',
			'value'		=> ''
		),
		array( //- radio
			'type'		=> 'radio',
			'title'		=> 'Radio 1 ',
			'name'		=> 'radio',
			'id'		=> 'radio',
			'class'		=> 'radio',
			'size'		=> 0,
			'maxlength'	=> 0,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> '',
			'follow'	=> '',
			'group'		=> true,
			'value'		=> '1'
		),
		array( //- radio
			'type'		=> 'radio',
			'title'		=> 'Radio 2 ',
			'name'		=> 'radio',
			'id'		=> 'radio',
			'class'		=> 'radio',
			'size'		=> 0,
			'maxlength'	=> 0,
			'required'	=> '',
			'readonly'	=> '',
			'placehold'	=> '',
			'follow'	=> '',
			'value'		=> '2'
		),

		array( //- fieldset
			'type'		=> 'fieldset',
			'title'		=> '' //- leave title blank to close field
		),
		array( //- fieldset
			'type'		=> 'fieldset',
			'title'		=> 'Input Elements (buttons)'
		),
		array( //- submit
			'type'		=> 'submit',
			'name'		=> 'submit',
			'id'		=> 'submit',
			'class'		=> 'submit',
			'src'		=> '',
			'alt'		=> '',
			'js'		=> '',
			'follow'	=> '',
			'value'		=> 'Submit'
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
		array( //- reset
			'type'		=> 'reset',
			'name'		=> 'reset',
			'id'		=> 'reset',
			'class'		=> 'reset',
			'src'		=> '',
			'alt'		=> '',
			'js'		=> '',
			'follow'	=> '',
			'value'		=> 'Reset'
		),
		array( //- button
			'type'		=> 'button',
			'name'		=> 'button',
			'id'		=> 'button',
			'class'		=> 'button',
			'src'		=> '',
			'alt'		=> '',
			'js'		=> '',
			'follow'	=> '',
			'value'		=> 'Button'
		),
		array( //- image
			'type'		=> 'image',
			'name'		=> 'image',
			'id'		=> 'image',
			'class'		=> 'image',
			'src'		=> 'button1.png',
			'alt'		=> 'Image',
			'js'		=> '',
			'follow'	=> '',
			'value'		=> ''
		),
		array( //- fieldset
			'type'		=> 'fieldset'
		),

	), //- END: settings

); //- END: $Form_Settings

?>