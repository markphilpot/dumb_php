<?php
$require_login = false;
require_once 'include/set_env.php';
require_once 'include/form_validator.php';

require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/ArraySmarty.php";
/*
 * Setup Ajax
 */
//require_once ("include/xajax.inc.php");
//$xajax = new xajax("freshmen_form.php");
//function processFreshmenForm($form)
//{
//   $objResponse = new xajaxResponse();
   
$form =& new HTML_QuickForm('freshmen_form', 
                            'POST',
                            'freshmen_form.php');
                            
$form_data = $form->getSubmitValues();

$response = '&nbsp;';

if(isset($form_data['Submit']))
{   
   global $db;
 	
   /*
   $validator = new form_validator($form);
   $validator->add_rule('name', 'Please fill in your name', 'required');
   $validator->add_rule('email', 'Please enter a valid email address', 'email');
   //$validator->add_rule('zip', 'Please enter a valid ZIP code', 'regex', 'numeric');
   
   $results = $validator->get_results();
   
   if(sizeof($results) != 0)
   {
      foreach($results as $m)
      {
         $content .= $m . '<br />';
      }
      //$objResponse->addAssign("form_result","innerHTML", $content);
      //return $objResponse;
   }*/
   
   $result = $db->getAll('insert into dumb_freshmen_form values (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)', array($form_data['name'],
                                                                                       $form_data['address'],
                                                                                       $form_data['city'],
                                                                                       $form_data['state'],
                                                                                       $form_data['zip'],
                                                                                       $form_data['email'],
                                                                                       $form_data['phone'],
                                                                                       $form_data['instrument'],
                                                                                       $form_data['highschool'],
                                                                                       $form_data['director'],
                                                                                       $form_data['graduation'],
                                                                                       $form_data['major'],
                                                                                       $form_data['size'],
                                                                                       $form_data['dukeID'],
                                                                                       $form_data['questions'],
                                                                                       date('Y-m-d')));
   
   if(PEAR::isError($result))
   {
      error_log($result->getMessage(), 1, $EMAIL);
      $response = "Error submitting form.  The Admin has been notified.";
   }
   
   $res = $db->getAll( "SELECT LAST_INSERT_ID()" );

   list($temp, $last) = each($res); // one row
   $entry_id = $last['LAST_INSERT_ID()'];
   
   // Save uploaded image if applicable
   $image_included = false;
   $size = 300; // the thumbnail height

   $filedir = 'uploaded/'; // the directory for the original image
   $thumbdir = 'uploaded/'; // the directory for the thumbnail image
   $prefix = 'small_'; // the prefix to be added to the original name
   
   $thumbnail = '';

   $maxfile = '2000000';
   $mode = '0666';
     
   $userfile_name = $_FILES['image']['name'];
   $userfile_tmp = $_FILES['image']['tmp_name'];
   $userfile_size = $_FILES['image']['size'];
   $userfile_type = $_FILES['image']['type'];
     
   if (isset($_FILES['image']['name']) &&
       $userfile_size > 0)
   {
      $image_included = true;

       $prod_img = $filedir.$entry_id."_".$userfile_name;

       $prod_img_thumb = $thumbdir.$prefix.$entry_id."_".$userfile_name;
       move_uploaded_file($userfile_tmp, $prod_img);
       chmod ($prod_img, octdec($mode));
        
       $sizes = getimagesize($prod_img);

       $aspect_ratio = $sizes[1]/$sizes[0];

       if ($sizes[1] <= $size)
       {
           $new_width = $sizes[0];
           $new_height = $sizes[1];
       }
       else
       {
           $new_height = $size;
           $new_width = abs($new_height/$aspect_ratio);
       }

       $destimg=ImageCreateTrueColor($new_width,$new_height) or die('Problem In Creating image');
       $srcimg=ImageCreateFromJPEG($prod_img) or die('Problem In opening Source Image');
       ImageCopyResized($destimg,$srcimg,0,0,0,0,$new_width,$new_height,ImageSX($srcimg),ImageSY($srcimg)) or die('Problem In resizing');
       ImageJPEG($destimg,$prod_img_thumb,90) or die('Problem In saving');
       imagedestroy($destimg);
       
       $thumbnail = $prod_img_thumb;
   } 
   
   $result = $db->getAll('insert into dumb_recruitment values (?, ?, NULL, ?, ?)', array($entry_id,
                                                                                       ($image_included) ? $thumbnail : "",
                                                                                       "",
                                                                                       "",));
   
   if(PEAR::isError($result))
   {
      error_log($result->getMessage(), 1, $EMAIL);
      $response = "Error submitting form.  The Admin has been notified.";
   }
   
   $response .= "Submitted Successfully!  Thank you. ";
   
   //$objResponse->addAssign("form_result","innerHTML", $content);
   
   //return $objResponse;
}
//$xajax->registerFunction("processFreshmenForm");
//$xajax->processRequests();
//$t->assign('xajax_javascript',$xajax->getJavascript('include/'));
$t->assign('title', 'Duke University Marching & Pep Band');
$t->assign('include_file', 'content/freshmen_form.tpl');
$t->assign('loc', 'current');
$t->assign('sidebar', 'sidebar/information.tpl');
$t->assign('breadcrumb', "<a href='index.php'>Home</a> > <a href='freshmen.php'>Admitted Freshmen</a> > Information Request");

$t->assign('response', $response);
/*
 * Setup Form
 */

/*
$form =& new HTML_QuickForm('freshmen_form', 
                            'POST',
                            '', '',
                            array('id' => 'freshmen_form',
                                  'onsubmit' => 'xajax_processFreshmenForm(xajax.getFormValues(\'freshmen_form\')); return false;' ) );
*/

$form->addElement('text', 'name', 'Name', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('text', 'address', 'Address', array('size' => 40, 'maxlength' =>40, 'style' => 'margin-bottom: 5px;' ));
$form->addElement('text', 'city', 'City', array('size' => 20, 'maxlength' =>30 ));
$form->addElement('text', 'state', 'State', array('size' => 2, 'maxlength' =>2 ));
$form->addElement('text', 'zip', 'ZIP', array('size' => 5, 'maxlength' =>5 ));
$form->addElement('text', 'email', 'Email', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('text', 'phone', 'Phone', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('text', 'instrument', 'Instrument', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('text', 'highschool', 'High School', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('text', 'director', 'Band Director', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('text', 'graduation', 'Duke Graduation Year', array('size' => 4, 'maxlength' =>4 ));
$form->addElement('text', 'major', 'Intended Major', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('select', 'size', 'Shirt Size', array('S'=>'S', 'M'=>'M', 'L'=>'L', 'XL'=>'XL', 'XXL'=>'XXL'));
$form->addElement('text', 'dukeID', 'Duke ID', array('size' => 20, 'maxlength' => 20 ));
$form->addElement('textarea', 'questions', 'Questions');
$form->addElement('file', 'image', 'Send us your photo');
$form->addElement('reset', 'Clear', 'Clear');
$form->addElement('submit', 'Submit', 'Submit');
$renderer =& new HTML_QuickForm_Renderer_ArraySmarty($t);
// build the HTML for the form
$form->accept($renderer);
// assign array with form data
$t->assign('FormData', $renderer->toArray());
$t->display('main.tpl');
?>
