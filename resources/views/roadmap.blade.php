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
        .role-card.store .role-icon { background: var(--teal-soft); color: var(--teal); }
        .role-card.captain .role-icon { background: var(--amber-soft); color: var(--amber); }
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
        .logic-card.otp .logic-head { background: var(--blue-soft); color: #1d4ed8; }
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
                Ek roadmap jo MediServe ke 4 login roles (Admin, Store, Captain, Customer),
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
                <p>Har role ka apna dashboard aur permission scope hoga. Admin aur Store dono panel-based hai, Captain (delivery staff, apni Store ke andar bana hua) ek lightweight app/PWA, aur Customer ka apna storefront + app. Customer site par seedhe aakar <strong>self-signup</strong> karta hai aur <strong>OTP-based login</strong> use karta hai (no password) — Store aur Captain ke liye abhi koi public signup nahi hai, dono Admin panel se create hote hain.</p>
            </div>
            <div class="role-grid">

                <div class="role-card admin">
                    <div class="role-icon">🛡️</div>
                    <div>
                        <span class="role-tag">Platform Level</span>
                        <h3>Admin</h3>
                    </div>
                    <ul>
                        <li>Role &amp; permission based access control — custom Admin roles (Super Admin, Store Manager, Catalog Manager, Support Admin, Finance Admin…) each with their own permission set</li>
                        <li>Menu is permission-gated — a role sees a module only if it holds that module's permission; unassigned modules stay hidden automatically</li>
                        <li>Directly register Store &amp; Captain accounts (Admin panel forms) — neither role has public self-signup for now</li>
                        <li>Approve/reject Stores after KYC (license, GST, Aadhaar/PAN) — login stays inactive until approved</li>
                        <li>Full control over all users — Store, Captain, Customer (activate/deactivate, reset login/password)</li>
                        <li>Oversight of every Store's Captains — can view/override/deactivate any of them</li>
                        <li>Master catalog: Products, Categories, Category Groups, Tags, Substitute Products</li>
                        <li>Global stock &amp; inventory oversight across all Stores</li>
                        <li>Coupons &amp; Offers, Banners, Health Articles, Content, Careers</li>
                        <li>Prescription compliance audit</li>
                        <li>App Config &amp; Settings</li>
                    </ul>
                </div>

                <div class="role-card store">
                    <div class="role-icon">🏪</div>
                    <div>
                        <span class="role-tag">Pharmacy Level</span>
                        <h3>Store</h3>
                    </div>
                    <ul>
                        <li>Store profile: shop name, license/GST, KYC (Aadhaar/PAN), location (lat/long)</li>
                        <li>Delivery radius (km) + speed (kmph) — both optional; skipping both means relying on a future delivery-partner integration for all orders</li>
                        <li>Stock / inventory management (batch &amp; expiry tracking)</li>
                        <li>Register &amp; manage own Captains (delivery staff)</li>
                        <li>Receive, accept &amp; prepare Orders; assign to own Captains</li>
                        <li>Examine uploaded prescriptions, mark available medicines/substitutes, and chat with customer before proceeding</li>
                        <li>Store-level sales reports &amp; earnings</li>
                    </ul>
                </div>

                <div class="role-card captain">
                    <div class="role-icon">🛵</div>
                    <div>
                        <span class="role-tag">Field Level</span>
                        <h3>Captain</h3>
                    </div>
                    <ul>
                        <li>Created &amp; managed by their Store (not directly by Admin)</li>
                        <li>Online / offline availability toggle</li>
                        <li>Assigned order queue, scoped to their Store's orders, with navigation</li>
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
                        <li>Self-registers directly on the site/app; login is <strong>OTP-based</strong> (no password)</li>
                        <li>Saved addresses + live GPS location</li>
                        <li>Browse categories/products, search medicines &amp; substitutes</li>
                        <li>See <strong>Fast Delivery</strong> or <strong>Expected Delivery Date</strong> per location</li>
                        <li>OTP-verified mobile number + live lat/long for prescription orders &amp; delivery matching</li>
                        <li>Upload prescription copy &amp; chat with Store to confirm available medicines</li>
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

                <div class="logic-card otp">
                    <div class="logic-head">📱 OTP-Verified Location Capture</div>
                    <ol>
                        <li>Customer apna <strong>mobile number</strong> enter karta hai aur GPS se current <strong>lat/long</strong> location share karta hai.</li>
                        <li>Mobile number par OTP bheja jata hai — verify hone ke baad hi location, order ya prescription request ke saath lock hoti hai.</li>
                        <li><strong>Local/dev environment</strong> (<code>APP_ENV=local</code>) me static OTP <strong>123456</strong> accept hota hai — real SMS gateway call ki zaroorat nahi, QA/testing fast hoti hai.</li>
                        <li><strong>Production</strong> me real SMS gateway (e.g. MSG91 / Twilio) se OTP generate aur verify hota hai.</li>
                        <li>Verified mobile number hi Store ↔ Customer communication (prescription clarification, order updates) ke liye use hota hai.</li>
                    </ol>
                    <div class="badge-row">
                        <span class="pill fast">🔐 OTP Verified</span>
                        <span class="pill eta">🧪 Local OTP: 123456</span>
                    </div>
                </div>

                <div class="logic-card radius">
                    <div class="logic-head">📍 Radius-based Delivery Availability</div>
                    <ol>
                        <li><strong>Location capture</strong> — customer ki GPS location ya saved address geocode hoti hai (lat/long).</li>
                        <li><strong>Nearby match</strong> — system un Stores ko dhundta hai jinke paas ordered product ka stock available hai.</li>
                        <li><strong>Distance check</strong> — customer aur har candidate Store ke beech distance calculate hota hai (Haversine formula).</li>
                        <li>Har Store apna <strong>delivery radius (km)</strong> aur <strong>delivery speed (kmph)</strong> define karta hai — dono <strong>optional</strong> hain; ek Store dono skip kar sakta hai aur poori tarah delivery-partner (future) par depend kar sakta hai.</li>
                        <li>Agar customer <strong>Store ke radius ke andar</strong> hai → <strong>ETA = distance ÷ speed</strong> se calculate hota hai. Ye ETA ek configurable threshold se kam hai to <strong>Fast Delivery</strong> badge + ETA dikhta hai — <em>ye ek manual flag nahi hai</em>, purely radius+speed ka result hai.</li>
                        <li>Agar koi Store radius ke andar nahi milta (ya Store ne radius/speed define nahi kiya) → nearest available Store se <strong>Expected Delivery Date</strong> (standard fallback SLA) dikhaya jata hai.</li>
                        <li>Admin har Store/city ke liye default fallback SLA (e.g. 2–4 din) configure kar sakta hai.</li>
                        <li><strong>Future:</strong> radius ke bahar ke orders ek third-party <strong>delivery-partner API</strong> se bhi fulfil ho sakte hain — abhi built nahi hai, vendor decide hone ke baad add hoga.</li>
                    </ol>
                    <div class="badge-row">
                        <span class="pill fast">⚡ Fast Delivery — ETA under threshold</span>
                        <span class="pill eta">📅 Expected Delivery Date — fallback SLA</span>
                    </div>
                </div>

                <div class="logic-card rx">
                    <div class="logic-head">📄 Prescription (Rx) Examine &amp; Fulfil Flow</div>
                    <ol>
                        <li>Har Product par <strong>requires_prescription</strong> flag Admin/Store set karte hain (Rx vs OTC) — customer directly bhi prescription upload karke order start kar sakta hai.</li>
                        <li>Customer prescription copy upload karta hai (image/PDF), saath me <strong>OTP-verified mobile number</strong> aur <strong>lat/long</strong> location deta hai.</li>
                        <li>Order <strong>"Pending Store Review"</strong> state me jata hai aur Store ko notify hota hai.</li>
                        <li>Store prescription <strong>examine</strong> karta hai, apne stock ke against <strong>available medicines</strong> match karta hai (zaroorat pe substitute suggest kar sakta hai).</li>
                        <li>Store in-app <strong>chat</strong> se customer se communicate karta hai — quantity, substitute aur price confirm karta hai.</li>
                        <li>Customer confirm karta hai → order finalize hokar packing/dispatch me aage badhta hai (fast-delivery/radius logic ke hisaab se).</li>
                        <li>Prescription copy + chat log order ke saath audit ke liye <em>Prescriptions</em> module me store rehta hai.</li>
                    </ol>
                    <div class="badge-row">
                        <span class="pill fast">✅ Confirmed → Dispatch</span>
                        <span class="pill eta">💬 Store ↔ Customer Chat</span>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- MODULES -->
    <section id="modules">
        <div class="wrap">
            <div class="section-head">
                <div class="kicker">Admin / Store Panel</div>
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
                        <span class="chip">Stores</span>
                        <span class="chip">Captains</span>
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

                <div class="phase done">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 0 — Foundation</h3>
                            <span class="status done">Done</span>
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

                <div class="phase progress">
                    <div class="phase-card">
                        <div class="phase-top">
                            <h3>Phase 1 — Roles &amp; Access</h3>
                            <span class="status progress">In Progress</span>
                        </div>
                        <p class="desc">4 login types aur permission system.</p>
                        <ul>
                            <li>Admin, Store, Captain, Customer account types (single <code>users</code> table + <code>role</code> column — see Tech Notes)</li>
                            <li>Role &amp; Permission module (<code>spatie/laravel-permission</code>) — custom Admin roles, granular permissions, Super Admin bypass</li>
                            <li>Permission-driven sidebar menu (config-defined, DB-driven visibility)</li>
                            <li>Separate <code>stores</code> table (1-1 with <code>users</code>) + <code>store_id</code> on <code>users</code> linking a Captain to their parent Store</li>
                            <li>Admin-side Store registration (KYC: Aadhaar/PAN/license/GST) + approve/reject workflow</li>
                            <li>Admin-side Captain registration, linked to an approved Store</li>
                            <li><strong>Pending:</strong> Customer self-registration + OTP-based login — separate from the above, not built yet</li>
                            <li><strong>Pending:</strong> Mobile OTP verification (static <strong>123456</strong> when <code>APP_ENV=local</code>) — mechanism defined, not wired to a real signup flow yet</li>
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
                            <li>Per-Store stock management (batch/expiry)</li>
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
                            <li>Store geo-location (lat/long) — Google Maps picker live on the registration form, with a "Use my current location" (browser geolocation) button, and manual lat/long inputs as fallback if the map itself can't load</li>
                            <li>Delivery radius (km) + speed (kmph) per Store — both <strong>optional</strong>; a Store can skip both and rely entirely on a future delivery-partner integration</li>
                            <li>OTP-verified customer location capture (lat/long) &amp; geocoding</li>
                            <li>Nearest-store + radius matching (Haversine)</li>
                            <li><strong>Fast Delivery is derived, not a manual flag:</strong> ETA = distance ÷ Store's speed; badge shows only if ETA is under a configurable threshold</li>
                            <li>Fallback SLA / expected delivery date engine — for Stores without radius/speed, or when the customer is outside every Store's radius</li>
                            <li><strong>Future:</strong> third-party delivery-partner API for out-of-radius orders — not started, vendor not chosen yet</li>
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
                            <li>Captain assignment &amp; live status updates</li>
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
                            <li>Prescription upload + OTP-verified mobile &amp; location capture</li>
                            <li>Store review queue — mark available medicines / suggest substitutes</li>
                            <li>In-app chat: Store ↔ Customer communication before confirming order</li>
                            <li>Customer confirmation → order finalize → dispatch</li>
                            <li>Audit trail (prescription + chat log) per order</li>
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
                <strong>Roles &amp; Permissions:</strong> <code>users.role</code> enum (<code>admin</code>/<code>store</code>/<code>captain</code>/<code>customer</code>) account-type ke liye rehta hai; iske upar <code>spatie/laravel-permission</code> se Admin ke andar fine-grained roles (Super Admin, Store Manager, …) aur permissions banti hain. Ek <code>Gate::before()</code> hook Super Admin ko har check me bypass deta hai. Ek role Admin, Customer ya Captain accounts ko assign ho sakta hai — Store deliberately excluded hai (uske apne staff permissions ek alag concern honge).
                &nbsp;·&nbsp; <strong>Menu:</strong> Sidebar ek code-defined config (<code>config/adminmenu.php</code>) se render hota hai, har item ek permission slug se tagged — DB me sirf roles/permissions ka data hai, menu <em>structure</em> nahi. Naya module add karne par sirf config me entry + permission seed karna hota hai; koi menu-builder UI nahi.
                &nbsp;·&nbsp; <strong>Google Maps:</strong> <code>GOOGLE_MAPS_API_KEY</code> <code>.env</code> me set hai, <code>config('services.google_maps.key')</code> se expose hota hai. Store registration ka map picker isse graceful-degrade karta hai — key missing ho to plain lat/long inputs dikhte hain, aur key runtime pe reject ho (billing/API-not-enabled/referrer restriction) to <code>gm_authFailure</code> hook broken map hide karke wahi fallback message dikha deta hai. "Use my current location" button (browser geolocation) map ke saath ya uske bina bhi kaam karta hai.
                &nbsp;·&nbsp; <strong>Store data model:</strong> Store-specific fields (shop name, license/GST, lat/long, radius, speed, approval status) ek alag <code>stores</code> table me hain (1-1 with <code>users</code> via <code>user_id</code>) — <code>users</code> khud sirf shared identity/login fields rakhta hai, taki Admin/Captain/Customer rows par ye sab always-null columns na aaye.
                &nbsp;·&nbsp; <strong>Store ↔ Captain link:</strong> <code>users</code> table par nullable <code>store_id</code> (self-referencing) column hai — ek Captain isi column se apni parent Store se linked hota hai; Admin is column ke bina bhi sabko dekh/edit kar sakta hai.
                &nbsp;·&nbsp; <strong>Address:</strong> purana <code>address</code> integer column (locations tree ka reference) sirf ek-do cities me hi locality-level data hone ki wajah se general address ke liye kaam nahi karta — ek naya <code>address_line</code> text column real postal address store karta hai; <code>address</code> ab nullable hai, unused reh gaya hai.
                &nbsp;·&nbsp; <strong>Multi-guard auth:</strong> Store, Captain, Customer ke liye separate guards/tables ya ek <code>users</code> table + <code>role</code> column, use-case ke hisaab se decide karein.
                &nbsp;·&nbsp; <strong>Admin tables:</strong> Har admin listing screen (Roles, Stores, Captains, …) server-side <code>yajra/laravel-datatables-oracle</code> se render hoti hai, horizontal-scroll variant — plain Blade tables nahi.
                &nbsp;·&nbsp; <strong>Geo queries:</strong> MySQL <code>ST_Distance_Sphere</code> ya raw Haversine query se nearest-Store + radius match nikala ja sakta hai.
                &nbsp;·&nbsp; <strong>File storage:</strong> Prescription uploads ke liye <code>storage/app/prescriptions</code> (private disk) + signed URLs for review.
                &nbsp;·&nbsp; <strong>OTP:</strong> <code>APP_ENV=local</code> par static OTP <code>123456</code> return karein (koi SMS gateway call nahi); staging/production me real gateway (MSG91 / Twilio) se bhejein aur rate-limit lagayein.
                &nbsp;·&nbsp; <strong>Store ↔ Customer chat:</strong> ek lightweight <code>order_messages</code> / <code>prescription_messages</code> table se text (aur optional image) messages, order/prescription se linked.
            </div>
        </div>
    </section>

    <footer>
        {{ config('app.name', 'MediServe') }} · Internal planning document · Generated {{ now()->format('d M Y') }}
    </footer>

</body>
</html>
