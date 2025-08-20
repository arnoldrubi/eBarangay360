<?php

function validateRequiredFields(array $requiredFields, array $source = null): array {
  $source = $source ?? $_POST;
  $errors = [];

  foreach ($requiredFields as $field) {
    if (!isset($source[$field]) || trim($source[$field]) === '') {
      $errors[] = $field;
    }
  }

  return $errors;
}