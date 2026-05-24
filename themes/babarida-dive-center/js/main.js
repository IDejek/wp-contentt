/**
 * Babarida Dive Center — Main JavaScript
 * Author: Iqbal Tombinawa <tombinawaiqbal@gmail.com>
 */
(function() {
    'use strict';

    const BBR = window.bbrData || {};
    const $ = (sel, ctx = document) => ctx.querySelector(sel);
    const $$ = (sel, ctx = document) => [...ctx.querySelectorAll(sel)];

    /* ============================================
       PRELOADER
       ============================================ */
    function initPreloader() {
        const preloader = $('.bbr-preloader');
        if (!preloader) return;
        window.addEventListener('load', () => {
            setTimeout(() => {
                preloader.classList.add('loaded');
                document.body.style.overflow = '';
            }, 1200);
        });
        document.body.style.overflow = 'hidden';
    }

    /* ============================================
       WORLD CLOCKS
       ============================================ */
    function initClocks() {
        const zones = [
            { label: 'Manado', tz: 'Asia/Makassar' },
            { label: 'Jakarta', tz: 'Asia/Jakarta' },
            { label: 'Singapore', tz: 'Asia/Singapore' },
            { label: 'Dubai', tz: 'Asia/Dubai' },
            { label: 'London', tz: 'Europe/London' },
            { label: 'New York', tz: 'America/New_York' },
            { label: 'Tokyo', tz: 'Asia/Tokyo' },
            { label: 'Seoul', tz: 'Asia/Seoul' },
        ];

        const mobileZones = [
            { label: 'MDO', tz: 'Asia/Makassar' },
            { label: 'SGN', tz: 'Asia/Singapore' },
            { label: 'LON', tz: 'Europe/London' },
            { label: 'SEL', tz: 'Asia/Seoul' },
        ];

        function renderClocks(container, zoneList) {
            if (!container) return;
            container.innerHTML = zoneList.map(z => {
                const now = new Date().toLocaleTimeString('en-GB', { timeZone: z.tz, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
                return '<div class="bbr-clock-item"><span class="bbr-clock-label">' + z.label + '</span><span class="bbr-clock-time" data-tz="' + z.tz + '">' + now + '</span></div>';
            }).join('');
        }

        function updateClocks() {
            $$('[data-tz]').forEach(el => {
                const tz = el.dataset.tz;
                el.textContent = new Date().toLocaleTimeString('en-GB', { timeZone: tz, hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
            });
        }

        const desktopClocks = $('.bbr-topbar-center');
        const mobileClocks = $('.bbr-mobile-clock');
        renderClocks(desktopClocks, zones);
        renderClocks(mobileClocks, mobileZones);
        setInterval(updateClocks, 1000);
    }

    /* ============================================
       STICKY HEADER
       ============================================ */
    function initHeader() {
        const header = $('.bbr-header');
        if (!header) return;
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const st = window.scrollY;
            if (st > 60) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            lastScroll = st;
        }, { passive: true });
    }

    /* ============================================
       MOBILE MENU
       ============================================ */
    function initMobileMenu() {
        const toggle = $('.bbr-mobile-toggle');
        const nav = $('.bbr-mobile-nav');
        if (!toggle || !nav) return;

        toggle.addEventListener('click', () => {
            toggle.classList.toggle('active');
            nav.classList.toggle('active');
            document.body.style.overflow = nav.classList.contains('active') ? 'hidden' : '';
        });

        // Submenu toggles
        $$('.bbr-mobile-nav-link[data-submenu]').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const target = document.getElementById(link.dataset.submenu);
                if (target) {
                    target.classList.toggle('active');
                    link.classList.toggle('open');
                }
            });
        });
    }

    /* ============================================
       FLOATING BUTTONS
       ============================================ */
    function initFloatingBtns() {
        const topBtn = $('.bbr-float-btn.top');
        if (!topBtn) return;
        window.addEventListener('scroll', () => {
            if (window.scrollY > 600) {
                topBtn.classList.add('visible');
            } else {
                topBtn.classList.remove('visible');
            }
        }, { passive: true });

        topBtn.addEventListener('click', () => {
            window.scrollTo({ top: 0, behavior: 'smooth' });
        });
    }

    /* ============================================
       SCROLL REVEAL
       ============================================ */
    function initReveal() {
        const els = $$('.bbr-reveal');
        if (!els.length) return;
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        els.forEach(el => observer.observe(el));
    }

    /* ============================================
       TESTIMONIAL SLIDER
       ============================================ */
    function initTestiSlider() {
        const track = $('.bbr-testi-track');
        const dots = $$('.bbr-testi-dot');
        if (!track || !dots.length) return;
        let current = 0;
        const total = $$('.bbr-testi-slide').length;

        function goTo(idx) {
            current = ((idx % total) + total) % total;
            track.style.transform = 'translateX(-' + (current * 100) + '%)';
            dots.forEach((d, i) => d.classList.toggle('active', i === current));
        }

        dots.forEach((dot, i) => dot.addEventListener('click', () => goTo(i)));

        // Auto-play
        let autoPlay = setInterval(() => goTo(current + 1), 5000);
        track.addEventListener('mouseenter', () => clearInterval(autoPlay));
        track.addEventListener('mouseleave', () => { autoPlay = setInterval(() => goTo(current + 1), 5000); });

        // Touch swipe
        let startX = 0;
        track.addEventListener('touchstart', (e) => { startX = e.touches[0].clientX; }, { passive: true });
        track.addEventListener('touchend', (e) => {
            const diff = startX - e.changedTouches[0].clientX;
            if (Math.abs(diff) > 50) goTo(diff > 0 ? current + 1 : current - 1);
        });
    }

    /* ============================================
       FAQ ACCORDION
       ============================================ */
    function initFAQ() {
        $$('.bbr-faq-q').forEach(q => {
            q.addEventListener('click', () => {
                const item = q.closest('.bbr-faq-item');
                const wasActive = item.classList.contains('active');
                $$('.bbr-faq-item.active').forEach(i => i.classList.remove('active'));
                if (!wasActive) item.classList.add('active');
            });
        });
    }

    /* ============================================
       AJAX SEARCH
       ============================================ */
    function initSearch() {
        const form = $('#bbr-search-form');
        if (!form) return;
        const resultsContainer = $('#bbr-search-results');

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const fd = new FormData(form);
            resultsContainer.innerHTML = '<p style="text-align:center;padding:2rem;color:#6b7280">' + (BBR.i18n?.searching || 'Searching...') + '</p>';

            fetch(BBR.ajaxUrl, {
                method: 'POST',
                body: new URLSearchParams({ action: 'bbr_search', nonce: BBR.nonce, ...Object.fromEntries(fd) })
            })
            .then(r => r.json())
            .then(res => {
                resultsContainer.innerHTML = res.data || '<p>No results.</p>';
            })
            .catch(() => {
                resultsContainer.innerHTML = '<p style="color:#EF4444;text-align:center;padding:2rem">Error occurred.</p>';
            });
        });
    }

    /* ============================================
       BOOKING FORM
       ============================================ */
    function initBookingForm() {
        $$('.bbr-booking-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const btn = $('button[type="submit"]', form);
                const origText = btn.textContent;
                btn.textContent = BBR.i18n?.sending || 'Sending...';
                btn.disabled = true;

                const formData = {};
                $$('.bbr-form-input, .bbr-form-select, .bbr-form-textarea', form).forEach(input => {
                    if (input.name) formData[input.name] = input.value;
                });

                fetch(BBR.ajaxUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'bbr_booking', nonce: BBR.nonce, form: JSON.stringify(formData) })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.success) {
                        btn.textContent = BBR.i18n?.sent || 'Submitted!';
                        btn.style.background = '#10B981';
                        btn.style.color = '#fff';
                        form.reset();
                        setTimeout(() => { btn.textContent = origText; btn.style.background = ''; btn.style.color = ''; btn.disabled = false; }, 4000);
                    } else {
                        alert(res.data?.message || 'Error');
                        btn.textContent = origText;
                        btn.disabled = false;
                    }
                })
                .catch(() => {
                    alert(BBR.i18n?.error || 'Error');
                    btn.textContent = origText;
                    btn.disabled = false;
                });
            });
        });
    }

    /* ============================================
       CONTACT FORM
       ============================================ */
    function initContactForm() {
        const form = $('#bbr-contact-form');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            e.preventDefault();
            const btn = $('button[type="submit"]', form);
            btn.textContent = BBR.i18n?.sending || 'Sending...';
            btn.disabled = true;

            fetch(BBR.ajaxUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    action: 'bbr_contact',
                    nonce: BBR.nonce,
                    name: $('#contact-name')?.value || '',
                    email: $('#contact-email')?.value || '',
                    subject: $('#contact-subject')?.value || '',
                    message: $('#contact-message')?.value || '',
                })
            })
            .then(r => r.json())
            .then(res => {
                if (res.success) {
                    btn.textContent = '✓ Sent!';
                    btn.style.background = '#10B981';
                    btn.style.color = '#fff';
                    form.reset();
                    setTimeout(() => { btn.textContent = 'Send Message'; btn.style.background = ''; btn.style.color = ''; btn.disabled = false; }, 4000);
                } else {
                    alert(res.data?.message || 'Error');
                    btn.textContent = 'Send Message';
                    btn.disabled = false;
                }
            })
            .catch(() => { alert('Error'); btn.textContent = 'Send Message'; btn.disabled = false; });
        });
    }

    /* ============================================
       CURRENCY SWITCHER
       ============================================ */
    function initCurrency() {
        $$('.bbr-currency-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                fetch(BBR.ajaxUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'bbr_currency', nonce: BBR.nonce, currency: btn.dataset.currency })
                })
                .then(() => window.location.reload());
            });
        });
    }

    /* ============================================
       NEWSLETTER
       ============================================ */
    function initNewsletter() {
        $$('.bbr-newsletter-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const email = $('input[type="email"]', form)?.value;
                if (!email) return;
                fetch(BBR.ajaxUrl, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: new URLSearchParams({ action: 'bbr_newsletter', nonce: BBR.nonce, email })
                })
                .then(r => r.json())
                .then(res => {
                    const msg = $('form + .bbr-newsletter-msg', form.parentElement) || form.nextElementSibling;
                    if (res.success) {
                        if (msg) { msg.textContent = '✓ Subscribed!'; msg.style.color = '#059669'; }
                        form.reset();
                    } else {
                        if (msg) { msg.textContent = res.data?.message || 'Error'; msg.style.color = '#DC2626'; }
                    }
                });
            });
        });
    }

    /* ============================================
       AVAILABILITY CHECK
       ============================================ */
    window.bbrCheckAvailability = function(tripId, date, guests, callback) {
        fetch(BBR.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'bbr_availability', nonce: BBR.nonce, trip_id: tripId, date, guests })
        })
        .then(r => r.json())
        .then(res => { if (callback) callback(res.data); });
    };

    /* ============================================
       AI CHAT WIDGET
       ============================================ */
    function initAIChat() {
        const btn = $('.bbr-ai-chat-btn');
        const win = $('.bbr-ai-chat-window');
        if (!btn || !win) return;

        btn.addEventListener('click', () => win.classList.toggle('open'));

        const input = $('input', win);
        const sendBtn = $('button', win);
        const messages = $('.bbr-ai-chat-messages', win);

        function addMsg(text, type) {
            const div = document.createElement('div');
            div.className = 'bbr-ai-msg ' + type;
            div.textContent = text;
            messages.appendChild(div);
            messages.scrollTop = messages.scrollHeight;
        }

        function botReply(userText) {
            const lower = userText.toLowerCase();
            const replies = {
                'price': 'Our diving packages start from $45/day trip to $2,500+ for liveaboard expeditions. Would you like specific pricing for a destination?',
                'bunaken': 'Bunaken offers world-class wall diving with 20+ dive sites. Visibility up to 40m, water temp 27-30°C. Best season: March to October.',
                'lembeh': 'Lembeh Strait is the muck diving capital! Famous for critters like frogfish, mimic octopus, and rhinopias. Best for macro photography.',
                'siladen': 'Siladen has pristine coral gardens and gentle drift dives. Perfect for all levels including beginners.',
                'bangka': 'Bangka Island features beautiful soft corals, pinnacles, and occasional pelagic encounters. A hidden gem!',
                'liveaboard': 'Our liveaboards range from 3 to 14 nights, visiting Bunaken, Bangka, and Lembeh. All-inclusive with meals and diving.',
                'course': 'We offer SSI courses from Open Water Diver to Dive Instructor. Beginner courses take 3-4 days.',
                'snorkeling': 'Snorkeling trips available at all destinations. Equipment provided. Perfect for non-divers!',
                'book': 'You can book directly here on our website, via WhatsApp at +' + (BBR.whatsapp || ''), ' or email ' + (BBR.email || '') + '.',
                'hotel': 'We partner with hotels in Manado from budget to luxury. Transfers included with dive packages.',
                'weather': 'North Sulawesi enjoys tropical climate year-round. Water temperature 27-30°C. Check our weather widget for current conditions.',
                'hello': 'Hello! Welcome to Babarida Dive Center. How can I help you plan your diving adventure today?',
                'hi': 'Hi there! Ready to explore the underwater wonders of North Sulawesi? Ask me anything!',
            };

            let reply = "I'd be happy to help! You can ask me about our destinations (Bunaken, Siladen, Bangka, Lembeh), liveaboards, courses, pricing, or booking. For detailed inquiries, please contact us via WhatsApp or email.";

            for (const [key, val] of Object.entries(replies)) {
                if (lower.includes(key)) { reply = val; break; }
            }

            setTimeout(() => addMsg(reply, 'bot'), 600);
        }

        function handleSend() {
            const text = input.value.trim();
            if (!text) return;
            addMsg(text, 'user');
            input.value = '';
            botReply(text);
        }

        sendBtn.addEventListener('click', handleSend);
        input.addEventListener('keypress', (e) => { if (e.key === 'Enter') handleSend(); });

        // Welcome message
        if (messages && messages.children.length === 0) {
            setTimeout(() => addMsg('Hello! I\'m your Babarida diving assistant. Ask me about destinations, courses, liveaboards, or pricing!', 'bot'), 500);
        }
    }

    /* ============================================
       WAIVER SIGNATURE PAD
       ============================================ */
    function initWaiver() {
        const canvas = $('.bbr-waiver-sign canvas');
        if (!canvas) return;
        const ctx = canvas.getContext('2d');
        let drawing = false;

        function resizeCanvas() {
            const rect = canvas.parentElement.getBoundingClientRect();
            canvas.width = rect.width;
            canvas.height = 100;
            ctx.strokeStyle = '#023E8A';
            ctx.lineWidth = 2;
            ctx.lineCap = 'round';
        }
        resizeCanvas();
        window.addEventListener('resize', resizeCanvas);

        function getPos(e) {
            const rect = canvas.getBoundingClientRect();
            const touch = e.touches ? e.touches[0] : e;
            return { x: touch.clientX - rect.left, y: touch.clientY - rect.top };
        }

        canvas.addEventListener('mousedown', (e) => { drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); });
        canvas.addEventListener('mousemove', (e) => { if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
        canvas.addEventListener('mouseup', () => { drawing = false; });
        canvas.addEventListener('mouseleave', () => { drawing = false; });

        canvas.addEventListener('touchstart', (e) => { e.preventDefault(); drawing = true; ctx.beginPath(); const p = getPos(e); ctx.moveTo(p.x, p.y); }, { passive: false });
        canvas.addEventListener('touchmove', (e) => { e.preventDefault(); if (!drawing) return; const p = getPos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); }, { passive: false });
        canvas.addEventListener('touchend', () => { drawing = false; });

        // Store signature data
        window.bbrGetSignature = () => canvas.toDataURL();
    }

    /* ============================================
       DASHBOARD FUNCTIONALITY
       ============================================ */
    window.bbrLoadDashboard = function(tab) {
        const content = $('#bbr-dash-content');
        if (!content) return;
        content.innerHTML = '<div style="text-align:center;padding:3rem;color:#6b7280">Loading...</div>';

        // Update active tab
        $$('.bbr-dash-nav-link').forEach(l => l.classList.remove('active'));
        const activeLink = $('.bbr-dash-nav-link[data-tab="' + tab + '"]');
        if (activeLink) activeLink.classList.add('active');

        // Update URL without reload
        const url = new URL(window.location);
        url.searchParams.set('bbr_tab', tab);
        history.replaceState(null, '', url);

        switch(tab) {
            case 'dashboard': loadDashOverview(); break;
            case 'bookings': loadDashBookings(); break;
            case 'reports': loadDashReports(); break;
            case 'analytics': loadDashAnalytics(); break;
            case 'finance': loadDashFinance(); break;
            case 'activity-log': loadDashActivityLog(); break;
            case 'system-health': loadDashSystemHealth(); break;
            case 'backups': loadDashBackups(); break;
            case 'settings': loadDashSettings(); break;
            case 'checkin': loadDashCheckin(); break;
            case 'content': loadDashContent(); break;
            default:
                content.innerHTML = '<div style="text-align:center;padding:4rem"><p style="font-size:3rem;margin-bottom:1rem">🚧</p><h3 style="color:#374151">Coming Soon</h3><p style="color:#6b7280">This section is being built.</p></div>';
        }
    };

    function loadDashOverview() {
        const content = $('#bbr-dash-content');
        if (!content) return;

        // Fetch stats
        fetch(BBR.ajaxUrl + '?action=bbr_dashboard_stats&nonce=' + BBR.nonce)
        .then(r => r.json())
        .then(res => {
            const d = res.data || {};
            content.innerHTML = `
                <div class="bbr-dash-stats">
                    <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon blue">📊</div><div class="bbr-dash-stat-val">${d.total_bookings || 0}</div><div class="bbr-dash-stat-label">Total Bookings</div></div>
                    <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon yellow">⏳</div><div class="bbr-dash-stat-val">${d.pending || 0}</div><div class="bbr-dash-stat-label">Pending</div></div>
                    <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon green">✅</div><div class="bbr-dash-stat-val">${d.confirmed || 0}</div><div class="bbr-dash-stat-label">Confirmed</div></div>
                    <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon blue">💰</div><div class="bbr-dash-stat-val">$${(d.revenue || 0).toLocaleString()}</div><div class="bbr-dash-stat-label">Revenue</div></div>
                </div>
                <div style="display:grid;grid-template-columns:2fr 1fr;gap:1.5rem">
                    <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">Recent Bookings</h3><button class="bbr-btn bbr-btn-primary" style="padding:.3rem .8rem;font-size:.75rem" onclick="bbrLoadDashboard('bookings')">View All</button></div><div class="bbr-dash-card-body" id="recent-bookings-tbl"><p style="color:#9ca3af;text-align:center;padding:1rem">Loading...</p></div></div>
                    <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">Quick Actions</h3></div><div class="bbr-dash-card-body" style="display:flex;flex-direction:column;gap:.5rem">
                        <a href="/wp-admin/post-new.php?post_type=booking" class="bbr-btn bbr-btn-primary" style="justify-content:center">+ New Booking</a>
                        <a href="/wp-admin/post-new.php?post_type=trip" class="bbr-btn bbr-btn-outline" style="justify-content:center">+ New Trip</a>
                        <a href="/wp-admin/post-new.php?post_type=liveaboard" class="bbr-btn bbr-btn-outline" style="justify-content:center">+ New Liveaboard</a>
                        <button class="bbr-btn bbr-btn-yellow" style="justify-content:center" onclick="bbrLoadDashboard('reports')">📊 View Reports</button>
                    </div></div>
                </div>
            `;
            loadRecentBookings();
        })
        .catch(() => { content.innerHTML = '<p style="color:#EF4444;text-align:center;padding:2rem">Failed to load dashboard.</p>'; });
    }

    function loadRecentBookings() {
        const container = $('#recent-bookings-tbl');
        if (!container) return;
        fetch(BBR.ajaxUrl + '?action=bbr_bookings_table&nonce=' + BBR.nonce + '&status=all&paged=1')
        .then(r => r.json())
        .then(res => {
            if (res.success && res.data) {
                container.innerHTML = '<table class="bbr-dash-table"><thead><tr><th>ID</th><th>Guest</th><th>Status</th></tr></thead><tbody>' + res.data.split('</td></tr>').slice(0, 6).join('</td></tr>') + '</tbody></table>';
            }
        });
    }

    function loadDashBookings() {
        const content = $('#bbr-dash-content');
        if (!content) return;
        content.innerHTML = `
            <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">All Bookings</h3>
            <div style="display:flex;gap:.5rem">
                <select id="bbr-book-status-filter" style="padding:.4rem .8rem;border:1px solid #d1d5db;border-radius:8px;font-size:.82rem"><option value="all">All Status</option><option value="pending">Pending</option><option value="confirmed">Confirmed</option><option value="paid">Paid</option><option value="checked-in">Checked-In</option><option value="completed">Completed</option><option value="cancelled">Cancelled</option></select>
                <button onclick="bbrRefreshBookings()" class="bbr-btn bbr-btn-primary" style="padding:.4rem .8rem;font-size:.75rem">Refresh</button>
            </div></div>
            <div class="bbr-dash-card-body" style="overflow-x:auto"><table class="bbr-dash-table"><thead><tr><th>ID</th><th>Guest</th><th>Trip</th><th>Date</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead><tbody id="bbr-bookings-tbody"><tr><td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af">Loading...</td></tr></tbody></table></div></div>
        `;
        bbrRefreshBookings();

        // Status filter
        const filter = $('#bbr-book-status-filter');
        if (filter) {
            filter.addEventListener('change', bbrRefreshBookings);
        }
    }

    window.bbrRefreshBookings = function() {
        const tbody = $('#bbr-bookings-tbody');
        const filter = $('#bbr-book-status-filter');
        if (!tbody) return;
        const status = filter ? filter.value : 'all';
        tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af">Loading...</td></tr>';
        fetch(BBR.ajaxUrl + '?action=bbr_bookings_table&nonce=' + BBR.nonce + '&status=' + status + '&paged=1')
        .then(r => r.json())
        .then(res => {
            if (res.success) {
                tbody.innerHTML = res.data;
            } else {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align:center;color:#EF4444">Error loading bookings.</td></tr>';
            }
        });
    };

    window.bbrChangeStatus = function(bookingId) {
        const statuses = ['pending','confirmed','paid','checked-in','completed','cancelled'];
        const current = prompt('Enter new status:\n' + statuses.join(', '));
        if (!current || !statuses.includes(current)) return;
        fetch(BBR.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'bbr_change_status', nonce: BBR.nonce, booking_id: bookingId, status: current })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) { alert('Status updated!'); bbrRefreshBookings(); }
            else alert('Failed to update.');
        });
    };

    function loadDashReports() {
        const content = $('#bbr-dash-content');
        content.innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:1.5rem">
                <div class="bbr-dash-card" style="cursor:pointer" onclick="alert('Daily report generation — coming with PDF export')"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">📋</p><h4>Daily Report</h4><p style="color:#6b7280;font-size:.85rem">Today's bookings & revenue</p></div></div>
                <div class="bbr-dash-card" style="cursor:pointer" onclick="alert('Weekly report generation — coming with PDF export')"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">📊</p><h4>Weekly Report</h4><p style="color:#6b7280;font-size:.85rem">7-day performance summary</p></div></div>
                <div class="bbr-dash-card" style="cursor:pointer" onclick="alert('Monthly report generation — coming with PDF export')"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">📈</p><h4>Monthly Report</h4><p style="color:#6b7280;font-size:.85rem">Full monthly analytics</p></div></div>
            </div>
            <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">Revenue Overview</h3></div><div class="bbr-dash-card-body" style="min-height:300px;display:flex;align-items:center;justify-content:center;color:#9ca3af"><p>📈 Chart integration — Connect Google Charts or Chart.js for visual analytics</p></div></div>
        `;
    }

    function loadDashAnalytics() {
        const content = $('#bbr-dash-content');
        content.innerHTML = `
            <div class="bbr-dash-stats">
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon blue">👁️</div><div class="bbr-dash-stat-val">—</div><div class="bbr-dash-stat-label">Page Views (GA4)</div></div>
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon green">👥</div><div class="bbr-dash-stat-val">—</div><div class="bbr-dash-stat-label">Unique Visitors</div></div>
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon yellow">⏱️</div><div class="bbr-dash-stat-val">—</div><div class="bbr-dash-stat-label">Avg. Session</div></div>
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon red">📉</div><div class="bbr-dash-stat-val">—</div><div class="bbr-dash-stat-label">Bounce Rate</div></div>
            </div>
            <div class="bbr-dash-card"><div class="bbr-dash-card-body" style="text-align:center;padding:3rem;color:#6b7280">
                <p style="font-size:2rem;margin-bottom:1rem">📊</p>
                <h3 style="margin-bottom:.5rem">Google Analytics 4 Integration</h3>
                <p>Connect your GA4 account in Settings to see live analytics data here. Configure your GA4 Measurement ID in Appearance > Customize > SEO Settings.</p>
            </div></div>
        `;
    }

    function loadDashFinance() {
        const content = $('#bbr-dash-content');
        content.innerHTML = `
            <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">Financial Summary</h3><button class="bbr-btn bbr-btn-primary" style="padding:.3rem .8rem;font-size:.75rem" onclick="alert('Export to Excel — coming soon')">Export Excel</button></div>
            <div class="bbr-dash-card-body">
                <table class="bbr-dash-table"><thead><tr><th>Month</th><th>Bookings</th><th>Revenue (USD)</th><th>Deposits</th><th>Outstanding</th></tr></thead>
                <tbody>
                    <tr><td>${new Date().toLocaleDateString('en-US',{month:'long',year:'numeric'})}</td><td>—</td><td>—</td><td>—</td><td>—</td></tr>
                    <tr><td>${new Date(new Date().setMonth(new Date().getMonth()-1)).toLocaleDateString('en-US',{month:'long',year:'numeric'})}</td><td>—</td><td>—</td><td>—</td><td>—</td></tr>
                    <tr><td>${new Date(new Date().setMonth(new Date().getMonth()-2)).toLocaleDateString('en-US',{month:'long',year:'numeric'})}</td><td>—</td><td>—</td><td>—</td><td>—</td></tr>
                </tbody></table>
                <p style="text-align:center;color:#9ca3af;margin-top:1rem;font-size:.85rem">Financial data populates as bookings are completed with payment records.</p>
            </div></div>
        `;
    }

    function loadDashActivityLog() {
        const content = $('#bbr-dash-content');
        content.innerHTML = '<div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">Activity Log</h3></div><div class="bbr-dash-card-body" style="max-height:600px;overflow-y:auto"><p style="text-align:center;color:#9ca3af;padding:2rem">Activity log displays login events, post changes, booking updates, and pricing changes. Data is stored in WordPress options table.</p></div></div>';
    }

    function loadDashSystemHealth() {
        const content = $('#bbr-dash-content');
        content.innerHTML = `
            <div class="bbr-dash-stats">
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon green">🟢</div><div class="bbr-dash-stat-val">Healthy</div><div class="bbr-dash-stat-label">System Status</div></div>
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon blue">💾</div><div class="bbr-dash-stat-val">—</div><div class="bbr-dash-stat-label">DB Size</div></div>
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon yellow">🔧</div><div class="bbr-dash-stat-val">${typeof PHP_VERSION !== 'undefined' ? 'PHP ' + PHP_VERSION : '—'}</div><div class="bbr-dash-stat-label">PHP Version</div></div>
                <div class="bbr-dash-stat"><div class="bbr-dash-stat-icon blue">📱</div><div class="bbr-dash-stat-val">PWA Ready</div><div class="bbr-dash-stat-label">Progressive Web App</div></div>
            </div>
            <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">System Information</h3></div>
            <div class="bbr-dash-card-body">
                <p style="color:#6b7280;font-size:.88rem">Detailed system health data (database optimization, storage usage, security warnings) is available through the server-side health monitor. Contact your system administrator for full diagnostics.</p>
            </div></div>
        `;
    }

    function loadDashBackups() {
        const content = $('#bbr-dash-content');
        content.innerHTML = `
            <div style="display:flex;gap:1rem;margin-bottom:1.5rem">
                <button class="bbr-btn bbr-btn-primary" onclick="bbrCreateBackup()">💾 Create Backup Now</button>
                <span style="color:#6b7280;font-size:.85rem;display:flex;align-items:center">Auto-backup runs daily at midnight</span>
            </div>
            <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">Backup Files</h3></div>
            <div class="bbr-dash-card-body" id="bbr-backup-list"><p style="text-align:center;color:#9ca3af;padding:1rem">Loading...</p></div></div>
        `;

        // Load backup list
        fetch(BBR.ajaxUrl + '?action=bbr_list_backups&nonce=' + BBR.nonce)
        .then(r => r.json())
        .then(res => {
            const list = $('#bbr-backup-list');
            if (res.success && res.data && res.data.length) {
                list.innerHTML = '<table class="bbr-dash-table"><thead><tr><th>Filename</th><th>Size</th><th>Date</th></tr></thead><tbody>' +
                    res.data.map(b => '<tr><td>' + b.name + '</td><td>' + b.size + '</td><td>' + b.date + '</td></tr>').join('') +
                    '</tbody></table>';
            } else {
                list.innerHTML = '<p style="text-align:center;color:#9ca3af;padding:2rem">No backups found.</p>';
            }
        });
    }

    window.bbrCreateBackup = function() {
        if (!confirm('Create a new database backup now?')) return;
        fetch(BBR.ajaxUrl, {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ action: 'bbr_create_backup', nonce: BBR.nonce })
        })
        .then(r => r.json())
        .then(res => {
            if (res.success) { alert('Backup created: ' + res.data.file); loadDashBackups(); }
            else alert('Backup failed.');
        });
    };

    function loadDashSettings() {
        const content = $('#bbr-dash-content');
        content.innerHTML = `
            <div class="bbr-dash-card"><div class="bbr-dash-card-header"><h3 class="bbr-dash-card-title">Dashboard Settings</h3></div>
            <div class="bbr-dash-card-body">
                <p style="color:#6b7280;margin-bottom:1.5rem">Configure system settings below. Most settings are also available in <a href="/wp-admin/customize.php" style="color:#0077B6;text-decoration:underline">Appearance > Customize</a> and <a href="/wp-admin/options-general.php" style="color:#0077B6;text-decoration:underline">Settings</a>.</p>
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem">
                    <div><h4 style="font-size:.9rem;margin-bottom:.75rem;color:#374151">Quick Links</h4>
                        <div style="display:flex;flex-direction:column;gap:.5rem">
                            <a href="/wp-admin/customize.php" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">🎨 Theme Customizer</a>
                            <a href="/wp-admin/options-permalink.php" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">🔗 Permalinks</a>
                            <a href="/wp-admin/options-reading.php" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">📖 Reading Settings</a>
                            <a href="/wp-admin/users.php" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">👥 Users & Roles</a>
                        </div>
                    </div>
                    <div><h4 style="font-size:.9rem;margin-bottom:.75rem;color:#374151">Integrations</h4>
                        <div style="display:flex;flex-direction:column;gap:.5rem">
                            <a href="/wp-admin/customize.php?autofocus[section]=bbr_payment" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">💳 Payment Gateways</a>
                            <a href="/wp-admin/customize.php?autofocus[section]=bbr_seo" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">🔍 SEO Settings</a>
                            <a href="/wp-admin/customize.php?autofocus[section]=bbr_weather" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">🌤️ Weather API</a>
                            <a href="/wp-admin/customize.php?autofocus[section]=bbr_social" class="bbr-btn bbr-btn-outline" style="justify-content:center;font-size:.82rem">📱 Social Media</a>
                        </div>
                    </div>
                </div>
            </div></div>
        `;
    }

    function loadDashCheckin() {
        const content = $('#bbr-dash-content');
        content.innerHTML = '<div class="bbr-dash-card"><div class="bbr-dash-card-body" style="text-align:center;padding:3rem"><p style="font-size:2rem;margin-bottom:1rem">✅</p><h3 style="margin-bottom:.5rem">Check-In Management</h3><p style="color:#6b7280">Use the <a href="/check-in/" style="color:#0077B6;text-decoration:underline">Check-In page</a> for guest registration, passport uploads, and QR code generation.</p></div></div>';
    }

    function loadDashContent() {
        const content = $('#bbr-dash-content');
        content.innerHTML = `
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem">
                <a href="/wp-admin/post-new.php?post_type=post" class="bbr-dash-card" style="display:block;text-decoration:none"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">📝</p><h4 style="color:#374151">New Blog Post</h4></div></a>
                <a href="/wp-admin/post-new.php?post_type=destination" class="bbr-dash-card" style="display:block;text-decoration:none"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">🏝️</p><h4 style="color:#374151">New Destination</h4></div></a>
                <a href="/wp-admin/post-new.php?post_type=faq" class="bbr-dash-card" style="display:block;text-decoration:none"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">❓</p><h4 style="color:#374151">New FAQ</h4></div></a>
                <a href="/wp-admin/edit.php?post_type=testimonial" class="bbr-dash-card" style="display:block;text-decoration:none"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">⭐</p><h4 style="color:#374151">Manage Testimonials</h4></div></a>
                <a href="/wp-admin/upload.php" class="bbr-dash-card" style="display:block;text-decoration:none"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">🖼️</p><h4 style="color:#374151">Media Library</h4></div></a>
                <a href="/wp-admin/edit.php" class="bbr-dash-card" style="display:block;text-decoration:none"><div class="bbr-dash-card-body" style="text-align:center;padding:2rem"><p style="font-size:2rem;margin-bottom:.5rem">📄</p><h4 style="color:#374151">All Posts</h4></div></a>
            </div>
        `;
    }

    /* ============================================
       DASHBOARD SIDEBAR TOGGLE (MOBILE)
       ============================================ */
    function initDashSidebar() {
        const toggle = $('.bbr-dash-sidebar-toggle');
        const sidebar = $('.bbr-dash-sidebar');
        if (!toggle || !sidebar) return;
        toggle.addEventListener('click', () => sidebar.classList.toggle('open'));
    }

    /* ============================================
       LANGUAGE SWITCHER
       ============================================ */
    function initLangSwitch() {
        $$('.bbr-lang-switch').forEach(el => {
            el.addEventListener('click', () => {
                const current = el.querySelector('.active-lang')?.textContent || 'EN';
                const next = current === 'EN' ? 'ID' : 'EN';
                // Simple page reload with language parameter
                const url = new URL(window.location);
                url.searchParams.set('lang', next.toLowerCase());
                window.location.href = url.toString();
            });
        });
    }

    /* ============================================
       PARTNER LOGO CAROUSEL (INFINITE SCROLL)
       ============================================ */
    function initPartnerCarousel() {
        const track = $('.bbr-partner-track');
        if (!track) return;
        // Duplicate items for infinite scroll
        const items = track.innerHTML;
        track.innerHTML = items + items;
    }

    /* ============================================
       BUBBLE ANIMATIONS
       ============================================ */
    function initBubbles() {
        $$('.bbr-bubbles').forEach(container => {
            for (let i = 0; i < 15; i++) {
                const bubble = document.createElement('div');
                bubble.className = 'bbr-bubble';
                const size = Math.random() * 30 + 10;
                bubble.style.cssText = 'width:' + size + 'px;height:' + size + 'px;left:' + (Math.random() * 100) + '%;animation-duration:' + (Math.random() * 8 + 6) + 's;animation-delay:' + (Math.random() * 5) + 's';
                container.appendChild(bubble);
            }
        });
    }

    /* ============================================
       SMOOTH SCROLL FOR ANCHOR LINKS
       ============================================ */
    function initSmoothScroll() {
        $$('a[href^="#"]').forEach(a => {
            a.addEventListener('click', (e) => {
                const target = document.querySelector(a.getAttribute('href'));
                if (target) {
                    e.preventDefault();
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    }

    /* ============================================
       LAZY LOADING ENHANCEMENT
       ============================================ */
    function initLazyLoad() {
        if ('IntersectionObserver' in window) {
            const imgObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        if (img.dataset.src) {
                            img.src = img.dataset.src;
                            img.removeAttribute('data-src');
                        }
                        if (img.dataset.srcset) {
                            img.srcset = img.dataset.srcset;
                            img.removeAttribute('data-srcset');
                        }
                        imgObserver.unobserve(img);
                    }
                });
            }, { rootMargin: '200px' });

            $$('img[data-src]').forEach(img => imgObserver.observe(img));
        }
    }

    /* ============================================
       INIT ALL
       ============================================ */
    document.addEventListener('DOMContentLoaded', () => {
        initPreloader();
        initClocks();
        initHeader();
        initMobileMenu();
        initFloatingBtns();
        initReveal();
        initTestiSlider();
        initFAQ();
        initSearch();
        initBookingForm();
        initContactForm();
        initCurrency();
        initNewsletter();
        initAIChat();
        initWaiver();
        initDashSidebar();
        initLangSwitch();
        initPartnerCarousel();
        initBubbles();
        initSmoothScroll();
        initLazyLoad();

        // Initialize Lucide icons
        if (window.lucide) {
            window.lucide.createIcons();
        }

        // Load dashboard tab if on dashboard page
        if ($('#bbr-dash-content')) {
            const params = new URLSearchParams(window.location.search);
            const tab = params.get('bbr_tab') || 'dashboard';
            bbrLoadDashboard(tab);
        }
    });

})();
