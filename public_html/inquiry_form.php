<?php
require_once 'includes/header.php';

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 text-center">Submit Your Inquiry</h2>
            <div class="card shadow">
                <div class="card-body p-4">
                    <form action="submit_inquiry.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email (Optional)</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Delivery Address <span class="text-danger">*</span></label>
                            <textarea name="address" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Any Special Instructions?</label>
                            <textarea name="message" class="form-control" rows="2"></textarea>
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="bi bi-info-circle"></i> This is just an inquiry. No payment is required now. We will contact you for confirmation and delivery.
                        </div>

                        <button type="submit" class="btn btn-success w-100 btn-lg">Submit Inquiry</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
