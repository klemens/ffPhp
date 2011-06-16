ffPhp
=====
_Forms for PHP_

Overview
--------

With ffPhp you can create xHtml forms in php very easy.

Supported controls are:

 * Fieldset
 * Singleline input
 * Multiline input (textarea)
 * Hidden field
 * Checkbox group
 * Radiobutton group
 * Grouped and ungrouped lists

Example
-------

A simple login form.

``` php
<?php

$form = new ffPhp;
$name = $form->Add(new ffInput('Username'));
$password = $form->Add(new ffInput('Password'));

$name->required = true;
$password->required = true;
$password->password = true;

if($form->IsSent()) {
    if($form->IsComplete()) {
        if(LOGIN_WRONG_NAME == login($name->GetValue(), $password->GetValue())) {
            //display custom error messages to the user
            $name->error = 'This username does not exist.';
        } else {
            //redirect to backend or the like
        }
    } else {
        //sets the name the user already entered as the default value
        //and marks the missing fields in red
        $form->ApplySent();
    }
}

$form->Show();

?>
```

Look into `demo.php` for the following more complete example:
![Screenshot of ffPhp](https://raw.github.com/klemens/ffPhp/master/demo.png)

Licence
-------

ffPhp is licenced under the MIT Licence (see LICENCE file). If you
choose to fork or use parts of this project, it would be nice if you use a
open source licence.