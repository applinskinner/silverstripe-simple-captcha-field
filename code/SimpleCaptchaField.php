<?php

/**
 * @author Itayi Patrick Chitovoro <patrick@chitosystems.com>
 * @copyright Copyright (c) 2015, Chito Systems (Pvt) Ltd
 * @package form
 * @subpackage validation
 */
class SimpleCaptchaField extends TextField
{
    protected $validateOnSubmit = false;

    /**
     * SimpleCaptchaField constructor.
     * @param string $name
     * @param null $title
     * @param string $formId
     */
    public function __construct($name, $title, $formId)
    {
        Requirements::css(SIMPLE_FORM_CAPTCHA_DIR . '/css/form.css');
        Requirements::javascript(SIMPLE_FORM_CAPTCHA_DIR . '/js/SimpleCaptchaField.js');

        parent::__construct($name, $title);

        Requirements::customScript(sprintf("var SIMPLECAPTCHAFORM = '%s'", $formId));
    }


    /**
     * @return string
     */
    public function getSkyImageLink()
    {
        $controller = SimpleCaptchaController::create();
        $controller->generateCaptchaID();
        return $controller->Link() . "image/?" . time();
    }

    protected $extraClasses = array('SimpleCaptchaField', 'form-control', 'field', 'text');

    /**
     * Validate this field
     *
     * @param Validator $validator
     * @return bool
     */
    public function validate($validator)
    {
        if (self::getValidateOnSubmit()) {
            return true;
        } else {
            if (strtoupper($this->value) === SimpleCaptchaController::getCaptchaID()) {
                return true;
            }
            $errormsg = sprintf("%s is wrong, Correct captcha is required", $this->value);
            $validator->validationError(
                $this->name, $errormsg,
                "validation"
            );

            Session::set("SimpleCaptchaError", $errormsg);
            return false;
        }
    }

    public function setValidateOnSubmit($bol = false)
    {
        $this->validateOnSubmit = $bol;

        return $this;
    }

    public function getValidateOnSubmit()
    {
        return $this->validateOnSubmit;
    }
}
