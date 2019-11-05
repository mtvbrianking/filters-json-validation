<?php

require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json');

# https://jsonschema.net

use JsonSchema\Validator;
use JsonSchema\Constraints\Constraint;
 
// $sample = json_decode(file_get_contents('sample.json'));

// https://stackoverflow.com/q/4790453/2732184
$queryParams = json_decode(json_encode($_GET, JSON_NUMERIC_CHECK));

$validator = new Validator; 

# file:///C:/xampp/htdocs/filters-json-validation/schema.json

$schema = (object) [
    '$ref' => 'file:///' . realpath('schema.json')
];

$validator->validate(
    $queryParams,
    $schema,
    Constraint::CHECK_MODE_APPLY_DEFAULTS
);
 
if (!$validator->isValid()) {
    http_response_code(400);

    echo json_encode([
        'message' => 'The given data is invalid.',
        'errors' => $validator->getErrors()
    ]);
    
    return;
}

echo json_encode($queryParams);
