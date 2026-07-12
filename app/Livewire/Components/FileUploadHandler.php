<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class FileUploadHandler extends Component
{
    use WithFileUploads;

    public $files = [];
    public $maxFiles = 5;
    public $maxFileSize = 10240; // 10MB in KB
    public $allowedTypes = ['pdf', 'docx', 'pptx', 'jpg', 'jpeg', 'png'];
    
    protected $rules = [
        'files.*' => 'required|file|max:10240|mimes:pdf,docx,pptx,jpg,jpeg,png'
    ];

    public function updatedFiles()
    {
        $this->validate();
        
        $uploadedData = [];
        foreach ($this->files as $file) {
            // Dapatkan metadata SEBELUM file dipindahkan/disimpan
            $originalName = $file->getClientOriginalName();
            $size = $file->getSize();
            $mime = $file->getMimeType();

            $path = $file->store('temp', 'local');
            $fullPath = storage_path('app/private/' . $path);
            
            // Detect page count
            $pageCount = app(\App\Services\DocumentPageService::class)->getPageCount($fullPath, $mime);
            
            $uploadedData[] = [
                'path' => $path,
                'original_name' => $originalName,
                'size' => $size,
                'mime' => $mime,
                'page_count' => $pageCount,
            ];
        }

        $this->dispatch('files-uploaded', files: $uploadedData);
    }

    public function removeFile($index)
    {
        array_splice($this->files, $index, 1);
        $this->dispatch('file-removed', index: $index);
    }

    public function render()
    {
        return view('livewire.components.file-upload-handler');
    }
}
