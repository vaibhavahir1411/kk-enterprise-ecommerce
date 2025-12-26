<?php require_once 'includes/header.php'; ?>

<!-- Page Header -->
<div class="page-header">
    <div class="container">
        <h1 class="page-title animated-text">Get in Touch</h1>
        <p class="page-subtitle">We'd love to hear from you. Here's how you can reach us.</p>
    </div>
</div>

<!-- Contact Section -->
<section class="section">
    <div class="container">
        <div class="contact-grid">
            <!-- Contact Form -->
            <div class="contact-form-wrapper reveal">
                <div class="form-header">
                    <h2>Send us a Message</h2>
                    <p>Have a question or want to place a bulk order? Fill out the form below.</p>
                </div>
                
                <form action="process_contact.php" method="POST">
                    <div class="form-group">
                        <input type="text" name="name" id="name" class="form-input" placeholder=" " required>
                        <label for="name" class="form-label">Your Name</label>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <input type="email" name="email" id="email" class="form-input" placeholder=" " required>
                            <label for="email" class="form-label">Email Address</label>
                        </div>
                        <div class="form-group">
                            <input type="tel" name="phone" id="phone" class="form-input" placeholder=" " required>
                            <label for="phone" class="form-label">Phone Number</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <input type="text" name="address" id="address" class="form-input" placeholder=" ">
                        <label for="address" class="form-label">Address (Optional)</label>
                    </div>
                    
                    <div class="form-group">
                        <textarea name="message" id="message" class="form-input form-textarea" placeholder=" " required></textarea>
                        <label for="message" class="form-label">Your Message</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-full">Send Message</button>
                </form>
            </div>
            
            <!-- Contact Info -->
            <div class="contact-info-wrapper reveal delay-1">
                <div class="info-card">
                    <h3>Contact Information</h3>
                    <p>Reach out to us directly through any of these channels.</p>
                    
                    <div class="info-items">
                        <div class="info-item">
                            <div class="info-icon-wrapper">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <div class="info-content">
                                <h4>Our Location</h4>
                                <p>123 Firework Lane, Sivakasi,<br>Tamil Nadu - 626123</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon-wrapper">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="info-content">
                                <h4>Phone Number</h4>
                                <p>+91 98765 43210<br>+91 98765 43211</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon-wrapper">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h4>Email Address</h4>
                                <p>info@kkenterprise.com<br>support@kkenterprise.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-connect">
                        <h4>Follow Us</h4>
                        <div class="social-links">
                            <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
                            <a href="#" class="social-link"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
                
                <div class="map-placeholder">
                    <div class="map-overlay">
                        <i class="bi bi-map map-icon"></i>
                        <p>Detailed Map View</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Bulk Order Section -->
<section class="section bg-light">
    <div class="container">
        <div class="bulk-order-card reveal">
            <div class="bulk-icon">🎉</div>
            <h2>Planning a Big Celebration?</h2>
            <p>We offer special discounts and custom packages for weddings, festivals, and corporate events. Get the best fireworks at wholesale prices.</p>
            
            <div class="bulk-features">
                <div class="bulk-feature">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>Wholesale Pricing</span>
                </div>
                <div class="bulk-feature">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>Custom Packages</span>
                </div>
                <div class="bulk-feature">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>Priority Delivery</span>
                </div>
                <div class="bulk-feature">
                    <i class="bi bi-check-circle-fill text-success"></i>
                    <span>Expert Advice</span>
                </div>
            </div>
            
            <div class="bulk-contact">
                Call our dedicated bulk order line: <strong>+91 98765 43210</strong>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Common Questions</h2>
        </div>
        
        <div class="faq-grid reveal">
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Do you deliver all over India?</h4>
                    <i class="bi bi-plus-lg faq-icon"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, we deliver to most major cities and towns across India. Please enter your pincode on the checkout page to check delivery availability.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Is there a minimum order value?</h4>
                    <i class="bi bi-plus-lg faq-icon"></i>
                </div>
                <div class="faq-answer">
                    <p>Yes, the minimum order value for delivery is ₹2000. This ensures we can provide safe and secure packaging for your products.</p>
                </div>
            </div>
            
            <div class="faq-item">
                <div class="faq-question">
                    <h4>Are your products certified?</h4>
                    <i class="bi bi-plus-lg faq-icon"></i>
                </div>
                <div class="faq-answer">
                    <p>Absolutely. All our products are 100% Green Fireworks certified by CSIR-NEERI and meet all safety regulations.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'includes/footer.php'; ?>
