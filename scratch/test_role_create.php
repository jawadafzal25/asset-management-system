<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use App\Http\Controllers\RoleController;
use App\Http\Requests\Role\RoleCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Boot the application
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$app->boot();

// Create a mock request
$data = [
    'name' => 'As12hMnr ' . uniqid(),
    'description' => 'Manages all asset-related operations',
    'permission' => [1, 5, 28, 1]
];

$request = RoleCreateRequest::create('/api/role/create', 'POST', $data);
$request->setContainer($app);

// Use validation
$validator = \Illuminate\Support\Facades\Validator::make($data, (new RoleCreateRequest())->rules());
if ($validator->fails()) {
    echo "Validation failed: " . json_encode($validator->errors()) . "\n";
    exit;
}

$validated = $validator->validated();

// Manually call the controller method
$controller = $app->make(RoleController::class);

// Note: In a real request, $request would be injected by Laravel.
// Here we just pass our validated request.
$formRequest = RoleCreateRequest::createFrom($request);
$formRequest->setContainer($app);
$formRequest->setValidator($validator);

$response = $controller->create($formRequest);

echo "Response Status: " . $response->getStatusCode() . "\n";
echo "Response Body: \n" . json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT) . "\n";
