<?php
/**
 * AtLeastValidator
 *
 * @author   Vladimir Galajda <galajda@gmail.com>
 **/
class AtLeastValidator extends CValidator
{

    /** @var string */
    protected $defaultMessage = 'Fill at least one of the following attributes: {attributes}';

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
            $message = empty($this->message) ? Yii::t('application', $this->defaultMessage) : $this->message;
            $this->addError($sender, null,  $message, array('{attributes}' => implode(', ', $this->attributes)));
        }
    }
}