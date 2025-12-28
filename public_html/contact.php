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
            <!-- Left Column: Form & Hours -->
            <div class="contact-left-column">
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

                <!-- Business Hours -->
                <div class="info-card reveal mt-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="info-icon-wrapper me-3" style="width: 40px; height: 40px; font-size: 1.2rem;">
                            <i class="bi bi-clock"></i>
                        </div>
                        <h3 class="mb-0">Business Hours</h3>
                    </div>
                    <div class="hours-list">
                        <div class="d-flex justify-content-between py-2 border-bottom">
                            <span class="text-muted">Mon - Tue / Thu - Sun</span>
                            <span class="fw-bold text-success text-end">Open 24 Hours</span>
                        </div>
                        <div class="d-flex justify-content-between py-2">
                            <span class="text-muted">Wednesday</span>
                            <span class="fw-bold text-dark text-end">02:00 PM - 11:30 PM</span>
                        </div>
                    </div>
                </div>
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
                                <p>4,Samrat Ind. Area, B/h Naraya Petrol Pump, Gondal Road, Rajkot-360004</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon-wrapper">
                                <i class="bi bi-telephone"></i>
                            </div>
                            <div class="info-content">
                                <h4>Phone Number</h4>
                                <p>+91 96380 03698</p>
                            </div>
                        </div>
                        
                        <div class="info-item">
                            <div class="info-icon-wrapper">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <div class="info-content">
                                <h4>Email Address</h4>
                                <p>kkfireworksgujarat@gmail.com</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="social-connect">
                        <h4>Follow Us</h4>
                        <div class="social-links">
                            <a href="https://www.facebook.com/profile.php?id=100092492319763" class="social-link" target="_blank"><i class="bi bi-facebook"></i></a>
                            <a href="https://www.instagram.com/k.k.fireworks_/?hl=en" class="social-link" target="_blank"><i class="bi bi-instagram"></i></a>
                            <a href="https://www.youtube.com/channel/UCrTbV4mhpg1ByZuQQ5THpyw" class="social-link" target="_blank"><i class="bi bi-youtube"></i></a>
                            <a href="https://wa.me/919638003698" class="social-link" target="_blank"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>
                </div>
                
                <a href="https://www.google.com/maps/place/K.K.+Enterprise+-+Fireworks+365+Days./@22.2614668,70.7968532,17z/data=!4m6!3m5!1s0x3959cbe7a6e1c5bf:0x92bd8d06d095a943!8m2!3d22.2603051!4d70.7985269!16s%2Fg%2F11trv5nxv_?entry=ttu&g_ep=EgoyMDI1MTIwOS4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="map-link-wrapper">
                    <div class="map-container rounded shadow-sm overflow-hidden mt-4">
                        <img src="assets/map_image.png" alt="KK Fireworks Location Map" class="img-fluid w-100" style="object-fit: cover; min-height: 250px;">
                        <div class="map-overlay" style="background: rgba(0,0,0,0.4); display: flex; align-items: center; justify-content: center; color: white;">
                             <p class="mb-0"><i class="bi bi-geo-alt-fill me-2"></i> View on Google Maps</p>
                        </div>
                    </div>
                </a>
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
                Call our dedicated bulk order line: <strong>+91 96380 03698</strong>
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
