/**
 * Formula Acqua - Premium JavaScript v4.6.64
 *
 * @package Formula_Acqua
 */

(function($) {
    'use strict';

    const FA = {
        init() {
            this.header();
            this.langDropdown();
            this.categoryDrawer();
            this.wishlist();
            this.shopFilters();
            this.faq();
            this.newsletter();
            this.contactForm();
            this.accountAuthTabs();
            this.passwordToggles();
            this.searchPlaceholder();
            this.floatingContacts();
            this.scrollAnimations();
            this.smoothCounters();
            this.videoBand();
            this.clickBubbles();
            this.heroOffers();
        },

        /* ============================
           Search bar — placeholder più corto su mobile (no cambi su desktop)
           ============================ */
        searchPlaceholder() {
            const input = document.querySelector('.fa-search-bar input[type="search"]');
            if (!input) return;
            const desktopText = input.getAttribute('data-placeholder-desktop');
            const mobileText = input.getAttribute('data-placeholder-mobile');
            if (!desktopText || !mobileText) return;

            const mq = window.matchMedia('(max-width: 600px)');
            function apply() {
                input.setAttribute('placeholder', mq.matches ? mobileText : desktopText);
            }
            apply();
            if (mq.addEventListener) {
                mq.addEventListener('change', apply);
            } else if (mq.addListener) {
                mq.addListener(apply);
            }
        },

        /* ============================
           Pulsanti flottanti — su mobile si nascondono solo quando la
           griglia "categorie rapide" occupa realmente lo stesso angolo
           dello schermo, per non coprire le card appena sotto l'hero
           (solo ≤900px, calcolo su bounding box reali).
           ============================ */
        floatingContacts() {
            const wrap = document.querySelector('.fa-float-contacts');
            const quickcats = document.querySelector('.fa-mobile-quickcats');
            if (!wrap || !quickcats) return;

            const mq = window.matchMedia('(max-width: 900px)');
            const clearance = 20;
            let ticking = false;

            function update() {
                ticking = false;
                if (!mq.matches) {
                    wrap.classList.remove('is-hidden');
                    return;
                }
                const catRect = quickcats.getBoundingClientRect();
                const btnRect = wrap.getBoundingClientRect();
                const overlaps = catRect.bottom > (btnRect.top - clearance) && catRect.top < btnRect.bottom;
                wrap.classList.toggle('is-hidden', overlaps);
            }
            function onScroll() {
                if (!ticking) {
                    ticking = true;
                    requestAnimationFrame(update);
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll);
            if (mq.addEventListener) {
                mq.addEventListener('change', update);
            } else if (mq.addListener) {
                mq.addListener(update);
            }
            update();
        },

        /* ============================
           Header — fixed height, no shrink on scroll
           ============================ */
        header() {
            const header = document.getElementById('fa-header');
            if (!header) return;

            let ticking = false;
            function onScroll() {
                if (!ticking) {
                    requestAnimationFrame(function() {
                        header.classList.toggle('scrolled', window.pageYOffset > 24);
                        ticking = false;
                    });
                    ticking = true;
                }
            }
            window.addEventListener('scroll', onScroll, { passive: true });
            onScroll();
        },

        /* ============================
           Language dropdown (Polylang-ready)
           ============================ */
        langDropdown() {
            const dropdowns = document.querySelectorAll('[data-fa-lang-dropdown]');
            if (!dropdowns.length) return;

            const header = document.getElementById('fa-header');

            const closeAll = function(except) {
                dropdowns.forEach(function(el) {
                    if (except && el === except) return;
                    el.classList.remove('is-open');
                    const btn = el.querySelector('.fa-lang-dropdown__toggle');
                    const menu = el.querySelector('.fa-lang-dropdown__menu');
                    if (btn) btn.setAttribute('aria-expanded', 'false');
                    if (menu) menu.hidden = true;
                });
                if (header) header.classList.remove('is-lang-open');
            };

            dropdowns.forEach(function(dropdown) {
                const toggle = dropdown.querySelector('.fa-lang-dropdown__toggle');
                const menu = dropdown.querySelector('.fa-lang-dropdown__menu');
                if (!toggle || !menu) return;

                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    const willOpen = !dropdown.classList.contains('is-open');
                    closeAll();
                    if (willOpen) {
                        dropdown.classList.add('is-open');
                        toggle.setAttribute('aria-expanded', 'true');
                        menu.hidden = false;
                        if (header) header.classList.add('is-lang-open');
                    }
                });
            });

            document.addEventListener('click', function(e) {
                if (!e.target.closest('[data-fa-lang-dropdown]')) {
                    closeAll();
                }
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') closeAll();
            });
        },

        /* ============================
           Category Drawer
           ============================ */
        categoryDrawer() {
            const toggle = document.getElementById('fa-cats-toggle');
            const drawer = document.getElementById('fa-cats-drawer');
            const backdrop = document.getElementById('fa-cats-backdrop');
            const closeBtn = document.getElementById('fa-cats-close');
            const heroCats = document.getElementById('fa-hero-cats');
            if (!toggle || !drawer || !backdrop) return;

            const open = function() {
                drawer.classList.add('is-open');
                backdrop.classList.add('is-open');
                backdrop.hidden = false;
                drawer.setAttribute('aria-hidden', 'false');
                toggle.setAttribute('aria-expanded', 'true');
                document.body.classList.add('cats-open');
            };

            const close = function() {
                drawer.classList.remove('is-open');
                backdrop.classList.remove('is-open');
                drawer.setAttribute('aria-hidden', 'true');
                toggle.setAttribute('aria-expanded', 'false');
                document.body.classList.remove('cats-open');
                setTimeout(function() {
                    if (!drawer.classList.contains('is-open')) {
                        backdrop.hidden = true;
                    }
                }, 300);
            };

            toggle.addEventListener('click', function() {
                if (drawer.classList.contains('is-open')) {
                    close();
                } else {
                    open();
                }
            });

            if (closeBtn) closeBtn.addEventListener('click', close);
            backdrop.addEventListener('click', close);
            if (heroCats) heroCats.addEventListener('click', open);

            drawer.querySelectorAll('a').forEach(function(link) {
                link.addEventListener('click', close);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && drawer.classList.contains('is-open')) {
                    close();
                }
            });
        },

        /* ============================
           Wishlist / Preferiti
           ============================ */
        wishlist() {
            const self = this;
            const cfg = (typeof formulaAcqua !== 'undefined') ? formulaAcqua : null;
            if (!cfg || !cfg.ajaxUrl) return;

            const updateCount = function(count, countHtml) {
                const badges = document.querySelectorAll('.fa-wish-count');
                if (countHtml && badges.length) {
                    badges.forEach(function(el) {
                        const wrap = document.createElement('div');
                        wrap.innerHTML = countHtml.trim();
                        const next = wrap.firstElementChild;
                        if (next) el.replaceWith(next);
                    });
                    return;
                }
                badges.forEach(function(el) {
                    el.setAttribute('data-count', String(count));
                    el.textContent = String(count);
                });
            };

            const setButtonState = function(btn, inWishlist, label, text) {
                btn.classList.toggle('is-active', !!inWishlist);
                btn.setAttribute('aria-pressed', inWishlist ? 'true' : 'false');
                if (label) {
                    btn.setAttribute('aria-label', label);
                    btn.setAttribute('title', label);
                }
                const svg = btn.querySelector('svg');
                if (svg) svg.setAttribute('fill', inWishlist ? 'currentColor' : 'none');
                const labelEl = btn.querySelector('.fa-wishlist-btn__label');
                if (labelEl && text) labelEl.textContent = text;
            };

            $(document).on('click', '.fa-wishlist-btn, .fa-wishlist-remove', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const btn = this;
                if (btn.classList.contains('is-loading')) return;

                const productId = btn.getAttribute('data-product-id');
                if (!productId) return;

                btn.classList.add('is-loading');

                $.post(cfg.ajaxUrl, {
                    action: 'fa_wishlist_toggle',
                    nonce: cfg.nonce,
                    product_id: productId
                }).done(function(res) {
                    if (!res || !res.success || !res.data) return;

                    const data = res.data;
                    updateCount(data.count, data.count_html);

                    document.querySelectorAll('.fa-wishlist-btn[data-product-id="' + productId + '"]').forEach(function(el) {
                        setButtonState(el, data.in_wishlist, data.label, data.text);
                    });

                    // Rimozione dalla pagina preferiti
                    if (!data.in_wishlist) {
                        const item = btn.closest('.fa-wishlist-item');
                        if (item) {
                            item.style.opacity = '0';
                            item.style.transform = 'scale(0.96)';
                            setTimeout(function() {
                                item.remove();
                                const grid = document.querySelector('.fa-wishlist-grid');
                                const page = document.querySelector('.fa-wishlist-page');
                                if (grid && !grid.querySelector('.fa-wishlist-item') && page) {
                                    window.location.reload();
                                } else if (page) {
                                    const desc = page.querySelector('.fa-wishlist-page__desc');
                                    if (desc) {
                                        const n = data.count;
                                        desc.textContent = n === 1 ? '1 prodotto salvato' : (n + ' prodotti salvati');
                                    }
                                }
                            }, 220);
                        }
                    }
                }).always(function() {
                    btn.classList.remove('is-loading');
                });
            });
        },

        /* ============================
           Account Login / Register Tabs
           ============================ */
        accountAuthTabs() {
            const tabs = document.querySelectorAll('.fa-auth__tab[data-fa-auth-tab]');
            const switches = document.querySelectorAll('.fa-auth__switch-btn[data-fa-auth-tab]');
            const panes = document.querySelectorAll('[data-fa-auth-pane]');
            const title = document.querySelector('.fa-auth__title');
            const subtitle = document.querySelector('[data-fa-auth-subtitle]');
            if (!panes.length) return;

            const copy = {
                login: {
                    title: 'Accedi',
                    subtitle: 'Accedi con email e password per continuare gli acquisti su Formula Acqua.'
                },
                register: {
                    title: 'Crea il tuo account',
                    subtitle: 'Registrati in pochi secondi. Potrai seguire gli ordini e salvare i tuoi prodotti preferiti.'
                }
            };

            const activate = function(target) {
                document.querySelectorAll('[data-fa-auth-tab]').forEach(function(t) {
                    const active = t.getAttribute('data-fa-auth-tab') === target;
                    if (t.classList.contains('fa-auth__tab')) {
                        t.classList.toggle('is-active', active);
                        t.setAttribute('aria-selected', active ? 'true' : 'false');
                    }
                });
                panes.forEach(function(pane) {
                    const active = pane.getAttribute('data-fa-auth-pane') === target;
                    pane.classList.toggle('is-active', active);
                    pane.hidden = !active;
                });
                if (title && copy[target]) title.textContent = copy[target].title;
                if (subtitle && copy[target]) subtitle.textContent = copy[target].subtitle;
            };

            tabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    activate(tab.getAttribute('data-fa-auth-tab'));
                });
            });
            switches.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    activate(btn.getAttribute('data-fa-auth-tab'));
                });
            });
        },

        /* ============================
           Password show / hide
           ============================ */
        passwordToggles() {
            document.querySelectorAll('[data-fa-toggle-pass]').forEach(function(btn) {
                /* Etichette già tradotte lato server (fa_t) nella lingua corrente. */
                const labelShow = btn.getAttribute('data-label-show') || btn.getAttribute('aria-label') || 'Mostra password';
                const labelHide = btn.getAttribute('data-label-hide') || labelShow;
                btn.addEventListener('click', function() {
                    const id = btn.getAttribute('data-fa-toggle-pass');
                    const input = id ? document.getElementById(id) : null;
                    if (!input) return;
                    const show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    /*
                     * Un'unica icona occhio per bottone: la barra diagonale
                     * (.fa-auth__eye-slash) viene solo mostrata/nascosta via
                     * classe CSS. Niente due SVG separati, niente rischio di
                     * sovrapposizione tra icone.
                     */
                    btn.classList.toggle('is-visible', show);
                    btn.setAttribute('aria-pressed', show ? 'true' : 'false');
                    btn.setAttribute('aria-label', show ? labelHide : labelShow);
                });
            });
        },

        /* ============================
           Shop Filters
           ============================ */
        shopFilters() {
            const toggle = document.getElementById('fa-filter-toggle');
            const sidebar = document.getElementById('fa-shop-sidebar');
            const close = document.getElementById('fa-sidebar-close');
            if (!toggle || !sidebar) return;

            toggle.addEventListener('click', function() {
                sidebar.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            if (close) {
                close.addEventListener('click', function() {
                    sidebar.classList.remove('active');
                    document.body.style.overflow = '';
                });
            }
        },

        /* ============================
           FAQ Accordion
           ============================ */
        faq() {
            document.querySelectorAll('.fa-faq-item__q').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const expanded = this.getAttribute('aria-expanded') === 'true';
                    const parent = this.closest('.fa-faq-home__list, .fa-faq-list, .fa-faq-page');

                    if (parent) {
                        parent.querySelectorAll('.fa-faq-item__q[aria-expanded="true"]').forEach(function(other) {
                            if (other !== btn) {
                                other.setAttribute('aria-expanded', 'false');
                                other.nextElementSibling.style.maxHeight = null;
                            }
                        });
                    }

                    this.setAttribute('aria-expanded', !expanded);
                    answer.style.maxHeight = expanded ? null : answer.scrollHeight + 'px';
                });
            });
        },

        /* ============================
           Newsletter
           ============================ */
        newsletter() {
            const form = document.getElementById('fa-newsletter-form');
            const msg = document.getElementById('fa-newsletter-message');
            if (!form || !msg) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const email = form.querySelector('input[type="email"]').value;
                const privacy = form.querySelector('input[name="privacy"]');

                if (!privacy || !privacy.checked) {
                    msg.textContent = 'Devi accettare la privacy policy.';
                    msg.className = 'fa-newsletter__msg error';
                    return;
                }

                const btn = form.querySelector('button[type="submit"]');
                btn.disabled = true;
                btn.innerHTML = '<span class="fa-spinner"></span> Invio...';

                $.ajax({
                    url: formulaAcqua.ajaxUrl,
                    type: 'POST',
                    data: { action: 'fa_newsletter', nonce: formulaAcqua.nonce, email: email },
                    success: function(res) {
                        msg.textContent = res.data.message;
                        msg.className = 'fa-newsletter__msg ' + (res.success ? 'success' : 'error');
                        if (res.success) form.reset();
                    },
                    error: function() {
                        msg.textContent = 'Errore di connessione.';
                        msg.className = 'fa-newsletter__msg error';
                    },
                    complete: function() {
                        btn.disabled = false;
                        btn.textContent = 'Iscriviti';
                    }
                });
            });
        },

        /* ============================
           Contact Form
           ============================ */
        contactForm() {
            const form = document.getElementById('fa-contact-form');
            const msg = document.getElementById('fa-contact-message');
            if (!form || !msg) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = form.querySelector('button[type="submit"]');
                const data = {
                    action: 'fa_contact',
                    nonce: formulaAcqua.nonce,
                    name: form.querySelector('[name="name"]').value,
                    email: form.querySelector('[name="email"]').value,
                    phone: form.querySelector('[name="phone"]') ? form.querySelector('[name="phone"]').value : '',
                    subject: form.querySelector('[name="subject"]') ? form.querySelector('[name="subject"]').value : '',
                    message: form.querySelector('[name="message"]').value
                };

                btn.disabled = true;
                $.ajax({
                    url: formulaAcqua.ajaxUrl,
                    type: 'POST',
                    data: data,
                    success: function(res) {
                        msg.textContent = res.data.message;
                        msg.className = 'fa-contact__msg ' + (res.success ? 'success' : 'error');
                        if (res.success) form.reset();
                    },
                    error: function() {
                        msg.textContent = 'Errore di connessione.';
                        msg.className = 'fa-contact__msg error';
                    },
                    complete: function() {
                        btn.disabled = false;
                    }
                });
            });
        },

        /* ============================
           Scroll Animations
           ============================ */
        scrollAnimations() {
            const els = document.querySelectorAll('[data-fa-animate]');
            if (!els.length || !('IntersectionObserver' in window)) {
                els.forEach(function(el) { el.classList.add('fa-visible'); });
                return;
            }

            const io = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const delay = parseInt(entry.target.getAttribute('data-fa-delay') || '0', 10);
                        setTimeout(function() {
                            entry.target.classList.add('fa-visible');
                        }, delay);
                        io.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });

            els.forEach(function(el) { io.observe(el); });
        },

        /* ============================
           Smooth Counters
           ============================ */
        smoothCounters() {
            const nums = document.querySelectorAll('.fa-stat__number');
            if (!nums.length || !('IntersectionObserver' in window)) return;

            const io = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (!entry.isIntersecting) return;
                    const el = entry.target;
                    const target = parseInt(el.getAttribute('data-count') || el.textContent.replace(/\D/g, ''), 10);
                    if (!target) return;
                    const suffix = el.getAttribute('data-suffix') || '';
                    const duration = 1400;
                    const start = performance.now();

                    function tick(now) {
                        const p = Math.min((now - start) / duration, 1);
                        const eased = 1 - Math.pow(1 - p, 3);
                        el.textContent = Math.floor(target * eased).toLocaleString('it-IT') + suffix;
                        if (p < 1) requestAnimationFrame(tick);
                    }
                    requestAnimationFrame(tick);
                    io.unobserve(el);
                });
            }, { threshold: 0.4 });

            nums.forEach(function(n) { io.observe(n); });
        },

        /* ============================
           Video band — loop fluido + parallax minimo
           ============================ */
        videoBand() {
            const band = document.getElementById('fa-video-band');
            const video = document.getElementById('fa-band-video');
            const media = band ? band.querySelector('.fa-video-band__media') : null;
            if (!band || !video || !media) return;

            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            video.muted = true;
            video.defaultMuted = true;
            video.playsInline = true;
            video.loop = true;
            video.playbackRate = reduceMotion ? 1 : 0.8;
            video.preload = 'auto';

            const tryPlay = function() {
                const p = video.play();
                if (p && typeof p.catch === 'function') {
                    p.catch(function() {});
                }
            };

            /* Loop continuo senza stacco: riparte poco prima della fine */
            video.addEventListener('timeupdate', function() {
                if (!video.duration || !isFinite(video.duration)) return;
                if (video.currentTime > video.duration - 0.12) {
                    video.currentTime = 0.02;
                    tryPlay();
                }
            });
            video.addEventListener('ended', function() {
                video.currentTime = 0.02;
                tryPlay();
            });

            if ('IntersectionObserver' in window) {
                const io = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            tryPlay();
                        } else {
                            video.pause();
                        }
                    });
                }, { threshold: 0.05, rootMargin: '120px 0px' });
                io.observe(band);
            } else {
                tryPlay();
            }

            if (reduceMotion) return;

            let ticking = false;
            const maxShift = 3;
            const frame = band.querySelector('.fa-section-break__frame') || band;

            function updateParallax() {
                const rect = frame.getBoundingClientRect();
                const viewH = window.innerHeight || 1;
                const center = rect.top + rect.height / 2;
                const progress = (center - viewH / 2) / viewH;
                const shift = Math.max(-maxShift, Math.min(maxShift, -progress * maxShift));
                media.style.transform = 'translate3d(0, ' + shift.toFixed(2) + 'px, 0) scale(1.05)';
                ticking = false;
            }

            function onScroll() {
                if (ticking) return;
                ticking = true;
                requestAnimationFrame(updateParallax);
            }

            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', onScroll, { passive: true });
            onScroll();
        },

        /* ============================
           Click bubbles — bolle discrete premium (sito intero)
           ============================ */
        clickBubbles() {
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            let layer = document.getElementById('fa-bubbles');
            if (!layer) {
                layer = document.createElement('div');
                layer.id = 'fa-bubbles';
                layer.className = 'fa-bubbles';
                layer.setAttribute('aria-hidden', 'true');
            }
            /* Sempre su <body>, fuori da overflow:hidden dei contenitori */
            if (layer.parentNode !== document.body) {
                document.body.appendChild(layer);
            }

            const MAX_BUBBLES = 24;
            const SIZES = [12, 14, 15, 16, 18, 20, 22];

            function prune(keepRoom) {
                while (layer.children.length > MAX_BUBBLES - keepRoom && layer.firstChild) {
                    layer.removeChild(layer.firstChild);
                }
            }

            function px(n) {
                return n.toFixed(1) + 'px';
            }

            function makeBubble(x, y) {
                const b = document.createElement('span');
                b.className = 'fa-bubble';

                const size = SIZES[Math.floor(Math.random() * SIZES.length)];
                /* Cluster stretto sul punto di click */
                const ox = (Math.random() - 0.5) * 14;
                const oy = (Math.random() - 0.5) * 8;
                const rise = 70 + Math.random() * 40; /* 70–110px */
                const op = (0.52 + Math.random() * 0.18).toFixed(2); /* ~0.52–0.70 */
                const dur = (1.15 + Math.random() * 0.3).toFixed(2) + 's';
                const sway = 8 + Math.random() * 6;
                const x1 = (Math.random() - 0.5) * sway;
                const x2 = (Math.random() - 0.5) * sway * 1.2;
                const x3 = (Math.random() - 0.5) * sway * 0.8;

                b.style.cssText =
                    'width:' + size + 'px;' +
                    'height:' + size + 'px;' +
                    'left:' + (x + ox - size / 2) + 'px;' +
                    'top:' + (y + oy - size / 2) + 'px;' +
                    '--fa-bubble-op:' + op + ';' +
                    '--fa-bubble-x1:' + px(x1) + ';' +
                    '--fa-bubble-x2:' + px(x2) + ';' +
                    '--fa-bubble-x3:' + px(x3) + ';' +
                    '--fa-bubble-y1:' + px(-rise * 0.3) + ';' +
                    '--fa-bubble-y2:' + px(-rise * 0.65) + ';' +
                    '--fa-bubble-y3:' + px(-rise) + ';' +
                    '--fa-bubble-dur:' + dur + ';';

                b.addEventListener('animationend', function onEnd() {
                    b.removeEventListener('animationend', onEnd);
                    if (b.parentNode) b.parentNode.removeChild(b);
                });
                return b;
            }

            function spawn(x, y) {
                const count = 6 + Math.floor(Math.random() * 5); /* 6–10 */
                prune(count);

                const frag = document.createDocumentFragment();
                for (let i = 0; i < count; i++) {
                    frag.appendChild(makeBubble(x, y));
                }
                /* Layer in cima al body: sempre sopra, mai clipato */
                if (layer.parentNode !== document.body) {
                    document.body.appendChild(layer);
                } else if (layer !== document.body.lastElementChild) {
                    document.body.appendChild(layer);
                }
                layer.appendChild(frag);
            }

            document.addEventListener('click', function(e) {
                if (typeof e.clientX !== 'number' || typeof e.clientY !== 'number') return;
                spawn(e.clientX, e.clientY);
            }, { capture: true, passive: true });
        },

        /* ============================
           Hero offers — loop infinito, una card alla volta
           ============================ */
        heroOffers() {
            const root = document.querySelector('[data-fa-hero-offers]');
            if (!root) return;
            const track = root.querySelector('.fa-hero-offers__track');
            const prevBtn = root.querySelector('[data-fa-offers-prev]');
            const nextBtn = root.querySelector('[data-fa-offers-next]');
            const dotsWrap = root.querySelector('[data-fa-offers-dots]');
            if (!track || track.children.length < 2) return;

            const reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const intervalMs = 3500;
            const durationMs = 680;
            const ease = 'cubic-bezier(0.22, 1, 0.36, 1)';
            const viewport = root.querySelector('.fa-hero-offers__viewport');
            let timer = null;
            let busy = false;
            let hovering = false;
            const originalCount = track.children.length;

            function measure() {
                if (!viewport) return;
                root.style.setProperty('--fa-offers-vp', viewport.clientWidth + 'px');
            }

            function stepPx() {
                const first = track.children[0];
                if (!first) return 0;
                const styles = window.getComputedStyle(track);
                const gap = parseFloat(styles.columnGap || styles.gap) || 12;
                return first.getBoundingClientRect().width + gap;
            }

            function currentIndex() {
                const first = track.querySelector('.fa-hero-offer');
                return first ? parseInt(first.getAttribute('data-index') || '0', 10) : 0;
            }

            function syncDots() {
                if (!dotsWrap) return;
                const idx = currentIndex();
                const dots = dotsWrap.querySelectorAll('.fa-hero-offers__dot');
                dots.forEach(function(dot, i) {
                    dot.classList.toggle('is-active', i === idx);
                    if (i === idx) {
                        dot.setAttribute('aria-current', 'true');
                    } else {
                        dot.removeAttribute('aria-current');
                    }
                });
            }

            function renderDots() {
                if (!dotsWrap) return;
                dotsWrap.innerHTML = '';
                for (let i = 0; i < originalCount; i++) {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.className = 'fa-hero-offers__dot' + (i === 0 ? ' is-active' : '');
                    b.setAttribute('aria-label', String(i + 1));
                    if (i === 0) {
                        b.setAttribute('aria-current', 'true');
                    }
                    b.addEventListener('click', function() {
                        goTo(i);
                    });
                    dotsWrap.appendChild(b);
                }
            }

            function next() {
                if (busy) return;
                const delta = stepPx();
                if (delta <= 0) return;
                busy = true;
                track.style.transition = 'transform ' + durationMs + 'ms ' + ease;
                track.style.transform = 'translate3d(' + (-delta) + 'px,0,0)';
                window.setTimeout(function() {
                    track.style.transition = 'none';
                    track.appendChild(track.firstElementChild);
                    track.style.transform = 'translate3d(0,0,0)';
                    void track.offsetWidth;
                    busy = false;
                    syncDots();
                }, durationMs + 16);
            }

            function prev() {
                if (busy) return;
                const delta = stepPx();
                if (delta <= 0) return;
                busy = true;
                track.insertBefore(track.lastElementChild, track.firstElementChild);
                track.style.transition = 'none';
                track.style.transform = 'translate3d(' + (-delta) + 'px,0,0)';
                void track.offsetWidth;
                track.style.transition = 'transform ' + durationMs + 'ms ' + ease;
                track.style.transform = 'translate3d(0,0,0)';
                window.setTimeout(function() {
                    busy = false;
                    syncDots();
                }, durationMs + 16);
            }

            function goTo(index) {
                if (busy) return;
                let guard = 0;
                while (currentIndex() !== index && guard < originalCount) {
                    track.appendChild(track.firstElementChild);
                    guard++;
                }
                track.style.transition = 'none';
                track.style.transform = 'translate3d(0,0,0)';
                syncDots();
            }

            function stop() {
                if (timer) {
                    window.clearInterval(timer);
                    timer = null;
                }
            }

            function start() {
                stop();
                if (reduce || hovering || document.hidden) return;
                timer = window.setInterval(next, intervalMs);
            }

            root.addEventListener('mouseenter', function() {
                hovering = true;
                stop();
            });
            root.addEventListener('mouseleave', function() {
                hovering = false;
                start();
            });
            root.addEventListener('focusin', function() {
                hovering = true;
                stop();
            });
            root.addEventListener('focusout', function(e) {
                if (root.contains(e.relatedTarget)) return;
                hovering = false;
                start();
            });
            document.addEventListener('visibilitychange', function() {
                if (document.hidden) stop();
                else start();
            });
            if (nextBtn) nextBtn.addEventListener('click', function() {
                next();
                start();
            });
            if (prevBtn) prevBtn.addEventListener('click', function() {
                prev();
                start();
            });
            window.addEventListener('resize', function() {
                measure();
                track.style.transition = 'none';
                track.style.transform = 'translate3d(0,0,0)';
            });

            measure();
            renderDots();
            start();
        },

    };

    $(document).ready(function() {
        FA.init();
    });

})(jQuery);
