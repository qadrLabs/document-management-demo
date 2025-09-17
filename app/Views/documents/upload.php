<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-cloud-upload"></i> Upload Dokumen Baru
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Error Messages -->
                    <?php if (session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">Terdapat kesalahan:</h6>
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Upload Form -->
                    <?= form_open_multipart('documents/store') ?>
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">
                                Judul Dokumen <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control <?= (session()->getFlashdata('errors.title')) ? 'is-invalid' : '' ?>" 
                                   id="title" 
                                   name="title" 
                                   value="<?= old('title') ?>" 
                                   placeholder="Masukkan judul dokumen"
                                   required>
                            <small class="text-muted">Judul yang deskriptif memudahkan pencarian dokumen</small>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Deskripsi</label>
                            <textarea class="form-control" 
                                      id="description" 
                                      name="description" 
                                      rows="3" 
                                      placeholder="Tambahkan deskripsi dokumen (opsional)"><?= old('description') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="document_file" class="form-label">
                                File Dokumen <span class="text-danger">*</span>
                            </label>
                            <div class="upload-zone" id="uploadZone">
                                <i class="bi bi-cloud-arrow-up" style="font-size: 3rem; color: #6c757d;"></i>
                                <p class="mt-2 mb-1">Drag & drop file di sini atau klik untuk memilih</p>
                                <input type="file" 
                                       class="form-control <?= (session()->getFlashdata('errors.document_file')) ? 'is-invalid' : '' ?>" 
                                       id="document_file" 
                                       name="document_file" 
                                       required
                                       accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.zip,.rar,.txt,.jpg,.jpeg,.png,.gif"
                                       style="display: none;">
                                <small class="text-muted">
                                    Format yang didukung: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, ZIP, RAR, TXT, JPG, PNG, GIF<br>
                                    Ukuran maksimal: 10MB
                                </small>
                                <div id="fileInfo" class="mt-3" style="display: none;">
                                    <div class="alert alert-info mb-0">
                                        <i class="bi bi-file-earmark"></i> 
                                        <span id="fileName"></span> 
                                        (<span id="fileSize"></span>)
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="uploaded_by" class="form-label">Diupload Oleh</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="uploaded_by" 
                                   name="uploaded_by" 
                                   value="<?= old('uploaded_by') ?>" 
                                   placeholder="Nama pengupload (opsional)">
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?= base_url('documents') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </a>
                            <button type="submit" class="btn btn-primary" id="uploadBtn">
                                <i class="bi bi-cloud-upload"></i> Upload Dokumen
                            </button>
                        </div>

                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Interactive upload zone
document.addEventListener('DOMContentLoaded', function() {
    const uploadZone = document.getElementById('uploadZone');
    const fileInput = document.getElementById('document_file');
    const fileInfo = document.getElementById('fileInfo');
    const fileName = document.getElementById('fileName');
    const fileSize = document.getElementById('fileSize');

    // Click to select file
    uploadZone.addEventListener('click', function() {
        fileInput.click();
    });

    // Drag and drop functionality
    uploadZone.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.style.borderColor = '#0d6efd';
        this.style.backgroundColor = '#e7f1ff';
    });

    uploadZone.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = 'transparent';
    });

    uploadZone.addEventListener('drop', function(e) {
        e.preventDefault();
        this.style.borderColor = '#dee2e6';
        this.style.backgroundColor = 'transparent';
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            displayFileInfo(files[0]);
        }
    });

    // Display file info when selected
    fileInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            displayFileInfo(this.files[0]);
        }
    });

    function displayFileInfo(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.style.display = 'block';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }
});
</script>
<?= $this->endSection() ?>