Installation
-----------

Put the AtLeastValidator.php file under the extensions/ subdirectory of application base directory.

Usage
-----

At least one of attributes attribute1, attribute2, attribute3 must be filled by an user.

```php
<?php
public function rules()
{
    return array(
        array('attribute1, attribute2, attribute3', 'required'),
        array('attribute1, attribute2, attribute3', 'ext.atLeastValidator'),
    );
}

?>
```

An user must fill at least phone or valid email.
```php
<?php
public function rules()
{
    return array(
        array('phone', 'required'),
        array('email', 'email', 'allowEmpty' => false),
        array('email, phone', 'ext.atLeastValidator'),
    );
}

?>
```