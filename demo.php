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
        background-color: #FBFBEF;
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
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
    <script type="text/javascript" src="js/ffPhp.js"></script>
</head>
<body onload="ffPhp.Init();">
<div id="container">
<?php

function ShuffleText($text) {
    $lines = explode("\n", $text);
    foreach($lines AS &$line) {
        $parts = explode(' ', $line);
        foreach($parts AS &$part)
            if(strlen($part) > 3)
                $part = substr($part, 0, 1).str_shuffle(substr($part, 1, -1)).substr($part, -1);
        $line = implode(' ', $parts);
    }
    return implode("\n", $lines);
}

require_once 'lib/ffPhp/ffPhp.php';

$form = new ffPhp;

$form->Add(new ffFieldset('Identification'));

$name = $form->Add(new ffInput('Name'));
$name->required = true;

$planet = $form->Add(new ffList('Home planet'));
$planet->choices->Add('Betelgeuse Five', 'Earth', 'Vogsphere');
$planet->choices->Select('Earth');

$form->Add(new ffFieldset('Luggage'));

$utils = $form->Add(new ffCheckbox('Utilities'));
$utils->AddChoices('Towel', 'Babelfish', 'H2G2');
$utils->DisableChoices('H2G2');
$utils->CheckChoices('Babelfish');
$utils->description = 'What would be most useful for you?';

$form->Add(new ffFieldset('Poetry'))->collapsed = true;

$poem = $form->Add(new ffInput('Poem'));
$poem->lines = 5;
$poem->value = <<<POEM
O freddled gruntbuggly thy micturations are to me
As plured gabbleblochits on a lurgid bee.
Groop, I implore thee my foonting turlingdromes.
And hooptiously drangle me with crinkly bindlewurdles,
Or I will rend thee in the gobberwarts with my blurlecruncheon, see if I don't.
POEM;
$style = $form->Add(new ffRadio('Style'));
$style->AddChoices('Normal', 'Italic');
$style->CheckChoice('Italic');

$form->Add(new ffButton('Submit'));

if($form->IsSent()) {
    if($form->IsComplete()) {
        echo '<p>Hail ';
        
        if($utils->IsChecked('Towel'))
            echo 'hitchhiker ';
        
        echo $name->GetValue().' from '.$planet->GetSingleValue().
             ', let me give pleasure to you by declaiming this graceful poem:</p>';
        
        $text = str_replace("\r\n", "\n", $poem->GetValue());
        if($utils->IsChecked('Babelfish'))
            $poem_str = nl2br($text);
        else
            $poem_str = nl2br(ShuffleText($text));
        
        if($style->GetValue() == 'Italic')
            echo '<p><i>'.$poem_str.'</i></p>';
        else
            echo '<p>'.$poem_str.'</p>';
        
        echo "\n<!--\n".print_r($form->req, 1)."\n-->\n";
    }
    
    $form->ApplySent();
}

$form->Show();

?>
</div>
</body>
</html>