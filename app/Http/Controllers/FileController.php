<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use MongoDB\Client;
use MongoDB\GridFS\Bucket;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileController extends Controller
{
    /**
     * Stream a file from MongoDB GridFS.
     *
     * @param string $id The MongoDB ObjectId of the file
     */
    public function stream(string $id): StreamedResponse
    {
        $client = new Client(config('database.connections.mongodb.dsn') ?? 'mongodb://127.0.0.1:27017');
        $database = $client->selectDatabase(config('database.connections.mongodb.database'));
        $bucket = $database->selectGridFSBucket();

        try {
            $objectId = new \MongoDB\BSON\ObjectId($id);
            $stream = $bucket->openDownloadStream($objectId);
            $metadata = $bucket->getFileDocumentForStream($stream);
            
            $contentType = $metadata['contentType'] ?? 'application/octet-stream';
            $filename = $metadata['filename'] ?? 'file';

            return response()->stream(
                function () use ($stream) {
                    fpassthru($stream);
                },
                200,
                [
                    'Content-Type' => $contentType,
                    'Content-Disposition' => 'inline; filename="' . $filename . '"',
                ]
            );
        } catch (\Exception $e) {
            abort(404, 'File not found');
        }
    }
}
