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
<p><strong style="color:orange;">Please enter your submission!</strong></p>
<?php

function ShuffleText($text) {
    $parts = explode(' ', $text);
    foreach($parts AS &$part)
        if(strlen($part) > 3)
            $part = substr($part, 0, 1).str_shuffle(substr($part, 1, -1)).substr($part, -1);
    return implode(' ', $parts);
}

require_once 'lib/ffPhp/ffPhp.php';

$form = new ffPhp;

$form->Add(new ffFieldset('Personal data'));

$name = $form->Add(new ffInput('name', 'Name'));
$name->required = true;

$form->Add(new ffFieldset('Luggage'));

$utils = $form->Add(new ffCheckbox('utils', 'Utilities'));
$utils->AddChoices('Towel', 'Babelfish', 'H2G2');
$utils->DisableChoices('H2G2');
$utils->CheckChoices('Towel');
$poem = $form->Add(new ffInput('poem', 'Poem'));
$poem->lines = 5;
$poem->value = <<<POEM
O freddled gruntbuggly thy micturations are to me
As plured gabbleblochits on a lurgid bee.
Groop, I implore thee my foonting turlingdromes.
And hooptiously drangle me with crinkly bindlewurdles,
Or I will rend thee in the gobberwarts with my blurlecruncheon, see if I don't.
POEM;

$form->Add(new ffButton('Submit'));

if($form->IsSent()) {
    if($form->IsComplete()) {
        echo '<p>Dear ';
        if($utils->IsChecked('Towel'))
            echo 'hitchhiker ';
        echo $name->GetValue().', here is your poem:</p>';
        if($utils->IsChecked('Babelfish'))
            echo '<p>'.$poem->GetValue().'</p>';
        else
            echo '<p>'.ShuffleText($poem->GetValue()).'</p>';
        echo "\n<!--\n".print_r($form->req, 1)."\n-->\n";
    }
    
    $form->ApplySent();
}

$form->Show();

?>
</div>
</body>
</html>