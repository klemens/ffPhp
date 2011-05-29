<?php header("Content-Type: text/html; charset=UTF-8");
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>ffphp example</title>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <style type="text/css">
    /* <![CDATA[ */
    body {
        font-family: Arial, Verdana, sans-serif;
        background-color: #EEF2EE;
    }
    #example_form_message {
        color: orange;
        font-weight: bold;
    }
    #container {
        max-width: 850px;
        margin: auto;
        padding: 10px;
        border: 1px solid #BBBBBB;
        box-shadow: 0 0 5px #888888;
        background-color: #FFFFFF;
    }
    /* ]]> */
    </style>
    <link rel="stylesheet" type="text/css" href="css/all.css" />
    <!--  //[if IE 8]>
    <link rel="stylesheet" type="text/css" href="css/ie8.css" />
    <![endif]-->
    <!--  //[if lte IE 7]>
    <link rel="stylesheet" type="text/css" href="css/ie7.css" />
    <![endif]-->
</head>
<body>
<div id="container">
<p><strong style="color:orange;">Please enter your data!</strong></p>
<?php

require_once 'lib/ffPhp/ffPhp.php';

$form = new ffPhp;

$form->Add(new ffFieldset('Personal data'));
$form->Add(new ffInput('lname', 'Last name'))->required = true;
$form->Add(new ffInput('fname', 'First name'));
$form->Add(new ffButton('Submit'));

if($form->IsSent()) {
    if($form->IsComplete()) {
        echo '<p>Submitted data:</p>';
        echo '<pre>';
        var_dump($form->req);
        echo '</pre>';
    }
    
    $form->ApplySent();
}

$form->Show();

?>
</div>
</body>
</html>