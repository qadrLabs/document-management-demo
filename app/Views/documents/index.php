<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-folder2-open"></i> Daftar Dokumen
                    </h4>
                    <a href="<?= base_url('documents/upload') ?>" class="btn btn-light btn-sm">
                        <i class="bi bi-cloud-upload"></i> Upload Dokumen
                    </a>
                </div>
                <div class="card-body">
                    <!-- Alert Messages -->
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($documents) && is_array($documents)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="50">#</th>
                                        <th width="60">Icon</th>
                                        <th>Judul</th>
                                        <th>Nama File</th>
                                        <th>Ukuran</th>
                                        <th>Tipe</th>
                                        <th>Tanggal Upload</th>
                                        <th width="150">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    foreach ($documents as $doc): 
                                        $model = new \App\Models\DocumentModel();
                                        $formattedSize = $model->getFormattedSize($doc['file_size']);
                                        
                                        // Determine icon based on file extension
                                        $icon = 'bi-file-earmark';
                                        $iconColor = 'text-secondary';
                                        switch(strtolower($doc['file_extension'])) {
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
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td class="text-center">
                                            <i class="bi <?= $icon ?> <?= $iconColor ?> file-icon"></i>
                                        </td>
                                        <td>
                                            <strong><?= esc($doc['title']) ?></strong>
                                            <?php if ($doc['description']): ?>
                                                <br><small class="text-muted"><?= esc(substr($doc['description'], 0, 50)) ?>...</small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <small><?= esc($doc['file_original_name']) ?></small>
                                        </td>
                                        <td><?= $formattedSize ?></td>
                                        <td>
                                            <span class="badge bg-secondary"><?= strtoupper($doc['file_extension']) ?></span>
                                        </td>
                                        <td>
                                            <small><?= date('d/m/Y H:i', strtotime($doc['created_at'])) ?></small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="<?= base_url('documents/view/' . $doc['id']) ?>" 
                                                   class="btn btn-info" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="<?= base_url('documents/download/' . $doc['id']) ?>" 
                                                   class="btn btn-success" title="Download">
                                                    <i class="bi bi-download"></i>
                                                </a>
                                                <a href="<?= base_url('documents/delete/' . $doc['id']) ?>" 
                                                   class="btn btn-danger" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus dokumen ini?')"
                                                   title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            <?= $pager->links('default', 'bootstrap_pagination') ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5">
                            <i class="bi bi-folder2-open text-muted" style="font-size: 5rem;"></i>
                            <h5 class="mt-3 text-muted">Belum ada dokumen</h5>
                            <p class="text-muted">Mulai upload dokumen pertama Anda</p>
                            <a href="<?= base_url('documents/upload') ?>" class="btn btn-primary">
                                <i class="bi bi-cloud-upload"></i> Upload Dokumen
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>