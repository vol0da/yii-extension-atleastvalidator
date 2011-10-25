<?php

class AtLeastValidator extends CValidator
{
    /**
     * @param CModel $object
     * @param array $attributes
     */
    public function validate(CModel $object, $attributes)
    {
        parent::validate($object, $attributes);
        $object->onAfterValidate = array($this, 'afterValidate');
    }

    /**
     * @param CModel $object
     * @param string $attribute
     */
    public function validateAttribute($object, $attribute)
    {

    }

    public function afterValidate($event)
    {
        /** @var $sender CModel */
        $sender = $event->sender;

        $valid = false;

        foreach($this->attributes as $attribute) {
            if (!$sender->hasErrors($attribute)) {
                $valid = true;
                break;
            }
        }

        if ($valid) {
            foreach($this->attributes as $attribute) {
                $sender->clearErrors($attribute);
            }
        }
        else {
            $sender->addError(null, 'Fill at least one of the following attributes: ' . implode(', ', $this->attributes));
        }
    }
}