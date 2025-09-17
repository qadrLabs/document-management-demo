<?php

namespace App\Controllers;

use App\Models\DocumentModel;
use CodeIgniter\Files\File;

class DocumentController extends BaseController
{
    protected $documentModel;
    protected $helpers = ['form', 'url', 'filesystem'];

    public function __construct()
    {
        $this->documentModel = new DocumentModel();
    }

    /**
     * Display list of documents
     */
    public function index()
    {
        $data = [
            'title' => 'Daftar Dokumen',
            'documents' => $this->documentModel->orderBy('created_at', 'DESC')->paginate(10),
            'pager' => $this->documentModel->pager
        ];

        return view('documents/index', $data);
    }

    /**
     * Show upload form
     */
    public function upload()
    {
        $data = [
            'title' => 'Upload Dokumen Baru',
            'validation' => \Config\Services::validation()
        ];

        return view('documents/upload', $data);
    }

    /**
     * Handle file upload
     */
    public function store()
    {
        // Validation rules untuk upload
        $validationRules = [
            'title' => [
                'rules' => 'required|min_length[3]|max_length[255]',
                'errors' => [
                    'required' => 'Judul dokumen harus diisi',
                    'min_length' => 'Judul minimal 3 karakter',
                    'max_length' => 'Judul maksimal 255 karakter'
                ]
            ],
            'document_file' => [
                'rules' => 'uploaded[document_file]'
                    . '|max_size[document_file,10240]' // Max 10MB
                    . '|ext_in[document_file,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,rar,txt,jpg,jpeg,png,gif]',
                'errors' => [
                    'uploaded' => 'Pilih file yang akan diupload',
                    'max_size' => 'Ukuran file maksimal 10MB',
                    'ext_in' => 'Format file tidak didukung'
                ]
            ]
        ];

        if (!$this->validate($validationRules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('document_file');

        if ($file->isValid() && !$file->hasMoved()) {
            // Generate nama file unik
            $newName = $file->getRandomName();

            // Pindahkan file ke folder uploads
            $file->move(ROOTPATH . 'public/uploads', $newName);

            // Siapkan data untuk disimpan ke database
            $data = [
                'title' => $this->request->getPost('title'),
                'description' => $this->request->getPost('description'),
                'file_name' => $newName,
                'file_original_name' => $file->getClientName(),
                'file_type' => $file->getClientMimeType(),
                'file_size' => $file->getSize(),
                'file_extension' => $file->getClientExtension(),
                'uploaded_by' => $this->request->getPost('uploaded_by') ?? 'System'
            ];

            // Simpan ke database
            if ($this->documentModel->save($data)) {
                return redirect()->to('/documents')->with('success', 'Dokumen berhasil diupload!');
            } else {
                // Hapus file jika gagal simpan ke database
                unlink(ROOTPATH . 'public/uploads/' . $newName);
                return redirect()->back()->withInput()->with('error', 'Gagal menyimpan informasi dokumen');
            }
        }

        return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan saat upload file');
    }

    /**
     * Download document
     */
    public function download($id)
    {
        $document = $this->documentModel->find($id);

        if (!$document) {
            return redirect()->to('/documents')->with('error', 'Dokumen tidak ditemukan');
        }

        $filepath = ROOTPATH . 'public/uploads/' . $document['file_name'];

        if (!file_exists($filepath)) {
            return redirect()->to('/documents')->with('error', 'File tidak ditemukan di server');
        }

        return $this->response->download($filepath, null)->setFileName($document['file_original_name']);
    }

    /**
     * Delete document
     */
    public function delete($id)
    {
        $document = $this->documentModel->find($id);

        if (!$document) {
            return redirect()->to('/documents')->with('error', 'Dokumen tidak ditemukan');
        }

        $filepath = ROOTPATH . 'public/uploads/' . $document['file_name'];

        // Hapus dari database
        if ($this->documentModel->delete($id)) {
            // Hapus file dari server
            if (file_exists($filepath)) {
                unlink($filepath);
            }
            return redirect()->to('/documents')->with('success', 'Dokumen berhasil dihapus');
        }

        return redirect()->to('/documents')->with('error', 'Gagal menghapus dokumen');
    }

    /**
     * View document details
     */
    public function view($id)
    {
        $document = $this->documentModel->find($id);

        if (!$document) {
            return redirect()->to('/documents')->with('error', 'Dokumen tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Dokumen',
            'document' => $document,
            'formatted_size' => $this->documentModel->getFormattedSize($document['file_size'])
        ];

        return view('documents/view', $data);
    }
}
