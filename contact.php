<?php
$pageTitle = 'Contact | Quantum Scalp AI';
$pageDescription = 'Questions about the technology, membership, partnership, or compliance? Reach out and select the right category.';
$currentPage = 'contact';
include 'inc/public-start.php';
include 'header.php';
?>
<main>
    <section class="qs-hero qs-section--tight">
        <div class="qs-wrap">
            <p class="qs-eyebrow">Contact</p>
            <h1 class="qs-h1">Questions about the technology, membership, partnership, or compliance?</h1>
            <p class="qs-lead">Reach out and select the right category. Our team typically responds within one business day.</p>
        </div>
    </section>
    <section class="qs-section">
        <div class="qs-wrap qs-grid-2">
            <form class="qs-form qs-card" method="post" action="contact">
                <label class="qs-muted">Category
                    <select name="category" required>
                        <option value="technology">Technology</option>
                        <option value="membership">Membership / License</option>
                        <option value="partnership">Partnership</option>
                        <option value="compliance">Compliance</option>
                    </select>
                </label>
                <input type="text" name="name" placeholder="Full name" required>
                <input type="email" name="email" placeholder="Email" required>
                <textarea name="message" placeholder="How can we help?" required></textarea>
                <button class="qs-btn qs-btn--primary" type="submit">Send message →</button>
                <p class="qs-muted">This form is for inquiries only. Nothing here is financial advice. Trading involves risk.</p>
            </form>
            <div>
                <article class="qs-card" style="margin-bottom:16px;">
                    <h3 class="qs-h3">Mail</h3>
                    <p><a class="qs-text-link" href="mailto:info@quantumscalp.io">info@quantumscalp.io</a></p>
                </article>
                <article class="qs-card">
                    <h3 class="qs-h3">Sales</h3>
                    <p class="qs-muted">For Q-Core Enterprise, use Contact Sales on the license page or email us with “Enterprise” in the subject.</p>
                    <p style="margin-top:12px;"><a class="qs-btn qs-btn--ghost" href="pricing">View License Options</a></p>
                </article>
            </div>
        </div>
    </section>
</main>
<?php include 'footer.php'; include 'inc/public-end.php'; ?>
