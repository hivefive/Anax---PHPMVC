<?php
session_name('cform_example');
session_start();


include('CForm.php');
// -----------------------------------------------------------------------
//
// Use the form and check its status.
//

$form = new CFormContact();
?>
<!doctype html>
<meta charset=utf8>
<title>CForm Example: Basic example on how to use CForm</title>
<?=$form->GetHTML()?>