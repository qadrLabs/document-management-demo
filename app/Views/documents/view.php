<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="bi bi-file-earmark-text"></i> Detail Dokumen
                    </h4>
                </div>
                <div class="card-body">
                    <?php 
                    // Determine icon
                    $icon = 'bi-file-earmark';
                    $iconColor = 'text-secondary';
                    switch(strtolower($document['file_extension'])) {
                        case 'pdf':
                            $icon = 'bi-file-earmark-pdf-fill';
                            $iconColor = 'text-danger';
                            break;
                        case 'doc':
                        case 'docx':
                            $icon = 'bi-file-earmark-word-fill';
                            $iconColor = 'text-primary';
                            break;
                        case 'xls':
                        case 'xlsx':
                            $icon = 'bi-file-earmark-excel-fill';
                            $iconColor = 'text-success';
                            break;
                        case 'ppt':
                        case 'pptx':
                            $icon = 'bi-file-earmark-ppt-fill';
                            $iconColor = 'text-warning';
                            break;
                        case 'zip':
                        case 'rar':
                            $icon = 'bi-file-earmark-zip-fill';
                            $iconColor = 'text-info';
                            break;
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                        case 'gif':
                            $icon = 'bi-file-earmark-image-fill';
                            $iconColor = 'text-success';
                            break;
                        case 'txt':
                            $icon = 'bi-file-earmark-text-fill';
                            $iconColor = 'text-secondary';
                            break;
                    }
                    ?>
                    
                    <div class="text-center mb-4">
                        <i class="bi <?= $icon ?> <?= $iconColor ?>" style="font-size: 5rem;"></i>
                    </div>

                    <table class="table table-borderless">
                        <tr>
                            <th width="200">Judul:</th>
                            <td><?= esc($document['title']) ?></td>
                        </tr>
                        <?php if ($document['description']): ?>
                        <tr>
                            <th>Deskripsi:</th>
                            <td><?= esc($document['description']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Nama File Original:</th>
                            <td><?= esc($document['file_original_name']) ?></td>
                        </tr>
                        <tr>
                            <th>Nama File Sistem:</th>
                            <td><code><?= esc($document['file_name']) ?></code></td>
                        </tr>
                        <tr>
                            <th>Tipe File:</th>
                            <td><?= esc($document['file_type']) ?></td>
                        </tr>
                        <tr>
                            <th>Ekstensi:</th>
                            <td><span class="badge bg-secondary"><?= strtoupper($document['file_extension']) ?></span></td>
                        </tr>
                        <tr>
                            <th>Ukuran File:</th>
                            <td><?= $formatted_size ?> (<?= number_format($document['file_size']) ?> bytes)</td>
                        </tr>
                        <?php if ($document['uploaded_by']): ?>
                        <tr>
                            <th>Diupload Oleh:</th>
                            <td><?= esc($document['uploaded_by']) ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <th>Tanggal Upload:</th>
                            <td><?= date('d F Y H:i:s', strtotime($document['created_at'])) ?></td>
                        </tr>
                        <?php if ($document['updated_at'] && $document['updated_at'] != $document['created_at']): ?>
                        <tr>
                            <th>Terakhir Diperbarui:</th>
                            <td><?= date('d F Y H:i:s', strtotime($document['updated_at'])) ?></td>
                        </tr>
                        <?php endif; ?>
                    </table>

                    <!-- Preview for Images -->
                    <?php if (in_array(strtolower($document['file_extension']), ['jpg', 'jpeg', 'png', 'gif'])): ?>
                        <div class="mt-4">
                            <h5>Preview:</h5>
                            <div class="text-center">
                                <img src="<?= base_url('uploads/' . $document['file_name']) ?>" 
                                     alt="<?= esc($document['title']) ?>" 
                                     class="img-fluid rounded" 
                                     style="max-height: 400px;">
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="<?= base_url('documents') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>
                        <a href="<?= base_url('documents/download/' . $document['id']) ?>" class="btn btn-success">
                            <i class="bi bi-download"></i> Download
                        </a>
                        <a href="<?= base_url('documents/delete/' . $document['id']) ?>" 
                           class="btn btn-danger"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')">
                            <i class="bi bi-trash"></i> Hapus
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>