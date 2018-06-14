<?php

//- - - - - - - - - - - - - - - - - - - - - - - -
//- Title: Form Generator
//- Description: This class creates a form object out of an array of settings passed in.
//- Author: Aaron Mann
//- Updated: 14 June 2018

//- USAGE:
/*
include 'formgen.php'; //- include the module
include 'forms/demo.php'; //- include form settings
$Do_Form = new Form($Form_Settings); //- instantiate form object
echo $Do_Form -> output(); //- render form to the screen
*/

//- - - - - - - - - - - - - - - - - - - - - - - -
//- page template class
class Form {

	//- variables
	public $iSet		= array(); //- your form settings, elements, attributes and values
	public $Output		= ''; //- generated HTML output string starts as blank by default
	public $Rendered	= ''; //- tracks which form was last rendered for re-render behavior

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- construct
	public function __construct( $inSet ) {

		//- declare globals
		global $n;
		global $t;
//		global $Page_Settings;

		//- include javascript attributes definitions
		include 'js_attribs.php';

		//- settings
		$tabidx=0;
		$star_css='color:#F00;font-size:20px;';

		//- import settings array (should be passed in)
		$this->iSet = $inSet; 

		//- check for auto option
		if( isset($this->iSet['option']['auto']) and $this->iSet['option']['auto'] != false ) {

			//- begin local output buffer with form tag
			$this->Output = $n.$t.$t.'<form';

			//- cycle form attributes
			foreach( $this->iSet['set'] as $k => $v ) if( strlen($v) >0 ) $this->Output .= ' '.$k.'="'.$v.'"';

			//- make sure action gets printed even if left blank to pass validation
			if( !isset($this->iSet['set']['action']) or strlen($this->iSet['set']['action']) < 1 ) 
				$this->Output .= ' action=""'; 

			//- make sure action gets printed even if left blank to pass validation
			if( !isset($this->iSet['set']['enctype']) or strlen($this->iSet['set']['enctype']) < 1 ) 
				$this->Output .= ' enctype="application/x-www-form-urlencoded"'; 

			//- check for javascript onsubmit()
			if( isset($this->iSet['option']['onsubmit']) and strlen($this->iSet['option']['onsubmit']) > 0 ) 
				$this->Output .= ' onsubmit="'.$this->iSet['option']['onsubmit'].'"'; 

			//- close form tag
			$this->Output .= '>'.$n; 

			//- record the name of the last form rendered
			$this->Rendered = $this->iSet['set']['name'];
			$_SESSION['last_form'] = $this->Rendered;

			//- collect elements and attributes
			foreach( $this->iSet['element'] as $ke => $attrib ) {

				//- ensure attribute 'type' is set
				if( isset($attrib['type']) ) {

					//- - - - - - - - - - - - - - - - - - - - - - - -
					//- check for fieldset element
					if( $attrib['type'] == 'fieldset' ) {
						if( isset($attrib['title']) and strlen($attrib['title']) >0 ) //- checks for open or close tag
							$this->Output .= $t.$t.'<fieldset><legend>'.$attrib['title'].'</legend>'.$n;
						else  //- close fieldset
							$this->Output .= $t.$t.'</fieldset>'.$n;
					} //- END: fieldset

					//- - - - - - - - - - - - - - - - - - - - - - - -
					//- check for code element
					if( $attrib['type'] == 'code' ) 
						$this->Output .= $t.$t.$attrib['code'].$n;

					//- - - - - - - - - - - - - - - - - - - - - - - -
					//- check for textarea element
					if( $attrib['type'] == 'textarea' ) {

						//- ensure name and title are set - append label
						if( isset($attrib['name']) and isset($attrib['title']) ) {
							if( isset($attrib['required']) and $attrib['required'] != '' ) 
								$attrib['title'].= '<span style="'.$star_css.'"> * </span>';
							$this->Output .= $t.$t.$t.'<div class="form_element"><div class="form_element_title">';
							$this->Output .= '<label for="'.$attrib['name'].'">'.$attrib['title'].'</label></div>'.$n;
						}

						//- begin textarea tag / append attributes and values
						$this->Output .= $t.$t.$t.'<span class="form_element_item"><textarea';
						if( isset($attrib['name']) ) $this->Output .= ' name="'.$attrib['name'].'"';
						if( isset($attrib['id']) ) $this->Output .= ' id="'.$attrib['id'].'"';
						if( isset($attrib['class']) ) $this->Output .= ' class="'.$attrib['class'].'"';
						if( isset($attrib['cols']) ) $this->Output .= ' cols="'.$attrib['cols'].'"';
						if( isset($attrib['rows']) ) $this->Output .= ' rows="'.$attrib['rows'].'"';

						$this->Output .= (isset($attrib['disabled']) and strlen($attrib['disabled']) >0 ) 
							? ' disabled="disabled"':null;

						//- check for javascript event attributes
						foreach($attrib as $kk => $vv) {
							if( in_array($kk, $js_DefAttributes) ) 
								$this->Output .= ' '.$kk.'="'.$vv.'"';
						}

						$this->Output .= ' tabindex="'.++$tabidx.'"';
						$this->Output .= '>';

//- * CURRENTLY WORKING HERE *

						//- check for post back
						if( isset($_POST) and count($_POST) > 0 ) {
							if( isset($attrib['name']) and isset($_POST[$attrib['name']]) 
//-							and $this->Rendered !== 'content_page_editor' 
							) 
								$this->Output .= stripslashes( $_POST[$attrib['name']] );
//-								$this->Output .= htmlspecialchars( stripslashes( $_POST[$attrib['name']] ) );

						} else { //- not posted back, fresh render off of previously saved settings

							//- check for content page settings
//							if( isset($Page_Settings['contents']) ) $this->Output .= $Page_Settings['contents'];

//-							elseif( isset($Form_Settings['set']['name']) 
//-							and $Form_Settings['set']['name'] == 'stylesheet_editor' ) 
//-								$this->Output .= $Form_Settings['element'][3]['value'];
//-								$this->Output .= file_get_contents($Site_Set['style_path'].'custom.css');

//- not a content page ..
							//- check for value set and use if found
							if( isset($attrib['value']) ) $this->Output .= $attrib['value'];

						} //- END: post check



						$this->Output .= '</textarea></span>'.$n;
						if( isset($attrib['follow']) and strlen($attrib['follow']) >0  ) {
							$this->Output .= $t.$t.$t.'<div class="form_element_follow">'.$attrib['follow'].'</div>';
							$this->Output .= '</div>'.$n;
						} else {
							$this->Output .= $t.$t.$t.'</div>'.$n;
						}
					} //- END: textarea

					//- - - - - - - - - - - - - - - - - - - - - - - -
					//- check for select element
					if( $attrib['type'] == 'select' ) {

						//- name & title
						if( isset($attrib['name']) and isset($attrib['title']) ) {
							if( isset($attrib['required']) and $attrib['required'] != '' ) 
								$attrib['title'].= '<span style="'.$star_css.'"> * </span>';
							$this->Output .= $t.$t.$t.'<div class="form_element"><div class="form_element_title">';
							$this->Output .= '<label for="'.$attrib['name'].'">'.$attrib['title'].'</label></div>'.$n;
						}

						//- opening tag and attributes
						$this->Output .= $t.$t.$t.'<span class="form_element_item"><select';
						if( isset($attrib['name']) ) $this->Output .= ' name="'.$attrib['name'].'"';
						if( isset($attrib['id']) ) $this->Output .= ' id="'.$attrib['id'].'"';
						if( isset($attrib['class']) ) $this->Output .= ' class="'.$attrib['class'].'"';
						if( isset($attrib['value']) ) $this->Output .= ' value="'.$attrib['value'].'"';
						if( isset($attrib['multi']) ) $this->Output .= ' multiple="multiple"';
						if( isset($attrib['size']) ) $this->Output .= ' size="'.$attrib['size'].'"';

						$this->Output .= (isset($attrib['disabled']) and strlen($attrib['disabled']) >0 ) 
							? ' disabled="disabled"':null;

						//- check for javascript event attributes
						foreach($attrib as $kk => $vv) {
							if( in_array($kk, $js_DefAttributes) ) 
								$this->Output .= ' '.$kk.'="'.$vv.'"';
						}

						$this->Output .= ' tabindex="'.++$tabidx.'"';
						$this->Output .= '>'.$n;

						//- loop through options
						if( isset($attrib['options']) ) {
							foreach( $attrib['options'] as $opt => $valu ) {
								$this->Output .= $t.$t.$t.$t.'<option value="'.$valu.'"';

								if( $valu == $attrib['value'] ) $this->Output .= ' selected="selected"';

								//- check for selected option on postback
								if( isset($_POST[$attrib['name']]) and $_POST[$attrib['name']] == $valu ) 
									$this->Output .= ' selected="selected"';

								$this->Output .= '>'.$opt.'</option>'.$n;
							} //- END: foreach
						} //- END: if options

						$this->Output .= $t.$t.$t.'</select></span>'.$n;
						if( isset($attrib['follow']) and strlen($attrib['follow']) >0  ) 
							$this->Output .= $t.$t.$t.'<span class="form_element_follow">'.$attrib['follow'].'</span>'.$n;
						$this->Output .= $t.$t.$t.'</div>'.$n;
					} //- END: select

					//- - - - - - - - - - - - - - - - - - - - - - - -
					//- check for input element [non-button]
					//- valid HTML5 <input> (attribute) type="values"; still unchecked:
					//- color, datetime, datetime-local, month, range, tel, week 

					if( $attrib['type'] == 'text' 
					or $attrib['type'] == 'password' 
					or $attrib['type'] == 'file' 
					or $attrib['type'] == 'email' 
					or $attrib['type'] == 'number' 
					or $attrib['type'] == 'search' 
					or $attrib['type'] == 'date' 
					or $attrib['type'] == 'time' 
					or $attrib['type'] == 'url' 
					or $attrib['type'] == 'hidden' 
					) {
						if($attrib['type'] != 'hidden' and isset($attrib['name']) and isset($attrib['title']) ) {
							if( isset($attrib['required']) and $attrib['required'] != '' ) 
								$attrib['title'].= '<span style="'.$star_css.'"> * </span>';
							$this->Output .= $t.$t.$t.'<div class="form_element"><div class="form_element_title">';
							$this->Output .= '<label for="'.$attrib['name'].'">'.$attrib['title'].'</label></div><span class="form_element_item">'.$n;
						}

						$this->Output .= $t.$t.$t.'<input type="'.$attrib['type'].'"';

						$this->Output .= (isset($attrib['id']) and strlen($attrib['id']) >0 ) 
							? ' id="'.$attrib['id'].'"':null;

						$this->Output .= (isset($attrib['name']) and strlen($attrib['name']) >0 ) 
							? ' name="'.$attrib['name'].'"':null;

						$this->Output .= (isset($attrib['class']) && strlen($attrib['class']) >0 ) 
							? ' class="'.$attrib['class'].'"':null;

						$this->Output .= (isset($attrib['disabled']) and strlen($attrib['disabled']) >0 ) 
							? ' disabled="disabled"':null;

						if($attrib['type'] != 'hidden') 
							$this->Output .= (isset($attrib['placehold']) and strlen($attrib['placehold']) >0 ) 
								? ' placeholder="'.$attrib['placehold'].'"':null;

						//- check for file
						if( $attrib['type'] != 'file' ) {

							$this->Output .= (isset($attrib['size']) && $attrib['size'] >0 ) 
								? ' size="'.$attrib['size'].'"':null;

							$this->Output .= (isset($attrib['maxlength']) && $attrib['maxlength'] >0 ) 
								? ' maxlength="'.$attrib['maxlength'].'"':null;

						} //- END: if file

						$this->Output .= (isset($attrib['required']) && strlen($attrib['required']) >0 ) 
							? ' required="'.$attrib['required'].'"':null;

						//- check for javascript event attributes
						foreach($attrib as $kk => $vv) {
							if( in_array($kk, $js_DefAttributes) ) 
								$this->Output .= ' '.$kk.'="'.$vv.'"';
						}

						if( $attrib['type'] != 'file' and $attrib['type'] != 'hidden' ) 
							$this->Output .= (isset($attrib['readonly']) and strlen($attrib['readonly']) >0 ) 
								? ' readonly="'.$attrib['readonly'].'"':null;

						$this->Output .= ($attrib['type'] !='hidden' ) ? ' tabindex="' . ++$tabidx .'"' :null;

						//- check for post back
						if( isset($_POST) and count($_POST) > 0 ) {

							if( isset($attrib['name']) and strlen($attrib['name']) > 0 and isset($_POST[$attrib['name']]) ) 
								$this->Output .= ' value="'.htmlspecialchars( stripslashes( $_POST[$attrib['name']] ) ).'"';

						} else {

							if( isset($attrib['value']) and strlen($attrib['value']) > 0 ) 
								$this->Output .= ' value="'.$attrib['value'].'"';

						} //- END: post check

						$this->Output .= '>';

						if($attrib['type'] != 'hidden') {

							$this->Output .= '</span>';

							if( isset($attrib['follow']) and strlen($attrib['follow']) >0  ) 
								$this->Output .= '<span class="form_element_follow">'.$attrib['follow'].'</span>';

							$this->Output .= '</div>'.$n;

						} else
							$this->Output .= $n;

					} //- END: input

					//- - - - - - - - - - - - - - - - - - - - - - - -
					//- check for input element [radio and checkbox]
					if($attrib['type'] == 'checkbox' 
					or $attrib['type'] == 'radio' 
					) {
//-						$this->Output .= $t.$t.$t.'<div class="form_element">';

						if( isset($attrib['name']) and isset($attrib['title']) ) {
							if( isset($attrib['required']) and $attrib['required'] != '' ) 
								$attrib['title'].= '<span style="'.$star_css.'"> * </span>';

							if( isset($attrib['group']) and $attrib['group'] == true ) 
								$this->Output .= $t.$t.$t.'<div class="form_element" style="">';

							$this->Output .= '<span class="form_element_title"><label for="'.$attrib['name'].'">'.$attrib['title'].'</label></span>'.$n;
						}

						$this->Output .= $t.$t.$t.'<span class="form_element_item"><input type="'.$attrib['type'].'"';

						$this->Output .= (isset($attrib['id']) and strlen($attrib['id']) >0 ) 
							? ' id="'.$attrib['id'].'"':null;

						$this->Output .= (isset($attrib['name']) and strlen($attrib['name']) >0 ) 
							? ' name="'.$attrib['name'].'"':null;

						$this->Output .= (isset($attrib['class']) and strlen($attrib['class']) >0 ) 
							? ' class="'.$attrib['class'].'"':null;

						$this->Output .= (isset($attrib['disabled']) and strlen($attrib['disabled']) >0 ) 
							? ' disabled="disabled"':null;

						$this->Output .= (isset($attrib['required']) and strlen($attrib['required']) >0 ) 
							? ' required="'.$attrib['required'].'"':null;

						//- check for javascript event attributes
						foreach($attrib as $kk => $vv) {
							if( in_array($kk, $js_DefAttributes) ) 
								$this->Output .= ' '.$kk.'="'.$vv.'"';
						}

						//- handling checked is a bit different between checkboxes and radios so let's toggle
						if( $attrib['type'] == 'checkbox' ) {

							$this->Output .= ( (isset($attrib['checked']) and strlen($attrib['checked']) >0) or ( isset($_POST[$attrib['name']]) ) ) 
								? ' checked="checked"':null;

						} else { //- radio:

							$this->Output .= ( (isset($attrib['checked']) and strlen($attrib['checked']) >0) 
								or ( isset($_POST[$attrib['name']]) and $_POST[$attrib['name']] == $attrib['value'] ) )
									? ' checked="checked"':null;

						} //- END: type toggle

						$this->Output .= ' tabindex="' . ++$tabidx .'"';

						if( isset($attrib['value']) and strlen($attrib['value']) > 0 ) 
							$this->Output .= ' value="'.$attrib['value'].'"';

						$this->Output .= '></span>';

						if( isset($attrib['follow']) and strlen($attrib['follow']) >0 ) 
							$this->Output .= '<span class="form_element_follow">'.$attrib['follow'].'</span>';

//						if( !isset($attrib['group']) ) 
//							$this->Output .= '</div>'.$n;

					} //- END: checkbox / radio

					//- - - - - - - - - - - - - - - - - - - - - - - -
					//- check for button element
					if($attrib['type'] == 'submit' 
					or $attrib['type'] == 'reset' 
					or $attrib['type'] == 'button' 
					or $attrib['type'] == 'image' 
					) {
						$this->Output .= $t.$t.$t.'<input type="'.$attrib['type'].'"';
						$this->Output .= (isset($attrib['id']) and strlen($attrib['id']) >0 ) 
							? ' id="'.$attrib['id'].'"':null;
						$this->Output .= (isset($attrib['name']) and strlen($attrib['name']) >0 ) 
							? ' name="'.$attrib['name'].'"':null;
						$this->Output .= (isset($attrib['class']) and strlen($attrib['class']) >0 ) 
							? ' class="'.$attrib['class'].'"':null;
						$this->Output .= (isset($attrib['src']) and strlen($attrib['src']) >0 ) 
							? ' src="'.$attrib['src'].'"':null;
						$this->Output .= (isset($attrib['alt']) and strlen($attrib['alt']) >0 ) 
							? ' alt="'.$attrib['alt'].'"':null;
						$this->Output .= (isset($attrib['style']) and strlen($attrib['style']) >0 ) 
							? ' style="'.$attrib['style'].'"':null;
						$this->Output .= (isset($attrib['disabled']) and strlen($attrib['disabled']) >0 ) 
							? ' disabled="disabled"':null;
						$this->Output .= (isset($attrib['js']) and strlen($attrib['js']) >0 ) 
							? $attrib['js'] :null;
						$this->Output .= ' tabindex="'.++$tabidx.'"';
						if( isset($attrib['value']) and strlen($attrib['value']) > 0 ) 
							$this->Output .= ' value="'.$attrib['value'].'"';

						//- check for javascript event attributes
						foreach($attrib as $kk => $vv) {
							if( in_array($kk, $js_DefAttributes) ) 
								$this->Output .= ' '.$kk.'="'.$vv.'"';
						}

						$this->Output .= '>';
						if( isset($attrib['follow']) and strlen($attrib['follow']) >0 ) 
							$this->Output .= $n.'<span class="form_element_follow">'.$attrib['follow'].'</span>';
						$this->Output .= $n;
					} //- END: buttons

				} //- END: check for 'type'
			} //- END: foreach loop

			//- close form
			$this->Output .= $t.$t.'</form>'.$n.$n;

		} //- END: check for auto option

	} //- END: function __construct

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- 
	public function _form_end() {

		return '</form>';

	} //- END: function

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- 
	public function fast_form_tag($name) {

		return '<form name="'.$name.'" method="post" enctype="application/x-www-form-urlencoded" action="">';

	} //- END: function

	//- - - - - - - - - - - - - - - - - - - - - - - -
	//- output the generated form
	public function output() {
		return $this->Output;
	} //- END: function

} //- END: class: Form


?>
