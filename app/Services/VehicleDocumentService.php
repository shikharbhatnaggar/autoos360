<?php

namespace App\Services;

use App\Models\Vehicle;
use App\Models\VehicleDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VehicleDocumentService
{
    public function save(
        Vehicle $vehicle,
        string $section,
        string $documentType,
        string $documentName,
        ?string $documentNo,
        ?string $validTill,
        ?UploadedFile $file = null
    ): VehicleDocument {

        $document = $vehicle->documents()
            ->where('section', $section)
            ->where('document_type', $documentType)
            ->first();

        $data = [
            'section' => $section,
            'document_type' => $documentType,
            'document_name' => $documentName,
            'document_no' => $documentNo,
            'valid_till' => $validTill,
        ];

        if ($file) {

            if ($document?->file_path) {
                Storage::disk('public')->delete($document->file_path);
            }

            $path = $file->store(
                'vehicles/' . $vehicle->id . '/documents',
                'public'
            );

            $data['file_path'] = $path;
            $data['original_file_name'] = $file->getClientOriginalName();
            $data['mime_type'] = $file->getMimeType();
            $data['file_size'] = $file->getSize();
        }

        if ($document) {

            $document->update($data);

            return $document;
        }

        return $vehicle->documents()->create($data);
    }
}