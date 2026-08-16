<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MediServe') }} — Customer Panel Requirements</title>
    <style>
        :root {
            --ink: #0f172a;
            --ink-soft: #475569;
            --ink-faint: #94a3b8;
            --bg: #f6f8fb;
            --panel: #ffffff;
            --line: #e2e8f0;
            --teal: #0d9488;
            --teal-soft: #ccfbf1;
            --indigo: #4f46e5;
            --indigo-soft: #e0e7ff;
            --amber: #d97706;
            --amber-soft: #fef3c7;
            --blue: #2563eb;
            --blue-soft: #dbeafe;
            --green: #16a34a;
            --green-soft: #dcfce7;
            --red: #dc2626;
            --red-soft: #fee2e2;
            --radius: 14px;
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Segoe UI', Figtree, -apple-system, BlinkMacSystemFont, Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--ink);
            line-height: 1.55;
        }
        a { color: inherit; }
        .wrap { max-width: 1120px; margin: 0 auto; padding: 0 24px; }

        .topnav {
            position: sticky; top: 0; z-index: 20;
            background: rgba(246, 248, 251, 0.92);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid var(--line);
        }
        .topnav .wrap {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 14px; padding-bottom: 14px; gap: 16px; flex-wrap: wrap;
        }
        .brand { display: flex; align-items: center; gap: 10px; font-weight: 700; font-size: 15px; letter-spacing: 0.2px; }
        .brand .dot { width: 10px; height: 10px; border-radius: 50%; background: linear-gradient(135deg, var(--blue), var(--indigo)); }
        .navlinks { display: flex; gap: 4px; flex-wrap: wrap; font-size: 12.5px; }
        .navlinks a { text-decoration: none; color: var(--ink-soft); padding: 6px 10px; border-radius: 999px; font-weight: 600; }
        .navlinks a:hover { background: var(--panel); color: var(--ink); }

        .hero { padding: 48px 0 32px; }
        .eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase;
            color: var(--blue); background: var(--blue-soft); padding: 6px 12px; border-radius: 999px;
        }
        h1 { font-size: clamp(26px, 3.6vw, 36px); margin: 16px 0 10px; letter-spacing: -0.02em; }
        .hero p.lead { font-size: 16px; color: var(--ink-soft); max-width: 720px; margin: 0 0 18px; }
        .hero-meta { display: flex; gap: 18px; flex-wrap: wrap; font-size: 13px; color: var(--ink-faint); }
        .hero-meta strong { color: var(--ink); }
        .back-link { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: var(--ink-soft); text-decoration: none; font-weight: 600; margin-bottom: 14px; }
        .back-link:hover { color: var(--ink); }

        section { padding: 40px 0; }
        section + section { border-top: 1px solid var(--line); }
        .section-head { margin-bottom: 22px; }
        .section-head .kicker { font-size: 12px; font-weight: 700; letter-spacing: 0.06em; text-transform: uppercase; color: var(--ink-faint); margin-bottom: 6px; }
        .section-head h2 { font-size: 22px; margin: 0 0 6px; letter-spacing: -0.01em; }
        .section-head p { color: var(--ink-soft); margin: 0; max-width: 760px; font-size: 14px; }

        .card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
        .spec-card { background: var(--panel); border: 1px solid var(--line); border-radius: var(--radius); padding: 18px 20px; }
        .spec-card h4 { margin: 0 0 4px; font-size: 14.5px; display: flex; align-items: center; gap: 8px; }
        .spec-card .tag { font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.04em; padding: 2px 8px; border-radius: 999px; }
        .tag.core { background: var(--blue-soft); color: #1d4ed8; }
        .tag.v1 { background: var(--green-soft); color: #15803d; }
        .tag.later { background: #f1f5f9; color: var(--ink-soft); }
        .spec-card ul { margin: 8px 0 0; padding-left: 18px; font-size: 13px; color: var(--ink-soft); display: flex; flex-direction: column; gap: 5px; }
        .spec-card p.note { margin: 8px 0 0; font-size: 12.5px; color: var(--ink-faint); }

        .flow-steps { counter-reset: step; list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 10px; }
        .flow-steps li {
            counter-increment: step; position: relative; padding: 12px 16px 12px 46px;
            background: var(--panel); border: 1px solid var(--line); border-radius: 10px; font-size: 13.5px; color: var(--ink-soft);
        }
        .flow-steps li strong { color: var(--ink); }
        .flow-steps li::before {
            content: counter(step); position: absolute; left: 14px; top: 50%; transform: translateY(-50%);
            width: 22px; height: 22px; border-radius: 50%; background: var(--indigo-soft); color: var(--indigo);
            font-size: 12px; font-weight: 700; display: flex; align-items: center; justify-content: center;
        }

        table.spec-table { width: 100%; border-collapse: collapse; background: var(--panel); border: 1px solid var(--line); border-radius: var(--radius); overflow: hidden; font-size: 13px; }
        table.spec-table th, table.spec-table td { text-align: left; padding: 10px 14px; border-bottom: 1px solid var(--line); vertical-align: top; }
        table.spec-table th { background: #f8fafc; font-size: 11.5px; text-transform: uppercase; letter-spacing: 0.04em; color: var(--ink-faint); }
        table.spec-table tr:last-child td { border-bottom: none; }
        table.spec-table code { background: #f1f5f9; padding: 1px 6px; border-radius: 5px; font-size: 12px; }
        .table-scroll { overflow-x: auto; }

        .decision-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 16px; }
        .decision-card {
            background: var(--amber-soft); border: 1px solid #fde68a; border-radius: var(--radius); padding: 16px 18px;
        }
        .decision-card h4 { margin: 0 0 6px; font-size: 14px; color: #92400e; }
        .decision-card p { margin: 0 0 8px; font-size: 13px; color: #78350f; }
        .decision-card .default { font-size: 12.5px; color: #92400e; background: rgba(255,255,255,0.5); padding: 6px 10px; border-radius: 8px; }
        .decision-card .default strong { color: #78350f; }

        .seq-list { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 8px; }
        .seq-list li {
            display: flex; align-items: baseline; gap: 10px; background: var(--panel); border: 1px solid var(--line);
            border-radius: 10px; padding: 10px 14px; font-size: 13.5px;
        }
        .seq-list .n { font-weight: 800; color: var(--blue); font-size: 13px; min-width: 20px; }
        .seq-list .stack { font-size: 12px; color: var(--ink-faint); }

        .pill-row { display: flex; gap: 8px; flex-wrap: wrap; margin: 10px 0 0; }
        .pill { font-size: 11.5px; font-weight: 600; padding: 4px 10px; border-radius: 999px; background: #f1f5f9; color: var(--ink-soft); }

        footer { padding: 30px 0 50px; font-size: 12.5px; color: var(--ink-faint); text-align: center; }
    </style>
</head>
<body>

    <nav class="topnav">
        <div class="wrap">
            <div class="brand"><span class="dot"></span> {{ config('app.name', 'MediServe') }} — Customer Panel Requirements</div>
            <div class="navlinks">
                <a href="#identity">Identity</a>
                <a href="#address">Address</a>
                <a href="#storefront">Storefront</a>
                <a href="#cart-checkout">Cart &amp; Checkout</a>
                <a href="#prescription">Prescription</a>
                <a href="#tracking">Tracking</a>
                <a href="#engagement">Engagement</a>
                <a href="#account">Account</a>
                <a href="#nfr">Non-Functional</a>
                <a href="#data-model">Data Model</a>
                <a href="#sequence">Build Order</a>
                <a href="#decisions">Open Decisions</a>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="wrap">
            <a href="{{ route('roadmap') }}" class="back-link">← Back to Product Roadmap</a>
            <span class="eyebrow">Requirements Draft · Pre-implementation</span>
            <h1>Customer Panel — Full Requirements</h1>
            <p class="lead">
                Har cheez jo Customer role ko chahiye — sirf storefront nahi, poora signup se lekar delivery tak ka
                loop — ek jagah define kiya gaya hai. Isse review/approve karne ke baad hi Customer-side implementation
                shuru hogi (per <a href="{{ route('roadmap') }}#roles">roadmap</a>'s Customer role card aur Core Logic).
            </p>
            <div class="hero-meta">
                <span><strong>Status:</strong> §1 Identity/OTP and a scoped-down §5 Prescription flow are built (prescription-first MVP, see the roadmap's Phase 5) — everything else below is still just requirements</span>
                <span><strong>Depends on:</strong> Phase 2 Catalog (Products — built), Phase 3 Delivery Engine (partially built)</span>
                <span><strong>Last updated:</strong> {{ now()->format('d M Y') }}</span>
            </div>
        </div>
    </header>

    <!-- IDENTITY -->
    <section id="identity">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">1 · Access Model</div>
                <h2>Identity &amp; Access</h2>
                <p>Customer <code>users</code> table hi reuse karta hai (role=customer) — koi alag table nahi, [[Store]] ki tarah, kyunki extra fields halka hai (addresses ek separate table me).</p>
            </div>
            <div class="card-grid">
                <div class="spec-card">
                    <h4><span class="tag v1">Built</span> Self-Registration</h4>
                    <ul>
                        <li>Mobile number + name — minimum required fields to start</li>
                        <li>Email optional at signup (nullable on <code>users</code> now), can be added later from Account Settings (not built)</li>
                        <li>No password field — OTP is the only credential (matches roadmap's Core Logic §OTP)</li>
                        <li>Duplicate mobile number → route straight into login/OTP instead of a "already registered" dead end</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag v1">Built</span> OTP Login</h4>
                    <ul>
                        <li>Enter mobile → request OTP → enter OTP → session starts</li>
                        <li><code>APP_ENV=local</code>: static <strong>123456</strong>, no gateway call</li>
                        <li>Other environments: real random code, logged rather than sent — <strong>production SMS gateway is still the one open decision</strong> below, nothing delivers this to an actual phone yet</li>
                        <li>Resend cooldown (30s) + max attempts before a short lockout, mirroring the existing password-login <code>RateLimiter</code> pattern — built</li>
                        <li>OTP expires after 5 minutes — built</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag v1">v1</span> Session &amp; Device</h4>
                    <ul>
                        <li>"Remember this device" — skip OTP on next visit from the same browser/device for N days</li>
                        <li>Logout (single device) from Account Settings</li>
                        <li><code>isActive</code> gate applies here too — a deactivated Customer can't log in (same enforcement just added for Store)</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag later">Later</span> Account Deletion</h4>
                    <ul>
                        <li>"Request account deletion" from Account Settings — soft, goes to Admin for actioning rather than instant self-delete (order/prescription history has to be retained for compliance)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ADDRESS -->
    <section id="address">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">2 · Location</div>
                <h2>Address &amp; Location</h2>
                <p>Naya <code>addresses</code> table (1-to-many with <code>users</code>) — ek Customer ke multiple saved addresses ho sakte hain, alag se ek live GPS capture bhi hota hai checkout ke waqt.</p>
            </div>
            <div class="card-grid">
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Saved Addresses</h4>
                    <ul>
                        <li>Label (Home/Work/Other), full address text, lat/long via the same <a href="{{ route('roadmap') }}#logic">map-picker</a> pattern already built for Stores</li>
                        <li>One marked default; add/edit/delete from Account Settings and inline during checkout</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Live Location Capture</h4>
                    <ul>
                        <li>Browser/app geolocation as a quick "use my current location" alternative to a saved address, same UX already built for Store registration</li>
                        <li>OTP-verified mobile + this lat/long is what feeds the radius-matching logic (roadmap Core Logic §Radius-based Delivery)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- STOREFRONT -->
    <section id="storefront">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">3 · Browse</div>
                <h2>Storefront: Browse &amp; Search</h2>
                <p>Products table already built (Admin &gt; Products); Categories/Category Groups/Tags are still pending (roadmap Phase 2) and this screen depends on them for proper navigation.</p>
            </div>
            <div class="card-grid">
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Home</h4>
                    <ul>
                        <li>Banners (Admin-managed), featured/top categories, health article teasers</li>
                        <li>Persistent "deliver to" bar showing current address + Fast Delivery/Expected Date context</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Category &amp; Search</h4>
                    <ul>
                        <li>Category Group → Category → Product drill-down (blocked on those tables existing)</li>
                        <li>Search by drug name, composition/salt, or manufacturer, with autocomplete</li>
                        <li>Tag-based filters (e.g. "Diabetes", "Cold &amp; Flu")</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Product Detail</h4>
                    <ul>
                        <li>Name, composition, manufacturer, packaging, image gallery — all already on the <code>products</code> table</li>
                        <li>MRP vs price (discount % shown when price &lt; mrp)</li>
                        <li>Rx badge when <code>requires_prescription</code> is true — add-to-cart still allowed, but checkout will demand an uploaded prescription</li>
                        <li>Fast Delivery badge / Expected Delivery Date computed live from nearest matching Store (Core Logic §Radius-based Delivery)</li>
                        <li>Substitute products shown when the exact item isn't available nearby (Phase 2 — Substitute Products, not built yet)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CART & CHECKOUT -->
    <section id="cart-checkout">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">4 · Purchase</div>
                <h2>Cart &amp; Checkout</h2>
                <p>Ek order ek hi Store se fulfil hota hai (see Open Decisions §Single-store checkout) — cart isliye implicitly ek Store ke around scoped hota hai.</p>
            </div>
            <ol class="flow-steps">
                <li><strong>Add to cart</strong> — from product detail or listing; quantity stepper; cart persists across sessions (tied to the logged-in Customer, not just a browser session).</li>
                <li><strong>Cart review</strong> — line items, quantity edit/remove, subtotal, applicable coupon code entry.</li>
                <li><strong>Delivery address</strong> — pick a saved address or capture live location; Fast Delivery/Expected Date re-confirmed against the chosen Store for this address.</li>
                <li><strong>Prescription step</strong> — shown only if any cart item has <code>requires_prescription</code>; upload photo/PDF here, or attach a prescription already uploaded earlier this session (feeds Phase 5 flow).</li>
                <li><strong>Payment</strong> — COD at minimum for v1; online payment gateway is an Open Decision (vendor not chosen).</li>
                <li><strong>Place order</strong> — order created in <code>Pending Store Review</code> (if Rx involved) or <code>Placed</code> (if pure OTC) state; confirmation screen + order number.</li>
            </ol>
        </div>
    </section>

    <!-- PRESCRIPTION -->
    <section id="prescription">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">5 · Compliance</div>
                <h2>Prescription Flow — Customer Side</h2>
                <p>
                    <strong>Built as an MVP</strong>, scoped down from the spec below and from the store-side steps in
                    <a href="{{ route('roadmap') }}#logic">roadmap Core Logic §Rx Examine &amp; Fulfil</a> — this went in as the very
                    first Customer feature, prescription-first rather than after Storefront/Cart (build sequence below is now stale
                    on that point). See roadmap Phase 5 for the full built/pending breakdown.
                </p>
            </div>
            <div class="card-grid">
                <div class="spec-card">
                    <h4><span class="tag v1">Built (scoped)</span> Upload</h4>
                    <ul>
                        <li>Standalone upload only — it's the first thing a Customer does after logging in; there's no cart/checkout entry point yet since Storefront/Cart isn't built</li>
                        <li>Image or PDF, multiple files per prescription — built</li>
                        <li>Remark + delivery address (typed each time — no saved Address Book yet) + optional GPS location — built</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag v1">Built (scoped)</span> Status &amp; Decision</h4>
                    <ul>
                        <li>Status timeline: Pending → Reviewing → Contacted → Awaiting Confirmation → Confirmed/Dispatched, or Rejected — built, one status shorter than the original spec (no separate "Store Reviewing" vs "Awaiting Your Confirmation" split beyond this)</li>
                        <li>Store posts a priced estimate (medicine lines + total) directly on the Prescription; Customer Accepts/Rejects from their own panel, <em>or</em> the Store records the same outcome after a voice call — built</li>
                        <li><strong>Not built:</strong> in-app chat thread — the call + posted estimate stand in for this today</li>
                        <li><strong>Not built:</strong> push/SMS notification when the Store responds — a Customer has to check their own panel (or wait for the call)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- TRACKING -->
    <section id="tracking">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">6 · Post-Order</div>
                <h2>Order Tracking &amp; History</h2>
            </div>
            <div class="card-grid">
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Order List &amp; Detail</h4>
                    <ul>
                        <li>Filter by status (Active / Delivered / Cancelled)</li>
                        <li>Detail: items, Store, assigned Captain (once dispatched), live status timeline</li>
                        <li>Delivery proof: OTP/signature/photo, shown once marked Delivered</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag v1">v1</span> Reorder &amp; Cancel</h4>
                    <ul>
                        <li>"Reorder" re-adds the same items to a fresh cart (re-validates stock/price)</li>
                        <li>Cancel — only while still <code>Placed</code>/<code>Pending Store Review</code>, before a Captain is assigned (cancellation/refund policy is an Open Decision)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ENGAGEMENT -->
    <section id="engagement">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">7 · Retention</div>
                <h2>Engagement</h2>
            </div>
            <div class="card-grid">
                <div class="spec-card">
                    <h4><span class="tag v1">v1</span> Reviews &amp; Ratings</h4>
                    <ul>
                        <li>Post-delivery prompt, per product and/or per Store</li>
                        <li>Goes through Admin moderation queue (already a roadmap module)</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag v1">v1</span> Notifications</h4>
                    <ul>
                        <li>Order status changes, prescription updates, promo/offer blasts</li>
                        <li>Channel(s) TBD — see Open Decisions</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag later">Later</span> Health Articles &amp; Wishlist</h4>
                    <ul>
                        <li>Read-only browsing of Admin's Health Articles module</li>
                        <li>Wishlist/favorites — nice-to-have, not required for v1 checkout loop</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ACCOUNT -->
    <section id="account">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">8 · Settings</div>
                <h2>Account Settings</h2>
            </div>
            <div class="card-grid">
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Profile</h4>
                    <ul>
                        <li>Name, email, DOB, gender — all optional beyond what signup already collected</li>
                        <li>Change mobile number → re-verifies via OTP on the new number before it takes effect</li>
                    </ul>
                </div>
                <div class="spec-card">
                    <h4><span class="tag core">Core</span> Addresses &amp; Sessions</h4>
                    <ul>
                        <li>Manage saved addresses (see §Address above)</li>
                        <li>Logout; "forget this device" to require OTP again next time</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- NFR -->
    <section id="nfr">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">9 · Cross-Cutting</div>
                <h2>Non-Functional Requirements</h2>
            </div>
            <div class="table-scroll">
                <table class="spec-table">
                    <thead>
                        <tr><th>Area</th><th>Requirement</th></tr>
                    </thead>
                    <tbody>
                        <tr><td>Platform</td><td>Responsive web storefront first (works inside the existing Laravel app); native app/PWA is a later decision, not required for v1 — see Open Decisions.</td></tr>
                        <tr><td>Performance</td><td>Paginated/infinite-scroll product listings; lazy-loaded images (the sheet's image URLs are already externally hosted, no local storage cost for those).</td></tr>
                        <tr><td>Security</td><td>OTP rate limiting mirrors the existing <code>RateLimiter</code> pattern on password login; prescription files stay on a private disk with signed URLs (already noted in roadmap Tech Notes).</td></tr>
                        <tr><td>Session</td><td>Reasonable default session length for a shopping app (e.g. persistent login until explicit logout or "forget device"), balanced against the OTP-only, no-password login.</td></tr>
                        <tr><td>Accessibility</td><td>Standard form labeling/contrast — no special requirement beyond what the rest of the app already follows.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- DATA MODEL -->
    <section id="data-model">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">10 · Engineering Handoff</div>
                <h2>New Tables This Implies</h2>
                <p>Customer identity stays on the existing <code>users</code> table (role=customer) — everything below is new.</p>
            </div>
            <div class="table-scroll">
                <table class="spec-table">
                    <thead>
                        <tr><th>Table</th><th>Purpose</th><th>Key columns</th></tr>
                    </thead>
                    <tbody>
                        <tr><td><code>addresses</code></td><td>Saved delivery addresses</td><td><code>user_id</code>, label, address_line, latitude, longitude, is_default</td></tr>
                        <tr><td><code>carts</code> / <code>cart_items</code></td><td>Active cart per Customer</td><td><code>user_id</code>, <code>store_id</code>, product_id, quantity</td></tr>
                        <tr><td><code>orders</code> / <code>order_items</code></td><td>Placed orders (Phase 4)</td><td><code>user_id</code>, <code>store_id</code>, <code>captain_id</code>, status, address snapshot, totals</td></tr>
                        <tr><td><code>prescriptions</code> / <code>prescription_messages</code></td><td>Rx upload + Store↔Customer chat (Phase 5, already noted in roadmap Tech Notes)</td><td><code>user_id</code>, <code>store_id</code>, <code>order_id</code>, file path, status</td></tr>
                        <tr><td><code>coupons</code></td><td>Discount codes (Admin-managed, Phase 4 chip already on roadmap)</td><td>code, type, value, validity</td></tr>
                        <tr><td><code>reviews</code></td><td>Post-delivery ratings</td><td><code>user_id</code>, reviewable (product/store), rating, comment</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <!-- SEQUENCE -->
    <section id="sequence">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">11 · Proposed Order</div>
                <h2>Suggested Build Sequence</h2>
                <p>Ek dependency-respecting order — har step pichle step ke upar build hota hai, matching the roadmap's own phased approach.</p>
            </div>
            <ol class="seq-list">
                <li><span class="n">1</span> OTP self-registration + login <span class="stack">unlocks every other Customer screen</span></li>
                <li><span class="n">2</span> Address book (saved addresses + live location) <span class="stack">needed before any delivery-matching UI makes sense</span></li>
                <li><span class="n">3</span> Storefront browse/search over the existing Products table <span class="stack">Categories/Tags can follow once their tables exist</span></li>
                <li><span class="n">4</span> Cart + COD-only checkout <span class="stack">gets a real end-to-end order flowing before payment gateway is picked</span></li>
                <li><span class="n">5</span> Order tracking + history <span class="stack">closes the loop for a non-Rx order</span></li>
                <li><span class="n">6</span> Prescription upload + chat <span class="stack">layers Rx compliance on top of the working cart/checkout</span></li>
                <li><span class="n">7</span> Reviews, notifications, coupons <span class="stack">retention layer, once the core loop is solid</span></li>
            </ol>
        </div>
    </section>

    <!-- OPEN DECISIONS -->
    <section id="decisions">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">⚠ Blocking Before Build</div>
                <h2>Open Decisions Needed From You</h2>
                <p>Ye cheezein sirf aap decide kar sakte hain — inke bina implementation shuru hone par galat assumption pe build ho sakta hai.</p>
            </div>
            <div class="decision-grid">
                <div class="decision-card">
                    <h4>Single-store checkout?</h4>
                    <p>Ek order sirf ek Store se fulfil ho, ya ek cart me multiple Stores ke products mix ho sakte hain (split into multiple orders at checkout)?</p>
                    <div class="default">Assumed default: <strong>single-store per order</strong> (simpler, matches nearest-Store matching logic already defined).</div>
                    <div class="default" style="margin-top: 8px;">Sidestepped for now: the Prescription MVP routes to whichever Store claims it first (shared queue), not nearest-Store matching — see roadmap Phase 5. This decision is still open for when Cart/Checkout gets built.</div>
                </div>
                <div class="decision-card">
                    <h4>Payment gateway</h4>
                    <p>COD-only launch, ya online payment bhi chahiye (Razorpay/PayU/Stripe — koi vendor decide nahi hua abhi)?</p>
                    <div class="default">Assumed default: <strong>COD-only for v1</strong>, online payment as a fast-follow once a vendor is picked.</div>
                </div>
                <div class="decision-card">
                    <h4>Production SMS/OTP gateway</h4>
                    <p>MSG91, Twilio, ya koi doosra — abhi sirf local static OTP (123456) defined hai, production gateway account nahi banaya gaya.</p>
                    <div class="default">No default — blocks real (non-local) OTP entirely until chosen.</div>
                </div>
                <div class="decision-card">
                    <h4>Notification channels</h4>
                    <p>In-app only, ya SMS/push bhi order updates ke liye chahiye? Push needs a mobile app or web-push setup.</p>
                    <div class="default">Assumed default: <strong>in-app + SMS</strong> (reusing the same SMS gateway account as OTP once chosen), push deferred.</div>
                </div>
                <div class="decision-card">
                    <h4>Cancellation/refund policy</h4>
                    <p>Kab tak order cancel ho sakta hai, aur refund kaise process hota hai (especially once online payment exists)?</p>
                    <div class="default">Assumed default: <strong>cancel allowed until a Captain is assigned</strong>; refunds out of scope until a payment gateway is chosen.</div>
                </div>
                <div class="decision-card">
                    <h4>Native app vs web-only</h4>
                    <p>V1 sirf responsive web storefront (isi Laravel app ke andar), ya ek native/PWA app bhi parallel-track pe chahiye?</p>
                    <div class="default">Assumed default: <strong>responsive web first</strong>; native/PWA is a later, separate decision.</div>
                </div>
            </div>
            <div class="pill-row">
                <span class="pill">Reply with adjustments to any of the above — sequence/data-model sections update accordingly</span>
            </div>
        </div>
    </section>

    <footer>
        {{ config('app.name', 'MediServe') }} · Internal planning document · Generated {{ now()->format('d M Y') }}
    </footer>

</body>
</html>
