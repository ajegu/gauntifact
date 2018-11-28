<?php

namespace App\Validator;

use App\Utils\CArtifactDeckDecoder;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DeckValidator extends ConstraintValidator
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * DeckValidator constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($value === null) {
            return;
        }

        $params = explode('/', $value);
        $code = $params[count($params) - 1];
        $deck = CArtifactDeckDecoder::ParseDeck($code);

        if ($deck === false) {
            $this->context->buildViolation($this->translator->trans($constraint->message))
                ->addViolation();
        }
    }
}
