<footer class="ms-footer">
    <div class="ms-footer-wrap">
        <div class="ms-footer-grid">
            <!-- 1. COMPANY -->
            <div class="ms-footer-col">
                <h4 class="ms-footer-heading">Company</h4>
                <ul class="ms-footer-links">
                    <li><a href="javascript:void(0)" data-policy-trigger="about">About MediServe</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="career">Careers</a></li>
                    <li><a href="{{ route('roadmap') }}">Platform Roadmap</a></li>
                    <li><a href="{{ route('customer-requirements') }}">Customer Requirements</a></li>
                    <li><a href="{{ route('login') }}">Partner Store Portal</a></li>
                </ul>
            </div>

            <!-- 2. OUR POLICIES -->
            <div class="ms-footer-col">
                <h4 class="ms-footer-heading">Our Policies</h4>
                <ul class="ms-footer-links">
                    <li><a href="javascript:void(0)" data-policy-trigger="terms">Terms &amp; Conditions</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="privacy">Privacy Policy</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="payments">Fees &amp; Payments Policy</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="shipping">Shipping &amp; Delivery Policy</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="returns">Return, Refund &amp; Cancellation Policy</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="editorial">Editorial Policy</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="caution">Caution Notice</a></li>
                </ul>
            </div>

            <!-- 3. SHOPPING -->
            <div class="ms-footer-col">
                <h4 class="ms-footer-heading">Shopping</h4>
                <ul class="ms-footer-links">
                    <li><a href="{{ route('home') }}">Medicines A to Z</a></li>
                    <li><a href="{{ route('prescription.upload.start') }}">Upload Prescription</a></li>
                    <li><a href="{{ route('home', ['deal' => 'discounted']) }}">Offers / Deals</a></li>
                    <li><a href="{{ route('cart.index') }}">My Cart</a></li>
                    <li><a href="{{ route('wishlist.index') }}">Saved Items</a></li>
                    <li><a href="{{ route('orders.index') }}">Track Orders</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="faq">FAQs</a></li>
                    <li><a href="javascript:void(0)" data-policy-trigger="contact">Contact Us</a></li>
                </ul>
            </div>

            <!-- 4. SOCIAL -->
            <div class="ms-footer-col">
                <h4 class="ms-footer-heading">Social</h4>
                <ul class="ms-footer-links">
                    <li><a href="https://facebook.com" target="_blank" rel="noopener noreferrer">Facebook</a></li>
                    <li><a href="https://twitter.com" target="_blank" rel="noopener noreferrer">Twitter (X)</a></li>
                    <li><a href="https://linkedin.com" target="_blank" rel="noopener noreferrer">LinkedIn</a></li>
                    <li><a href="https://youtube.com" target="_blank" rel="noopener noreferrer">YouTube</a></li>
                    <li><a href="https://instagram.com" target="_blank" rel="noopener noreferrer">Instagram</a></li>
                </ul>
            </div>

            <!-- 5. SUBSCRIBE TO OUR NEWSLETTER -->
            <div class="ms-footer-col ms-footer-col-newsletter">
                <h4 class="ms-footer-heading">Subscribe to our Newsletter</h4>
                <p class="ms-newsletter-desc">Get a free subscription to our health and fitness tips and stay tuned to our latest offers</p>
                <form class="ms-newsletter-form" id="ms-newsletter-form" onsubmit="return handleNewsletterSubmit(event)">
                    <div class="ms-newsletter-input-wrap">
                        <input
                            type="text"
                            id="ms-newsletter-email"
                            class="ms-newsletter-input"
                            placeholder="enter your email address"
                            autocomplete="email"
                            aria-label="Enter your email address"
                        >
                        <button type="submit" class="ms-newsletter-btn" aria-label="Subscribe to newsletter">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                        </button>
                    </div>
                    <div id="ms-newsletter-error" class="ms-newsletter-msg ms-newsletter-error" style="display:none">Please enter valid email address</div>
                    <div id="ms-newsletter-success" class="ms-newsletter-msg ms-newsletter-success" style="display:none">✓ Thank you for subscribing to MediServe updates!</div>
                </form>
                <div class="ms-trust-badges">
                    <span class="ms-badge-pill">🔒 Genuine Medicines</span>
                    <span class="ms-badge-pill">⚡ Express Delivery</span>
                </div>
            </div>
        </div>

        <!-- BOTTOM COPYRIGHT BAR -->
        <div class="ms-footer-bottom">
            <div class="ms-bottom-brand">
                <span class="ms-india-flag" aria-hidden="true">
                    <span style="color:#ff9933">#</span><span style="color:#087f5b">MadeIn</span><span style="color:#073d31">India</span>
                </span>
                <span class="ms-copyright-text">
                    &copy; {{ date('Y') }} MediServe Marketplace &middot; By <strong style="color:#18332c">Tejasweb Solutions</strong>. All rights reserved.
                </span>
            </div>
            <div class="ms-bottom-badges">
                <span>Licensed Pharmacists</span> &bull; 
                <span>Cashfree Verified</span> &bull; 
                <span>Doorstep Delivery</span>
            </div>
        </div>
    </div>

    <!-- POLICY & INFO POPUP MODAL -->
    <div id="ms-policy-modal" class="ms-modal-backdrop" style="display:none" role="dialog" aria-modal="true" aria-labelledby="ms-modal-title">
        <div class="ms-modal-card">
            <div class="ms-modal-head">
                <h3 id="ms-modal-title" class="ms-modal-heading">Policy Information</h3>
                <button type="button" class="ms-modal-close" onclick="closePolicyModal()" aria-label="Close modal">&times;</button>
            </div>
            <div id="ms-modal-body" class="ms-modal-body">
                <!-- Injected via JS -->
            </div>
            <div class="ms-modal-foot">
                <button type="button" class="ms-modal-btn" onclick="closePolicyModal()">Close</button>
            </div>
        </div>
    </div>
