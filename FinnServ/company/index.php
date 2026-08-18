<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/init.php';
require_once __DIR__ . '/../includes/auth.php';

// Retrieve active company ID context
$companyId = $_SESSION['company_id'] ?? 1;

// Fetch company details
$stmt = $pdo->prepare("SELECT id, name, mailing_name, address, state, country, pincode, phone, email, gstin FROM companies WHERE id = ? LIMIT 1");
$stmt->execute([$companyId]);
$company = $stmt->fetch();

if (!$company) {
    die('Company not found.');
}

$pageTitle = 'Company Profile';
require_once __DIR__ . '/../includes/header.php';
?>

<!-- Header Section -->
<div class="page-header">
    <div>
        <h1>Company Profile</h1>
        <p>View and manage your FinnServ company information.</p>
    </div>
    
    <a href="<?= BASE_URL ?>company/edit.php" class="btn btn-primary">Edit Company</a>
</div>

<!-- Profile details card -->
<div class="card">
    <div class="card-header">
        <h2><?= htmlspecialchars($company['name']) ?></h2>
    </div>
    
    <div class="company-details">
        <div class="detail-group">
            <span class="detail-label">Company Name</span>
            <strong><?= htmlspecialchars($company['name']) ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">Mailing Name</span>
            <strong><?= htmlspecialchars($company['mailing_name'] ?? '') ?: '—' ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">Address</span>
            <strong><?= nl2br(htmlspecialchars($company['address'] ?? '')) ?: '—' ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">State</span>
            <strong><?= htmlspecialchars($company['state'] ?? '') ?: '—' ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">Country</span>
            <strong><?= htmlspecialchars($company['country'] ?? '') ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">Pincode</span>
            <strong><?= htmlspecialchars($company['pincode'] ?? '') ?: '—' ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">Phone</span>
            <strong><?= htmlspecialchars($company['phone'] ?? '') ?: '—' ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">Email</span>
            <strong><?= htmlspecialchars($company['email'] ?? '') ?: '—' ?></strong>
        </div>
        
        <div class="detail-group">
            <span class="detail-label">GSTIN</span>
            <strong><?= htmlspecialchars($company['gstin'] ?? '') ?: '—' ?></strong>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>