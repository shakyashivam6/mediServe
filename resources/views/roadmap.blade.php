<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'MediServe') }} — Product Roadmap</title>
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

        .wrap {
            max-width: 1120px;
            margin: 0 auto;
            padding: 0 24px;
        }

        /* ---------- Top nav ---------- */
        .topnav {
            position: sticky;
            top: 0;
            z-index: 20;
            background: rgba(246, 248, 251, 0.92);
            backdrop-filter: blur(6px);
            border-bottom: 1px solid var(--line);
        }
        .topnav .wrap {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-top: 14px;
            padding-bottom: 14px;
            gap: 16px;
            flex-wrap: wrap;
        }
        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 15px;
            letter-spacing: 0.2px;
        }
        .brand .dot {
            width: 10px; height: 10px; border-radius: 50%;
            background: linear-gradient(135deg, var(--teal), var(--blue));
        }
        .navlinks {
            display: flex;
            gap: 4px;
            flex-wrap: wrap;
            font-size: 13px;
        }
        .navlinks a {
            text-decoration: none;
            color: var(--ink-soft);
            padding: 7px 12px;
            border-radius: 999px;
            font-weight: 600;
        }
        .navlinks a:hover { background: var(--panel); color: var(--ink); }

        /* ---------- Hero ---------- */
        .hero {
            padding: 56px 0 40px;
        }
        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--teal);
            background: var(--teal-soft);
            padding: 6px 12px;
            border-radius: 999px;
        }
        h1 {
            font-size: clamp(28px, 4vw, 40px);
            margin: 16px 0 10px;
            letter-spacing: -0.02em;
        }
        .hero p.lead {
            font-size: 16.5px;
            color: var(--ink-soft);
            max-width: 680px;
            margin: 0 0 22px;
        }
        .hero-meta {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
            font-size: 13px;
            color: var(--ink-faint);
        }
        .hero-meta strong { color: var(--ink); }

        /* ---------- Section shell ---------- */
        section { padding: 46px 0; }
        section + section { border-top: 1px solid var(--line); }
        .section-head { margin-bottom: 26px; }
        .section-head .kicker {
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: var(--ink-faint);
            margin-bottom: 6px;
        }
        .section-head h2 {
            font-size: 24px;
            margin: 0 0 6px;
            letter-spacing: -0.01em;
        }
        .section-head p {
            color: var(--ink-soft);
            margin: 0;
            max-width: 720px;
            font-size: 14.5px;
        }

        /* ---------- Role cards ---------- */
        .role-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 16px;
        }
        .role-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .role-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 19px;
        }
        .role-card h3 { margin: 0; font-size: 16.5px; }
        .role-card .role-tag {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--ink-faint);
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }
        .role-card ul {
            margin: 4px 0 0;
            padding-left: 18px;
            font-size: 13.3px;
            color: var(--ink-soft);
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .role-card.admin .role-icon { background: var(--indigo-soft); color: var(--indigo); }
        .role-card.owner .role-icon { background: var(--teal-soft); color: var(--teal); }
        .role-card.delivery .role-icon { background: var(--amber-soft); color: var(--amber); }
        .role-card.customer .role-icon { background: var(--blue-soft); color: var(--blue); }

        /* ---------- Logic callouts ---------- */
        .logic-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 18px;
        }
        .logic-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            overflow: hidden;
        }
        .logic-card .logic-head {
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 15px;
            border-bottom: 1px solid var(--line);
        }
        .logic-card.radius .logic-head { background: var(--teal-soft); color: #0f766e; }
        .logic-card.rx .logic-head { background: var(--amber-soft); color: #92400e; }
        .logic-card ol {
            margin: 0;
            padding: 18px 20px 20px 36px;
            font-size: 13.5px;
            color: var(--ink-soft);
            display: flex;
            flex-direction: column;
            gap: 9px;
        }
        .logic-card ol li strong { color: var(--ink); }
        .badge-row {
            display: flex; gap: 8px; flex-wrap: wrap;
            padding: 0 20px 18px;
        }
        .pill {
            font-size: 12px;
            font-weight: 600;
            padding: 5px 10px;
            border-radius: 999px;
        }
        .pill.fast { background: var(--green-soft); color: #15803d; }
        .pill.eta { background: #f1f5f9; color: var(--ink-soft); }

        /* ---------- Module chips ---------- */
        .module-groups {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 16px;
        }
        .module-group {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 18px 20px;
        }
        .module-group h4 {
            margin: 0 0 10px;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--ink-faint);
        }
        .chip-list { display: flex; flex-wrap: wrap; gap: 7px; }
        .chip {
            font-size: 12.5px;
            font-weight: 600;
            background: #f1f5f9;
            color: var(--ink);
            padding: 6px 11px;
            border-radius: 8px;
            border: 1px solid var(--line);
        }

        /* ---------- Timeline ---------- */
        .timeline {
            position: relative;
            margin-top: 6px;
        }
        .timeline::before {
            content: "";
            position: absolute;
            left: 19px;
            top: 6px;
            bottom: 6px;
            width: 2px;
            background: var(--line);
        }
        .phase {
            position: relative;
            padding: 0 0 30px 54px;
        }
        .phase:last-child { padding-bottom: 4px; }
        .phase::before {
            content: "";
            position: absolute;
            left: 11px;
            top: 4px;
            width: 18px; height: 18px;
            border-radius: 50%;
            background: var(--panel);
            border: 3px solid var(--ink-faint);
        }
        .phase.done::before { border-color: var(--green); background: var(--green); }
        .phase.progress::before { border-color: var(--amber); background: var(--panel); }
        .phase-card {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 18px 20px;
        }
        .phase-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }
        .phase-top h3 { margin: 0; font-size: 16px; }
        .status {
            font-size: 11.5px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
            text-transform: uppercase;
            letter-spacing: 0.03em;
        }
        .status.done { background: var(--green-soft); color: #15803d; }
        .status.progress { background: var(--amber-soft); color: #92400e; }
        .status.planned { background: #f1f5f9; color: var(--ink-soft); }
        .phase-card p.desc {
            margin: 0 0 10px;
            font-size: 13.5px;
            color: var(--ink-soft);
        }
        .phase-card ul {
            margin: 0;
            padding-left: 18px;
            font-size: 13.3px;
            color: var(--ink-soft);
            columns: 2;
            column-gap: 24px;
        }
        .phase-card ul li { break-inside: avoid; margin-bottom: 5px; }
        @media (max-width: 640px) {
            .phase-card ul { columns: 1; }
        }

        /* ---------- Stack note ---------- */
        .stack-note {
            background: var(--panel);
            border: 1px solid var(--line);
            border-radius: var(--radius);
            padding: 20px 22px;
            font-size: 13.5px;
            color: var(--ink-soft);
        }
        .stack-note strong { color: var(--ink); }
        .stack-note code {
            background: #f1f5f9;
            padding: 1px 6px;
            border-radius: 5px;
            font-size: 12.5px;
        }

        footer {
            padding: 30px 0 50px;
            font-size: 12.5px;
            color: var(--ink-faint);
            text-align: center;
        }

        @media print {
            .topnav { position: static; }
        }
    </style>
</head>
<body>

    <nav class="topnav">
        <div class="wrap">
            <div class="brand"><span class="dot"></span> {{ config('app.name', 'MediServe') }} Roadmap</div>
            <div class="navlinks">
                <a href="#roles">Roles</a>
                <a href="#logic">Core Logic</a>
                <a href="#modules">Modules</a>
                <a href="#timeline">Timeline</a>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="wrap">
            <span class="eyebrow">Internal · Draft v1</span>
            <h1>Pharmacy Delivery Platform — Product Roadmap</h1>
            <p class="lead">
                Ek roadmap jo MediServe ke 4 login roles (Admin, Owner, Delivery Boy, Customer),
                radius-based delivery logic, prescription (Rx) verification flow, aur admin panel
                ke saare modules ko ek jagah define karta hai.
            </p>
            <div class="hero-meta">
                <span><strong>Owner:</strong> Product / Engineering</span>
                <span><strong>Status:</strong> Planning → Phase 0 in progress</span>
                <span><strong>Last updated:</strong> {{ now()->format('d M Y') }}</span>
            </div>
        </div>
    </header>

    <!-- ROLES -->
    <section id="roles">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">Access Model</div>
                <h2>4 Types of Logins</h2>
                <p>Har role ka apna dashboard aur permission scope hoga. Admin aur Owner dono panel-based hai, Delivery Boy ek lightweight app/PWA, aur Customer ka apna storefront + app.</p>
            </div>
            <div class="role-grid">

                <div class="role-card admin">
                    <div class="role-icon">🛡️</div>
                    <div>
                        <span class="role-tag">Platform Level</span>
                        <h3>Admin</h3>
                    </div>
                    <ul>
                        <li>Role &amp; permission based access control (custom roles for sub-admins)</li>
                        <li>Global stock &amp; inventory oversight across all Owners</li>
                        <li>Approve / reject Owner (pharmacy) onboarding &amp; KYC</li>
                        <li>Master catalog: Products, Categories, Category Groups, Tags, Substitute Products</li>
                        <li>Coupons &amp; Offers, Banners, Health Articles, Content, Careers</li>
                        <li>Prescription compliance audit</li>
                        <li>Admin Users, App Config, Settings</li>
                    </ul>
                </div>

                <div class="role-card owner">
                    <div class="role-icon">🏪</div>
                    <div>
                        <span class="role-tag">Pharmacy Level</span>
                        <h3>Owner</h3>
                    </div>
                    <ul>
                        <li>Store profile: location (lat/long) + delivery service radius</li>
                        <li>Stock / inventory management (batch &amp; expiry tracking)</li>
                        <li>Receive, accept &amp; prepare Orders</li>
                        <li>Assign orders to Delivery Boys</li>
                        <li>Verify prescription copy before dispatching Rx orders</li>
                        <li>Store-level sales reports &amp; earnings</li>
                    </ul>
                </div>

                <div class="role-card delivery">
                    <div class="role-icon">🛵</div>
                    <div>
                        <span class="role-tag">Field Level</span>
                        <h3>Delivery Boy</h3>
                    </div>
                    <ul>
                        <li>Online / offline availability toggle</li>
                        <li>Assigned order queue with navigation</li>
                        <li>Status updates: Picked up → Out for delivery → Delivered</li>
                        <li>Proof of delivery (OTP / signature / photo)</li>
                        <li>COD collection &amp; reconciliation</li>
                        <li>Earnings / incentive summary</li>
                    </ul>
                </div>

                <div class="role-card customer">
                    <div class="role-icon">🧑‍🤝‍🧑</div>
                    <div>
                        <span class="role-tag">Consumer Level</span>
                        <h3>Customer</h3>
                    </div>
                    <ul>
                        <li>Saved addresses + live GPS location</li>
                        <li>Browse categories/products, search medicines &amp; substitutes</li>
                        <li>See <strong>Fast Delivery</strong> or <strong>Expected Delivery Date</strong> per location</li>
                        <li>Mandatory prescription upload for Rx medicines</li>
                        <li>Cart, coupons, checkout, live order tracking</li>
                        <li>Reviews, Health Articles, Notifications</li>
                    </ul>
                </div>

            </div>
        </div>
    </section>

    <!-- CORE LOGIC -->
    <section id="logic">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">Business Rules</div>
                <h2>Core Delivery &amp; Compliance Logic</h2>
                <p>Ye do rules pure customer experience ko drive karte hain — kahan se order fulfil hoga, aur kaun se order pe prescription mandatory hai.</p>
            </div>
            <div class="logic-grid">

                <div class="logic-card radius">
                    <div class="logic-head">📍 Radius-based Delivery Availability</div>
                    <ol>
                        <li><strong>Location capture</strong> — customer ki GPS location ya saved address geocode hoti hai (lat/long).</li>
                        <li><strong>Nearby match</strong> — system un Owners ko dhundta hai jinke paas ordered product ka stock available hai.</li>
                        <li><strong>Distance check</strong> — customer aur har candidate Owner ke beech distance calculate hota hai (Haversine formula).</li>
                        <li>Agar distance <strong>Owner ke defined radius ke andar</strong> hai → <strong>Fast Delivery</strong> label + ETA dikhta hai.</li>
                        <li>Agar koi Owner radius ke andar nahi milta → nearest available Owner se <strong>Expected Delivery Date</strong> (standard SLA) dikhaya jata hai.</li>
                        <li>Admin har Owner/city ke liye default fallback SLA (e.g. 2–4 din) configure kar sakta hai.</li>
                    </ol>
                    <div class="badge-row">
                        <span class="pill fast">⚡ Fast Delivery — within radius</span>
                        <span class="pill eta">📅 Expected Delivery Date — outside radius</span>
                    </div>
                </div>

                <div class="logic-card rx">
                    <div class="logic-head">📄 Prescription (Rx) Verification Flow</div>
                    <ol>
                        <li>Har Product par <strong>requires_prescription</strong> flag Admin/Owner set karte hain (Rx vs OTC).</li>
                        <li>Customer jab Rx medicine cart me add karta hai → checkout se pehle prescription upload (image/PDF) mandatory hota hai.</li>
                        <li>Order <strong>"Pending Prescription Verification"</strong> state me chala jata hai.</li>
                        <li>Owner / Admin pharmacist prescription review karke <strong>Approve</strong> ya <strong>Reject</strong> karta hai.</li>
                        <li>Approved → packing/dispatch aage badhta hai. Rejected → customer ko notify karke re-upload ya item remove karne ka option.</li>
                        <li>Prescription copy order ke saath audit ke liye <em>Prescriptions</em> module me store rehti hai.</li>
                    </ol>
                    <div class="badge-row">
                        <span class="pill fast">✅ Verified → Dispatch</span>
                        <span class="pill eta">⛔ Rejected → Re-upload</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- MODULES -->
    <section id="modules">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">Admin / Owner Panel</div>
                <h2>Feature Modules</h2>
                <p>Shared sidebar menu se derive kiye gaye modules, logical groups me organize kiye gaye.</p>
            </div>
            <div class="module-groups">

                <div class="module-group">
                    <h4>Catalog &amp; Inventory</h4>
                    <div class="chip-list">
                        <span class="chip">Products</span>
                        <span class="chip">Substitute Products</span>
                        <span class="chip">Categories</span>
                        <span class="chip">Category Groups</span>
                        <span class="chip">Tags</span>
                        <span class="chip">Stock Management</span>
                    </div>
                </div>

                <div class="module-group">
                    <h4>Sales &amp; Orders</h4>
                    <div class="chip-list">
                        <span class="chip">Orders</span>
                        <span class="chip">Prescriptions</span>
                        <span class="chip">Coupons &amp; Offers</span>
                        <span class="chip">Delivery Assignment</span>
                    </div>
                </div>

                <div class="module-group">
                    <h4>People</h4>
                    <div class="chip-list">
                        <span class="chip">Customers</span>
                        <span class="chip">Admin Users</span>
                        <span class="chip">Owners</span>
                        <span class="chip">Delivery Boys</span>
                    </div>
                </div>

                <div class="module-group">
                    <h4>Engagement &amp; Content</h4>
                    <div class="chip-list">
                        <span class="chip">Reviews</span>
                        <span class="chip">Banners</span>
                        <span class="chip">Health Articles</span>
                        <span class="chip">Notifications</span>
                        <span class="chip">Content</span>
                        <span class="chip">Careers (Web)</span>
                    </div>
                </div>

                <div class="module-group">
                    <h4>Platform</h4>
                    <div class="chip-list">
                        <span class="chip">Dashboard</span>
                        <span class="chip">Settings</span>
                        <span class="chip">App Config</span>
                        <span class="chip">Roles &amp; Permissions</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- TIMELINE -->
    <section id="timeline">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">Execution Plan</div>
                <h2>Phased Roadmap</h2>
                <p>Foundation se launch tak — har phase pichle phase ke upar build karta hai.</p>
            </div>

            <div class="timeline">

                <div class="phase progress">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 0 — Foundation</h3>
                            <span class="status progress">In Progress</span>
                        </div>
                        <p class="desc">Base auth scaffolding aur seed data setup.</p>
                        <ul>
                            <li>Laravel Breeze auth (login/register)</li>
                            <li>User + Location seeders</li>
                            <li>Base DB schema planning</li>
                            <li>Project structure &amp; conventions</li>
                        </ul>
                    </div>
                </div>

                <div class="phase">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 1 — Roles &amp; Access</h3>
                            <span class="status planned">Planned</span>
                        </div>
                        <p class="desc">4 login types aur permission system.</p>
                        <ul>
                            <li>Admin, Owner, Delivery Boy, Customer auth guards</li>
                            <li>Role &amp; permission management (spatie/permission style)</li>
                            <li>Owner onboarding + KYC approval workflow</li>
                            <li>Admin Users module</li>
                        </ul>
                    </div>
                </div>

                <div class="phase">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 2 — Catalog &amp; Inventory</h3>
                            <span class="status planned">Planned</span>
                        </div>
                        <p class="desc">Product data model jo pura storefront drive karega.</p>
                        <ul>
                            <li>Products, Substitute Products</li>
                            <li>Categories, Category Groups, Tags</li>
                            <li>Per-Owner stock management (batch/expiry)</li>
                            <li>Rx flag (requires_prescription) per product</li>
                        </ul>
                    </div>
                </div>

                <div class="phase">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 3 — Location &amp; Delivery Engine</h3>
                            <span class="status planned">Planned</span>
                        </div>
                        <p class="desc">Fast Delivery vs Expected Delivery Date logic.</p>
                        <ul>
                            <li>Owner geo-location + radius setup</li>
                            <li>Customer location capture &amp; geocoding</li>
                            <li>Nearest-store + radius matching (Haversine)</li>
                            <li>Fallback SLA / expected delivery date engine</li>
                        </ul>
                    </div>
                </div>

                <div class="phase">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 4 — Orders, Cart &amp; Checkout</h3>
                            <span class="status planned">Planned</span>
                        </div>
                        <p class="desc">End-to-end purchase aur fulfilment flow.</p>
                        <ul>
                            <li>Cart, Coupons &amp; Offers</li>
                            <li>Order lifecycle (placed → accepted → dispatched → delivered)</li>
                            <li>Delivery Boy assignment &amp; live status updates</li>
                            <li>Order tracking for customer</li>
                        </ul>
                    </div>
                </div>

                <div class="phase">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 5 — Prescription Workflow</h3>
                            <span class="status planned">Planned</span>
                        </div>
                        <p class="desc">Rx compliance end-to-end.</p>
                        <ul>
                            <li>Prescription upload at checkout</li>
                            <li>Owner/Admin verification queue</li>
                            <li>Approve / Reject + re-upload flow</li>
                            <li>Audit trail per order</li>
                        </ul>
                    </div>
                </div>

                <div class="phase">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 6 — Engagement &amp; Content</h3>
                            <span class="status planned">Planned</span>
                        </div>
                        <p class="desc">Retention aur marketing surfaces.</p>
                        <ul>
                            <li>Reviews &amp; ratings moderation</li>
                            <li>Banners, Health Articles, Content pages</li>
                            <li>Notifications (order + marketing)</li>
                            <li>Careers (Web)</li>
                        </ul>
                    </div>
                </div>

                <div class="phase">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 7 — Platform &amp; Launch Hardening</h3>
                            <span class="status planned">Planned</span>
                        </div>
                        <p class="desc">Ops, config aur go-live readiness.</p>
                        <ul>
                            <li>Dashboard analytics/reports</li>
                            <li>Settings &amp; App Config</li>
                            <li>Permission fine-tuning</li>
                            <li>Security review, testing, deployment</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section>
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">Suggested Approach</div>
                <h2>Tech Notes</h2>
            </div>
            <div class="stack-note">
                <strong>RBAC:</strong> <code>spatie/laravel-permission</code> se Admin ke andar custom roles/permissions banaye ja sakte hain.
                &nbsp;·&nbsp; <strong>Multi-guard auth:</strong> Owner, Delivery Boy, Customer ke liye separate guards/tables ya ek <code>users</code> table + <code>role</code> column, use-case ke hisaab se decide karein.
                &nbsp;·&nbsp; <strong>Geo queries:</strong> MySQL <code>ST_Distance_Sphere</code> ya raw Haversine query se nearest-Owner + radius match nikala ja sakta hai.
                &nbsp;·&nbsp; <strong>File storage:</strong> Prescription uploads ke liye <code>storage/app/prescriptions</code> (private disk) + signed URLs for review.
            </div>
        </div>
    </section>

    <footer>
        {{ config('app.name', 'MediServe') }} · Internal planning document · Generated {{ now()->format('d M Y') }}
    </footer>

</body>
</html>