</footer>

<style>
    .ms-footer {
        margin-top: 60px;
        background: #f7faf9;
        border-top: 1px solid #e5eee9;
        color: #556b64;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
        font-size: 14px;
        line-height: 1.5;
    }
    .ms-footer-wrap {
        width: min(1180px, calc(100% - 40px));
        margin: 0 auto;
        padding: 50px 0 25px;
    }
    .ms-footer-grid {
        display: grid;
        grid-template-columns: 1.1fr 1.3fr 1.15fr 0.9fr 1.8fr;
        gap: 32px;
        margin-bottom: 40px;
    }
    .ms-footer-col {
        display: flex;
        flex-direction: column;
    }
    .ms-footer-heading {
        margin: 0 0 18px;
        color: #112822;
        font-size: 13px;
        font-weight: 850;
        letter-spacing: 0.6px;
        text-transform: uppercase;
    }
    .ms-footer-links {
        list-style: none;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .ms-footer-links a {
        color: #5c736b;
        text-decoration: none;
        font-size: 13.5px;
        transition: color 0.16s ease, transform 0.16s ease;
        display: inline-block;
    }
    .ms-footer-links a:hover {
        color: #087f5b;
        transform: translateX(2px);
    }
    .ms-newsletter-desc {
        color: #556b64;
        font-size: 13.5px;
        line-height: 1.45;
        margin: 0 0 16px;
    }
    .ms-newsletter-form {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }
    .ms-newsletter-input-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
        position: relative;
    }
    .ms-newsletter-input {
        flex: 1;
        min-width: 0;
        background: transparent;
        border: 0;
        border-bottom: 2px solid #5a736a;
        padding: 9px 4px;
        color: #18332c;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s ease;
    }
    .ms-newsletter-input:focus {
        border-bottom-color: #087f5b;
    }
    .ms-newsletter-input::placeholder {
        color: #8b9e97;
    }
    .ms-newsletter-btn {
        width: 44px;
        height: 38px;
        border: 0;
        border-radius: 8px;
        background: #b6eedf;
        color: #054f39;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: background 0.18s ease, transform 0.15s ease;
    }
    .ms-newsletter-btn:hover {
        background: #087f5b;
        color: #ffffff;
        transform: translateY(-1px);
    }
    .ms-newsletter-msg {
        font-size: 12px;
        margin-top: 4px;
        font-weight: 600;
    }
    .ms-newsletter-error {
        color: #d9383a;
    }
    .ms-newsletter-success {
        color: #087f5b;
    }
    .ms-trust-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 18px;
    }
    .ms-badge-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 20px;
        background: #eaf4f0;
        color: #125740;
        font-size: 11.5px;
        font-weight: 700;
    }
    .ms-footer-bottom {
        border-top: 1px solid #e5eee9;
        padding-top: 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        font-size: 13px;
        color: #6a8078;
    }
    .ms-bottom-brand {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }
    .ms-india-flag {
        font-weight: 800;
        letter-spacing: 0.3px;
        background: #ffffff;
        padding: 3px 8px;
        border-radius: 6px;
        border: 1px solid #dce8e2;
        font-size: 12px;
    }
    .ms-bottom-badges {
        font-size: 12px;
        color: #799088;
    }

    /* Policy Modal Styles */
    .ms-modal-backdrop {
        position: fixed;
        inset: 0;
        z-index: 9999;
        background: rgba(14, 38, 31, 0.58);
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .ms-modal-card {
        background: #ffffff;
        border-radius: 18px;
        max-width: 620px;
        width: 100%;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 50px rgba(7, 45, 36, 0.25);
        overflow: hidden;
        animation: msModalPop 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    }
    @keyframes msModalPop {
        from { opacity: 0; transform: scale(0.96) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }
    .ms-modal-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px 24px;
        border-bottom: 1px solid #eef3f0;
        background: #fbfdfc;
    }
    .ms-modal-heading {
        margin: 0;
        font-size: 18px;
        font-weight: 800;
        color: #112822;
    }
    .ms-modal-close {
        border: 0;
        background: transparent;
        font-size: 26px;
        line-height: 1;
        color: #718780;
        cursor: pointer;
        padding: 0 4px;
        border-radius: 6px;
    }
    .ms-modal-close:hover {
        color: #112822;
        background: #eef5f1;
    }
    .ms-modal-body {
        padding: 24px;
        overflow-y: auto;
        color: #354d45;
        font-size: 14px;
        line-height: 1.65;
    }
    .ms-modal-body h4 {
        margin: 16px 0 6px;
        color: #112822;
        font-size: 14.5px;
    }
    .ms-modal-body p {
        margin: 0 0 12px;
    }
    .ms-modal-body ul {
        margin: 0 0 14px;
        padding-left: 20px;
    }
    .ms-modal-body li {
        margin-bottom: 6px;
    }
    .ms-modal-foot {
        padding: 14px 24px;
        border-top: 1px solid #eef3f0;
        background: #fbfdfc;
        display: flex;
        justify-content: flex-end;
    }
    .ms-modal-btn {
        padding: 9px 20px;
        background: #087f5b;
        color: #ffffff;
        font-weight: 700;
        border: 0;
        border-radius: 8px;
        cursor: pointer;
        font-size: 13.5px;
    }
    .ms-modal-btn:hover {
        background: #066749;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .ms-footer-grid {
            grid-template-columns: repeat(3, 1fr);
            gap: 26px;
        }
        .ms-footer-col-newsletter {
            grid-column: 1 / -1;
            max-width: 520px;
        }
    }
    @media (max-width: 640px) {
        .ms-footer-wrap {
            padding: 38px 0 20px;
            width: calc(100% - 28px);
        }
        .ms-footer-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 22px;
        }
        .ms-footer-col-newsletter {
            grid-column: 1 / -1;
            max-width: 100%;
        }
        .ms-footer-bottom {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
    @media (max-width: 420px) {
        .ms-footer-grid {
            grid-template-columns: 1fr;
            gap: 22px;
        }
    }
</style>

<script>
    const msPolicyData = {
        about: {
            title: "About MediServe",
            html: `
                <p><strong>MediServe</strong> is your trusted online healthcare and pharmacy platform designed to make essential medicines, wellness products, and healthcare services fast, accessible, and affordable.</p>
                <h4>Our Mission</h4>
                <p>We connect patients directly with licensed local pharmacies and qualified delivery captains to deliver genuine medications to your doorstep in minutes, supported by digital prescriptions and live tracking.</p>
                <h4>Developed By</h4>
                <p>MediServe is proudly engineered and managed by <strong>Tejasweb Solutions</strong>, building cutting-edge healthcare technology for India.</p>
            `
        },
        career: {
            title: "Careers at MediServe",
            html: `
                <p>Join our team of technology innovators, delivery captains, and healthcare logistics professionals who are reimagining pharmacy fulfillment in India.</p>
                <h4>Open Opportunities</h4>
                <ul>
                    <li><strong>Delivery Captains:</strong> Flexible hours, weekly COD payouts, and safety gear.</li>
                    <li><strong>Store Operations:</strong> Partner management and prescription fulfillment specialists.</li>
                    <li><strong>Full-Stack Engineers:</strong> PHP, Laravel, and Mobile application development.</li>
                </ul>
                <p>Interested candidates can write to us with their resume at <strong>careers@mediserve.test</strong>.</p>
            `
        },
        terms: {
            title: "Terms & Conditions",
            html: `
                <p>Welcome to MediServe. By accessing our platform, website, or mobile services, you agree to comply with and be bound by the following terms.</p>
                <h4>1. Medical Prescriptions</h4>
                <p>Prescription medications (Schedule H and Schedule X drugs) are dispensed strictly against a valid, legible prescription issued by a registered medical practitioner.</p>
                <h4>2. Orders and Pricing</h4>
                <p>All prices listed on the platform include applicable GST. We reserve the right to correct pricing errors and cancel orders where stock is unavailable or delivery address is outside the serviceable radius.</p>
                <h4>3. Customer Responsibilities</h4>
                <p>Customers must provide accurate delivery addresses, contact numbers, and ensure an authorized recipient is present at the delivery location.</p>
            `
        },
        privacy: {
            title: "Privacy Policy",
            html: `
                <p>Your privacy and the security of your medical data are of paramount importance to MediServe.</p>
                <h4>Information We Collect</h4>
                <p>We collect essential information required for order fulfillment, including your name, contact phone number, delivery address, and prescription images.</p>
                <h4>Data Protection &amp; Confidentiality</h4>
                <p>Your uploaded prescriptions and personal medical records are accessible only by authorized partner pharmacies and licensed pharmacists reviewing your order. We never sell or rent your personal data to third parties.</p>
            `
        },
        payments: {
            title: "Fees & Payments Policy",
            html: `
                <p>MediServe offers convenient and secure payment options for all customers:</p>
                <h4>Supported Payment Modes</h4>
                <ul>
                    <li><strong>Cashfree Online Payments:</strong> Instant, encrypted checkout supporting UPI (GPay, PhonePe, Paytm), Credit Cards, Debit Cards, and Net Banking.</li>
                    <li><strong>Cash on Delivery (COD):</strong> Pay cash directly to the delivery captain upon physical receipt and inspection of your medicines.</li>
                </ul>
                <h4>Delivery Charges</h4>
                <p>Delivery fees are calculated transparently based on distance from the nearest approved partner pharmacy to your doorstep.</p>
            `
        },
        shipping: {
            title: "Shipping & Delivery Policy",
            html: `
                <p>We pride ourselves on prompt, safe, and hygienic medicine delivery.</p>
                <h4>Delivery Timelines</h4>
                <p>Orders within the designated store radius (typically 5–10 km) are delivered within <strong>30 to 60 minutes</strong> after store confirmation.</p>
                <h4>Safe Handling</h4>
                <p>Medicines are packed in sealed, tamper-evident packages and delivered by trained captains. Cold-chain storage requirements are strictly adhered to for specialized products.</p>
            `
        },
        returns: {
            title: "Return, Refund & Cancellation Policy",
            html: `
                <p>We stand behind the authenticity and quality of every health product delivered.</p>
                <h4>Return Eligibility</h4>
                <p>Items may be returned within <strong>48 hours</strong> of delivery if they are damaged in transit, expired, or if an incorrect product was delivered.</p>
                <h4>Non-Returnable Items</h4>
                <p>Opened consumables, temperature-sensitive vaccines/insulin, and baby foods cannot be returned once delivered for health and safety compliance.</p>
                <h4>Refund Processing</h4>
                <p>Refunds for prepaid orders are credited back to the original payment source within <strong>5–7 business days</strong> following inspection.</p>
            `
        },
        editorial: {
            title: "Editorial Policy",
            html: `
                <p>All healthcare guides, composition summaries, medicine usages, and health tips published on MediServe undergo rigorous editorial review:</p>
                <ul>
                    <li>Content is verified against standard pharmacological compendiums and manufacturer leaflets.</li>
                    <li>Content is intended solely for informative purposes and does not substitute for professional medical advice, diagnosis, or treatment.</li>
                </ul>
            `
        },
        caution: {
            title: "Caution Notice",
            html: `
                <p><strong>Important Healthcare Advisory:</strong></p>
                <p>Please strictly follow the dosage instructions prescribed by your physician or as indicated on the medicine packaging.</p>
                <p>Do not consume medicines past their expiry date. Store medicines in a cool, dry place out of reach of children. If you experience unexpected side effects, discontinue use immediately and seek medical attention.</p>
            `
        },
        faq: {
            title: "Frequently Asked Questions",
            html: `
                <h4>How do I upload a prescription?</h4>
                <p>Click on the <strong>Upload prescription</strong> button on the home banner or footer, log in with your mobile OTP, and upload a clear photo or PDF of your doctor's prescription. Our store pharmacist will review it and prepare your order.</p>
                <h4>Can I track my order live?</h4>
                <p>Yes. Go to <strong>Account &rarr; Your Orders</strong> to view the real-time status of your order, including store acceptance, captain assignment, and delivery.</p>
                <h4>Is Cash on Delivery (COD) available?</h4>
                <p>Yes, COD is available for all standard pharmacy orders within our local delivery zones.</p>
            `
        },
        contact: {
            title: "Contact Us",
            html: `
                <p>We are here to assist you with your orders, prescription queries, or feedback.</p>
                <h4>MediServe Support Desk</h4>
                <ul>
                    <li><strong>Customer Support Phone:</strong> +91 9595959595</li>
                    <li><strong>Email Assistance:</strong> support@mediserve.test</li>
                    <li><strong>Corporate Office:</strong> MediServe / Tejasweb Solutions, HCL IT SEZ, Lucknow, Uttar Pradesh 226016, India</li>
                    <li><strong>Operating Hours:</strong> 8:00 AM &ndash; 10:00 PM (Monday to Sunday)</li>
                </ul>
            `
        }
    };

    function handleNewsletterSubmit(event) {
        event.preventDefault();
        const input = document.getElementById('ms-newsletter-email');
        const errEl = document.getElementById('ms-newsletter-error');
        const succEl = document.getElementById('ms-newsletter-success');
        const email = (input ? input.value : '').trim();

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email || !emailRegex.test(email)) {
            if (errEl) errEl.style.display = 'block';
            if (succEl) succEl.style.display = 'none';
            if (input) input.focus();
            return false;
        }

        if (errEl) errEl.style.display = 'none';
        if (succEl) succEl.style.display = 'block';
        if (input) {
            input.value = '';
            input.disabled = true;
        }
        return false;
    }

    function openPolicyModal(key) {
        const item = msPolicyData[key];
        if (!item) return;

        const modal = document.getElementById('ms-policy-modal');
        const titleEl = document.getElementById('ms-modal-title');
        const bodyEl = document.getElementById('ms-modal-body');

        if (titleEl) titleEl.innerText = item.title;
        if (bodyEl) bodyEl.innerHTML = item.html;
        if (modal) modal.style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePolicyModal() {
        const modal = document.getElementById('ms-policy-modal');
        if (modal) modal.style.display = 'none';
        document.body.style.overflow = '';
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-policy-trigger]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                const key = btn.getAttribute('data-policy-trigger');
                openPolicyModal(key);
            });
        });

        const modal = document.getElementById('ms-policy-modal');
        if (modal) {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) closePolicyModal();
            });
        }

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') closePolicyModal();
        });
    });
</script>
