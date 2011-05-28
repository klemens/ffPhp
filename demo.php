<?php

require_once 'lib/ffPhp/ffPhp.php';

$form = new ffPhp;

$form->Add(new ffInput('name', 'Name'));

if($form->IsSent()) {
    echo '<a href="'.$form->action.'">Zurück</a>';
    echo '<pre>';
    var_dump($form->req);
    echo '</pre>';
} else {
    $form->Show();
}
