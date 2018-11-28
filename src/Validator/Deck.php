<?php

namespace App\Validator;

use Symfony\Component\Translation\Translator;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Deck extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    public $message = 'validator.deck_code_not_valid';
}
