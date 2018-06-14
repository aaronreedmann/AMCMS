<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: FormGen Index
//- Description: This file demonstrates the use of FormGen
//- Author: Aaron Mann
//- Updated: 14 June 2018

//- - - - - - - - - - - - - - - - - - - - - - - -
//- include the prepend file
include 'prep.php';

//- - - - - - - - - - - - - - - - - - - - - - - -
//- send some basic headers
echo '<!DOCTYPE html>'.$n; //- HTML 5
echo '<html lang="en">'.$n;
echo '<!-- HTML 5 Page by Aaron Mann -->'.$n;
echo '<head>'.$n;
echo '<title>AMCMS - FormGen Demo</title>'.$n;
echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">'.$n;
echo '<meta name="author" content="Aaron Mann, http://www.aaronreedmann.com">'.$n;
echo '<meta name="description" content="AMCMS - Form Generator Demonstration">'.$n;
echo '<link rel="shortcut icon" type="image/ico" href="../../../../site/image/icon2.ico">'.$n;

//- include module header only if css is not excluded
if( isset($_GET['form']) and $_GET['form'] !== 'demo-css' ) 
	include 'head.php';


//- - - - - - - - - - - - - - - - - - - - - - - -
//- begin body
echo '</head>'.$n;
echo '<body>'.$n;

//- only show ads when not on development environment
if( strtolower($_SERVER["SERVER_NAME"]) !== 'localhost' ) {
	//- google analytics - stats tracking
	include_once("../../../../site/script/analyticstracking.php");
}

//- navigation panel
echo '<br><div class="nav_panel"><a href="../../../../index.php">Home</a> - <a href="../../../index.php">AMCMS</a> - <a href="../../index.php">Modules</a> - <a href="../index.php">FormGen</a> - <a href="'.htmlentities($_SERVER['REQUEST_URI']).'">Demonstration</a></div>';
echo '<br><div class="nav_panel"><a href="index.php?form=demo">Demo</a> - <a href="index.php?form=contact">Contact</a> - <a href="index.php?form=login">Login</a> - <a href="index.php?form=demo-css">Demo-CSS</a></div>';

//- page contents
echo '<div id="page_content_wrapper">'.$n;
echo '<section><h2 style="color:#000;">AMCMS - FormGen - Demonstration</h2>'.$n;

//- include module
include 'formgen.php';
include 'fetch.php';
include 'files.php';

//- load form settings based on selection in Q string
if( isset($_GET['form']) and strlen($_GET['form'])>1 ) {
	if($_GET['form']=='demo') include 'form_demo.php';
	if($_GET['form']=='demo-css') include 'form_demo.php';
	if($_GET['form']=='login') include 'form_login.php';
	if($_GET['form']=='contact') include 'form_contact.php';
} else {
	include 'form_demo.php';
} //- END: if isset GET[form]

//- - - - - - - - - - - - - - - - - - - - - - - -
//- check to see that we're allowed to handle forms and that there is POST data to collect
//- instantiate a new form handler object will collect all POST data to a CLEAN array automatically
//- this will also check for (and handle) file uploads since those are typically part of a form process
if( isset($_POST) and count($_POST) > 0 and isset($Form_Settings) ) {
	$Fetch_Data = new Fetch_Data($Form_Settings,$_POST);

// 	$Temp['i_acct_name'] = $Fetch_Data->Clean['i_acct_name']; 
}

//- - - - - - - - - - - - - - - - - - - - - - - -
//- instantiate form
$Do_Form = new Form($Form_Settings);

//- render form to the screen
echo $Do_Form -> output();

//- some whitespace at the end
echo '<br><br>';

//- end of the wrappers
echo '<div class="section_bottom"></div></section>'.$n;
echo '</div> <!-- END: page_content_wrapper -->'.$n;

if( isset($_POST) and count($_POST) > 0 and isset($Fetch_Data) ) {
	print_r($Fetch_Data);
}

//- - - - - - - - - - - - - - - - - - - - - - - -
//- end of the page
echo '</body>'.$n;
echo '</html>'.$n;

?>
