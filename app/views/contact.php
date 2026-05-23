<?php require 'layouts/header.php'; ?>
<main class="content-wrapper">
    <section class="hero-simple">
        <h1>Help Center</h1>
        <p>Find answers to common questions or reach out to our support team.</p>
    </section>
    <section class="faq-grid container">
        <div class="faq-card"><h3>How do I sign up?</h3><p>Click the "Sign In" button on the homepage and follow the registration prompts to create your farm profile.</p></div>
        <div class="faq-card"><h3>How to reset data?</h3><p>Go to your dashboard > settings > data management to reset or archive your logs.</p></div>
        <div class="faq-card"><h3>Is the app free?</h3><p>Yes, basic features are free forever. We offer premium options for large-scale industrial farms.</p></div>
    </section>
    <section class="contact-section container">
        <div class="contact-container">
            <h2>Contact Us</h2>
            <form action="#" class="contact-form">
                <div class="form-row">
                    <div class="form-group"><label>Name</label><input type="text" placeholder="Your Name"></div>
                    <div class="form-group"><label>Email</label><input type="email" placeholder="Your Email"></div>
                </div>
                <div class="form-group"><label>Message</label><textarea rows="5" placeholder="How can we help you?"></textarea></div>
                <button type="submit" class="btn-submit">Send Message</button>
            </form>
        </div>
    </section>
</main>
<?php require 'layouts/footer.php'; ?>