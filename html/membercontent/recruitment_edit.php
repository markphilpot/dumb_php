<?php

require_once "include/dumb_util.php";
require_once "include/html_util.php";
require_once "HTML/QuickForm.php";
require_once "HTML/QuickForm/Renderer/QuickHtml.php";


$form =& new HTML_QuickForm('freshmen_form', 
                            'POST',
                            'members.php?loc=recruitment_edit&entry_id='.$_REQUEST['entry_id']);
                            
$form_data = $form->getSubmitValues();

$response = '&nbsp;';

if(isset($form_data['Submit']))
{  global $db;
   
   $result = $db->getAll("UPDATE dumb_freshmen_form SET ".
	   "name=?,".
	   "address=?,".
	   "city=?,".
	   "state=?,".
	   "zip=?,".
	   "email=?,".
	   "phone=?,".
	   "instrument=?,".
	   "highschool=?,".
	   "director=?,".
	   "graduation=?,".
	   "major=?,".
	   "size=?,".
           "hsexp=?,".
	   "questions=? ".
	   "WHERE id=?", array($form_data['name'],
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
                                                                                       $form_data['hsexp'],
                                                                                       $form_data['questions'],
									 	       $_REQUEST['entry_id']));
   
   if(PEAR::isError($result))
   {
      error_log($result->getMessage(), 1, $EMAIL);
      $response = "Error submitting form.  The Admin has been notified.";
   }



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

       $prod_img = $filedir.$_REQUEST['entry_id']."_".$userfile_name;

       $prod_img_thumb = $thumbdir.$prefix.$_REQUEST['entry_id']."_".$userfile_name;
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
    
   	$db->getAll('update dumb_recruitment set picture_file=? WHERE id=?', 
		   array($thumbnail, $_REQUEST['entry_id']));
   
 	  if(PEAR::isError($result))
	   {
	      error_log($result->getMessage(), 1, $EMAIL);
	      $response = "Error submitting form.  The Admin has been notified.";
	   }
   }
}

/*
 * Setup members only page
 */

?>
<div id="options">
<h3 class="center">&nbsp;</h3>
<div class="border">
<h4 class="center">Recruit Functions</h4>
</div>
<h5 class="center"><a href="members.php?loc=recruitment">Back</a></h5>
</div> <!-- end options -->

<div id="main_app">
<h3 class="center">Edit Recruit Information</h3>
<?php  //display (editable) information


/*
 * Setup Form
 */

if(!isset($_REQUEST['entry_id']))
{
   die("invalid recruitment entry_id");
}

$entry_id = $_REQUEST['entry_id']; //THIS IS STILL A SECURITY PROBLEM!  Potential to execute foreign code in query.





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
$form->addElement('text', 'graduation', 'Graduation Year', array('size' => 4, 'maxlength' =>4 ));
$form->addElement('text', 'major', 'Intended Major', array('size' => 40, 'maxlength' =>80 ));
$form->addElement('select', 'size', 'Shirt Size', array('S'=>'S', 'M'=>'M', 'L'=>'L', 'XL'=>'XL', 'XXL'=>'XXL'));
$form->addElement('textarea', 'hsexp', 'Highschool Experience');
$form->addElement('textarea', 'questions', 'Questions');
$form->addElement('file', 'image', 'Photo');
$form->addElement('submit', 'Submit', 'Submit');

/*
 * Set Default Values
 */

	global $db;
	$entries = $db->getAll("select * from dumb_freshmen_form where id = ?", array($entry_id));
	$entry = $entries[0];




$form_defaults = array( 'name' => $entry['name'],
	'address' => $entry['address'],
	'city' => $entry['city'],	
	'state' => $entry['state'],
	'zip' => $entry['zip'],
	'email' => $entry['email'],
	'phone' => $entry['phone'],
	'instrument' => $entry['instrument'],
	'highschool' => $entry['highschool'],
	'director' => $entry['director'],
	'graduation' => $entry['graduation'],
	'major' => $entry['major'],
	'size' => $entry['size'],
	'hsexp' => $entry['hsexp'],
	'questions' => $entry['questions']);

$form->setDefaults($form_defaults);

$r =& new HTML_QuickForm_Renderer_QuickHtml();
// build the HTML for the form
$form->accept($r);
// assign array with form data

$sups = $db->getAll("select * from dumb_recruitment where id = ?", array($entry_id));
	$sup = $sups[0];

$data = '<table width="50%" border="1" cellspacing="0" cellpadding="1">' .
		'<tr> <th class="table_header">Name</th> <td>'.$r->elementToHtml('name').'</td> </tr>' .
		'<tr> <th class="table_header">Address</th> <td>'.$r->elementToHtml('address').'</td> </tr>' .
		'<tr> <th class="table_header">City</th> <td>'.$r->elementToHtml('city').'</td> </tr>' .
		'<tr> <th class="table_header">State</th> <td>'.$r->elementToHtml('state').'</td> </tr>' .
		'<tr> <th class="table_header">Zip</th> <td>'.$r->elementToHtml('zip').'</td> </tr>' .
		'<tr> <th class="table_header">Email</th> <td>'.$r->elementToHtml('email').'</td> </tr>' .
		'<tr> <th class="table_header">Phone</th> <td>'.$r->elementToHtml('phone').'</td> </tr>' .
		'<tr> <th class="table_header">Instrument</th> <td>'.$r->elementToHtml('instrument').'</td> </tr>' .
		'<tr> <th class="table_header">High School</th> <td>'.$r->elementToHtml('highschool').'</td> </tr>' .
		'<tr> <th class="table_header">Director</th> <td>'.$r->elementToHtml('director').'</td> </tr>' .		
		'<tr> <th class="table_header">Graduation Year</th> <td>'.$r->elementToHtml('graduation').'</td> </tr>' .
		'<tr> <th class="table_header">Expected Major</th> <td>'.$r->elementToHtml('major').'</td> </tr>' .
		'<tr> <th class="table_header">Shirt Size</th> <td>'.$r->elementToHtml('size').'</td> </tr>' .		
                '<tr> <th class="table_header">Highschool Experience</th> <td>'.$r->elementToHtml('hsexp').'</td> </tr>' .
		'<tr> <th class="table_header">Questions</th> <td>'.$r->elementToHtml('questions').'</td> </tr>' .
		'<tr> <th class="table_header">Change Photo<br><img src="'.$sup['picture_file'].'" alt="(image)" /></th> <td>'.$r->elementToHtml('image').'</td> </tr>' .
		'</table>';

echo $r->toHtml($data);

?>
</div>  <!-- end main app -->

