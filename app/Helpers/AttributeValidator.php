<?php

namespace App\Helpers;

use App\Exceptions\AttributeDoesNotExistException;
use App\Exceptions\ValidationRuleException;

class AttributeValidator
{
    private $errors = [];
    /**
     * Validate a model attribute.
     * 
     * @param array<string, mixed> $attributes
     * @param array<string, mixed> $rules
     * @return bool
     */
    public function validate(array $attributes, array $rules, string $context = 'validate'): bool
    {
        foreach ($rules as $attribute => $ruleParts) {
            // Make sure attribute exists
            if (!array_key_exists($attribute, $attributes)) {
                throw new AttributeDoesNotExistException($attribute);
            }

            // Before we validate any of these, we need to check for an on:{context} rule
            // This isn't the most efficient way to do this but in the interest of time...
            foreach ($ruleParts as $rule) {
                $r = explode(':', $rule);
                // If this is an "on" rule and the context doesn't match the current context, skip this rule
                if (count($r) > 1 && $r[0] === 'on' && $r[1] !== $context) {
                    continue 2;
                }
            }

            // Now validate each rule
            foreach ($ruleParts as $rule) {
                $r = explode(':', $rule);

                if (count($r) === 1) {
                    $r[1] = null;
                }

                $method = 'validate' . ucfirst($r[0]);

                if (!method_exists(self::class, $method)) {
                    // We'll throw this as a generic exception. This should stop execution.
                    throw new Exception("Validation method {$method} does not exist.");
                }

                $this::$method($attributes[$attribute], $r[1]);
            }
        }

        return true;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    private function addError(string $attribute, string $error)
    {
        if (!array_key_exists($attribute, $this->errors)) {
            $this->errors[$attribute] = [];
        }

        $this->errors[$attribute][] = $error;
    }

    protected function validateRequired($attribute, $options = null): bool
    {
        if (empty($attribute)) {
            $this->addError($attribute, "Value is required.");
        }

        return true;
    }

    protected function validateString($attribute, $options = null)
    {
        if (!is_string($attribute)) {
            $this->addError($attribute, "Value must be a string.");
        }
    }

    protected function validateInteger($attribute, $options = null)
    {
        if (!is_int($attribute)) {
            $this->addError($attribute, "Value must be an integer");
        }
    }

    protected function validateMax($attribute, int $max)
    {
        if (intval($attribute) > $max) {
            $this->addError($attribute, "Value must be less than or equal to {$max}");
        }
    }
}