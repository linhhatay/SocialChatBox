<?php

namespace App;

class Validator
{
    protected $data;
    protected $rules;
    protected $errors = [];

    public function __construct($data, $rules)
    {
        $this->data = $data;
        $this->rules = $rules;
    }

    public function validate()
    {
        foreach ($this->rules as $field => $rule) {
            $rules = explode('|', $rule);
            foreach ($rules as $singleRule) {
                $this->validateField($field, $singleRule);
            }
        }

        return empty($this->errors);
    }

    protected function validateField($field, $rule)
    {
        $params = [];
        if (strpos($rule, ':') !== false) {
            [$rule, $params] = explode(':', $rule, 2);
            $params = explode(',', $params);
        }

        $value = $this->data[$field] ?? null;

        switch ($rule) {
            case 'required':
                if (empty($value)) {
                    $this->addError($field, 'The ' . $field . ' field is required.');
                }
                break;

            case 'numeric':
                if (!is_numeric($value)) {
                    $this->addError($field, 'The ' . $field . ' field must be a number.');
                }
                break;

            case 'min':
                if (strlen($value) < $params[0]) {
                    $this->addError($field, 'The ' . $field . ' field must be at least ' . $params[0] . ' characters.');
                }
                break;

                // Add more validation rules as needed.

            default:
                // Custom or unsupported rules can be handled here.
                break;
        }
    }

    protected function addError($field, $message)
    {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = [];
        }
        $this->errors[$field][] = $message;
    }

    public function errors()
    {
        return $this->errors;
    }
}