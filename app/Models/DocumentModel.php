<?php

namespace App\Models;

use CodeIgniter\Model;

class DocumentModel extends Model
{
    protected $table            = 'documents';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'description',
        'file_name',
        'file_original_name',
        'file_type',
        'file_size',
        'file_extension',
        'uploaded_by'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'title' => 'required|min_length[3]|max_length[255]',
        'file_name' => 'required|max_length[255]',
        'file_original_name' => 'required|max_length[255]',
        'file_type' => 'required|max_length[100]',
        'file_size' => 'required|integer',
        'file_extension' => 'required|max_length[10]'
    ];

    protected $validationMessages = [
        'title' => [
            'required' => 'Judul dokumen harus diisi',
            'min_length' => 'Judul minimal 3 karakter',
            'max_length' => 'Judul maksimal 255 karakter'
        ]
    ];

    protected $skipValidation = false;

    /**
     * Get formatted file size
     */
    public function getFormattedSize($size)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;

        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 2) . ' ' . $units[$i];
    }
}
