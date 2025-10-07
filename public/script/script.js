// public\script\script.js - CLEAN VERSION

// ===== NAVBAR CONTROLLER (SINGLE SOURCE OF TRUTH) =====
const NavbarController = {
    elements: {
        header: null,
        mobileMenuBtn: null,
        mobileMenu: null,
        hamburger: null,
        navLinks: null,
        logo: null,
        logoImg: null,
        searchInputs: null,
        searchContainers: null
    },

    state: {
        isMenuOpen: false,
        isInitialized: false,
        searchTimeout: null,
        activeSuggestions: null
    },

    // Initialize navbar
    init() {
        if (this.state.isInitialized) {
            console.warn('Navbar already initialized');
            return;
        }
        
        this.cacheElements();
        this.bindEvents();
        this.handleInitialState();
        this.initializeSearch();
        
        this.state.isInitialized = true;
        console.log('✅ Navbar initialized successfully');
    },

    // Cache DOM elements
    cacheElements() {
        this.elements.header = document.querySelector(".header");
        this.elements.mobileMenuBtn = document.getElementById("mobile-menu-btn");
        this.elements.mobileMenu = document.getElementById("mobile-menu");
        this.elements.hamburger = document.querySelector(".hamburger");
        this.elements.navLinks = document.querySelectorAll(".nav-link");
        this.elements.logo = document.querySelector(".logo");
        this.elements.logoImg = document.getElementById("logo-img");
        this.elements.searchInputs = document.querySelectorAll(".search-input, .mobile-search-input");
        this.elements.searchContainers = document.querySelectorAll(".search-container, .mobile-search-container");

        console.log('🔍 Elements cached:', {
            hamburger: !!this.elements.hamburger,
            mobileMenuBtn: !!this.elements.mobileMenuBtn,
            mobileMenu: !!this.elements.mobileMenu,
            searchInputs: this.elements.searchInputs.length
        });
    },

    // Bind all events
    bindEvents() {
        // Scroll event
        window.addEventListener("scroll", () => this.handleScroll());
        
        // Mobile menu toggle
        if (this.elements.mobileMenuBtn) {
            this.elements.mobileMenuBtn.addEventListener("click", (e) => {
                console.log('🍔 Hamburger clicked!');
                e.preventDefault();
                e.stopPropagation();
                this.toggleMobileMenu();
            });
        }

        // Close mobile menu on link clicks
        if (this.elements.mobileMenu) {
            const mobileLinks = this.elements.mobileMenu.querySelectorAll(".mobile-nav-link");
            mobileLinks.forEach(link => {
                link.addEventListener("click", () => {
                    this.closeMobileMenu();
                });
            });
        }

        // Close mobile menu on outside click
        document.addEventListener("click", (e) => {
            if (this.state.isMenuOpen && 
                this.elements.mobileMenu && 
                !this.elements.mobileMenu.contains(e.target) && 
                !this.elements.mobileMenuBtn.contains(e.target)) {
                this.closeMobileMenu();
            }
        });

        // Close mobile menu on escape key
        document.addEventListener("keydown", (e) => {
            if (e.key === "Escape") {
                if (this.state.isMenuOpen) {
                    this.closeMobileMenu();
                }
                this.closeSuggestions();
            }
        });

        // Close menu on window resize to desktop
        window.addEventListener("resize", () => {
            if (window.innerWidth >= 768 && this.state.isMenuOpen) {
                this.closeMobileMenu();
            }
        });
    },

    // Initialize search functionality
    initializeSearch() {
        this.elements.searchInputs.forEach((input, index) => {
            const container = this.elements.searchContainers[index];
            
            // Create suggestions dropdown
            const suggestionsEl = this.createSuggestionsElement();
            container.appendChild(suggestionsEl);

            // Bind search events
            input.addEventListener("focus", () => {
                input.style.transform = "scale(1.02)";
            });

            input.addEventListener("blur", (e) => {
                input.style.transform = "scale(1)";
                // Delay closing suggestions to allow clicks
                setTimeout(() => {
                    if (!suggestionsEl.matches(':hover')) {
                        this.closeSuggestions();
                    }
                }, 150);
            });

            // Handle input changes for suggestions
            input.addEventListener("input", (e) => {
                const query = e.target.value.trim();
                this.handleSearchInput(query, suggestionsEl);
            });

            // Handle enter key for search
            input.addEventListener("keypress", (e) => {
                if (e.key === "Enter") {
                    e.preventDefault();
                    const searchTerm = input.value.trim();
                    if (searchTerm) {
                        this.performSearch(searchTerm);
                    }
                }
            });

            // Handle arrow keys for suggestion navigation
            input.addEventListener("keydown", (e) => {
                this.handleSuggestionNavigation(e, suggestionsEl);
            });
        });
    },

    // Create suggestions dropdown element
    createSuggestionsElement() {
        const suggestions = document.createElement('div');
        suggestions.className = 'search-suggestions absolute top-full left-0 right-0 bg-white rounded-lg shadow-lg border mt-1 max-h-64 overflow-y-auto z-50 hidden';
        return suggestions;
    },

    // Handle search input changes
    handleSearchInput(query, suggestionsEl) {
        // Clear previous timeout
        if (this.state.searchTimeout) {
            clearTimeout(this.state.searchTimeout);
        }

        if (query.length < 2) {
            this.closeSuggestions();
            return;
        }

        // Debounce search requests
        this.state.searchTimeout = setTimeout(() => {
            this.fetchSuggestions(query, suggestionsEl);
        }, 300);
    },

    // Fetch search suggestions
    async fetchSuggestions(query, suggestionsEl) {
        try {
            const response = await fetch(`/search/suggestions?q=${encodeURIComponent(query)}`);
            if (!response.ok) throw new Error('Network response was not ok');
            
            const suggestions = await response.json();
            this.displaySuggestions(suggestions, suggestionsEl);
        } catch (error) {
            console.error('Error fetching suggestions:', error);
            this.closeSuggestions();
        }
    },

    // Display search suggestions
    displaySuggestions(suggestions, suggestionsEl) {
        if (!suggestions || suggestions.length === 0) {
            this.closeSuggestions();
            return;
        }

        let html = '';
        
        // Group suggestions by type
        const grouped = suggestions.reduce((acc, item) => {
            if (!acc[item.type]) acc[item.type] = [];
            acc[item.type].push(item);
            return acc;
        }, {});

        // Render grouped suggestions
        Object.entries(grouped).forEach(([type, items]) => {
            const typeLabel = type === 'artikel' ? 'Artikel' : 'Galeri';
            html += `<div class="p-2 text-xs font-semibold text-gray-500 uppercase border-b">${typeLabel}</div>`;
            
            items.forEach(item => {
                html += `
                    <a href="${item.url}" class="suggestion-item block p-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-6 h-6 mr-3">
                                ${type === 'artikel' ? 
                                    '<svg class="w-full h-full text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>' :
                                    '<svg class="w-full h-full text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>'
                                }
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">${item.title}</p>
                            </div>
                        </div>
                    </a>
                `;
            });
        });

        suggestionsEl.innerHTML = html;
        suggestionsEl.classList.remove('hidden');
        this.state.activeSuggestions = suggestionsEl;
    },

    // Handle suggestion navigation with arrow keys
    handleSuggestionNavigation(e, suggestionsEl) {
        const suggestions = suggestionsEl.querySelectorAll('.suggestion-item');
        if (suggestions.length === 0) return;

        let currentIndex = Array.from(suggestions).findIndex(item => 
            item.classList.contains('bg-gray-100')
        );

        switch (e.key) {
            case 'ArrowDown':
                e.preventDefault();
                currentIndex = currentIndex < suggestions.length - 1 ? currentIndex + 1 : 0;
                this.highlightSuggestion(suggestions, currentIndex);
                break;
            case 'ArrowUp':
                e.preventDefault();
                currentIndex = currentIndex > 0 ? currentIndex - 1 : suggestions.length - 1;
                this.highlightSuggestion(suggestions, currentIndex);
                break;
            case 'Enter':
                if (currentIndex >= 0 && suggestions[currentIndex]) {
                    e.preventDefault();
                    suggestions[currentIndex].click();
                }
                break;
        }
    },

    // Highlight suggestion item
    highlightSuggestion(suggestions, index) {
        suggestions.forEach((item, i) => {
            if (i === index) {
                item.classList.add('bg-gray-100');
            } else {
                item.classList.remove('bg-gray-100');
            }
        });
    },

    // Close suggestions dropdown
    closeSuggestions() {
        if (this.state.activeSuggestions) {
            this.state.activeSuggestions.classList.add('hidden');
            this.state.activeSuggestions = null;
        }
        
        // Clear highlights from all suggestion dropdowns
        document.querySelectorAll('.search-suggestions .suggestion-item').forEach(item => {
            item.classList.remove('bg-gray-100');
        });
    },

    // Perform search
    performSearch(query) {
        if (query.trim()) {
            window.location.href = `/search?q=${encodeURIComponent(query.trim())}`;
        }
    },

    // Handle initial state
    handleInitialState() {
        this.handleScroll();
    },

    // Handle scroll effects
    handleScroll() {
        if (!this.elements.header) return;

        const isInitiallySolid = this.elements.header.classList.contains('header-solid');

        if (isInitiallySolid) {
            this.setDarkTheme();
            return;
        }

        if (window.scrollY > 50) {
            this.elements.header.classList.add("scrolled");
            this.setDarkTheme();
        } else {
            this.elements.header.classList.remove("scrolled");
            this.setLightTheme();
        }
    },

    // Set dark theme colors
    setDarkTheme() {
        this.elements.navLinks.forEach(link => {
            link.style.color = "var(--primary-dark)";
        });
        
        if (this.elements.logo) {
            this.elements.logo.style.color = "var(--primary-dark)";
        }
        
        if (this.elements.hamburger) {
            this.elements.hamburger.querySelectorAll('span').forEach(span => {
                span.style.background = "var(--primary-dark)";
            });
        }
        
        if (this.elements.logoImg) {
            this.elements.logoImg.src = "/images/logofixfix-color.png";
        }
    },

    // Set light theme colors
    setLightTheme() {
        this.elements.navLinks.forEach(link => {
            link.style.color = "#fff";
        });
        
        if (this.elements.logo) {
            this.elements.logo.style.color = "#fff";
        }
        
        if (this.elements.hamburger) {
            this.elements.hamburger.querySelectorAll('span').forEach(span => {
                span.style.background = "#fff";
            });
        }
        
        if (this.elements.logoImg) {
            this.elements.logoImg.src = "/images/logofix.png";
        }
    },

    // Toggle mobile menu
    toggleMobileMenu() {
        if (this.state.isMenuOpen) {
            this.closeMobileMenu();
        } else {
            this.openMobileMenu();
        }
    },

    // Open mobile menu
    openMobileMenu() {
        if (!this.elements.mobileMenu || !this.elements.hamburger) return;
        
        this.elements.mobileMenu.classList.add("active");
        this.elements.hamburger.classList.add("active");
        document.body.style.overflow = "hidden";
        this.state.isMenuOpen = true;
    },

    // Close mobile menu
    closeMobileMenu() {
        if (!this.elements.mobileMenu || !this.elements.hamburger) return;
        
        this.elements.mobileMenu.classList.remove("active");
        this.elements.hamburger.classList.remove("active");
        document.body.style.overflow = "auto";
        this.state.isMenuOpen = false;
        this.closeSuggestions(); // Also close search suggestions
    }
};

// ===== UTILITY FUNCTIONS =====
function toggleClasses(el, remove, add) {
    el.classList.remove(remove);
    el.classList.add(add);
}

function initHorizontalSlider(sliderId, prevId, nextId) {
    const slider = document.getElementById(sliderId);
    const prevBtn = document.getElementById(prevId);
    const nextBtn = document.getElementById(nextId);

    if (!slider || !prevBtn || !nextBtn) return;

    const scrollAmount = 320;

    nextBtn.addEventListener("click", (e) => {
        e.preventDefault();
        slider.scrollBy({ left: scrollAmount, behavior: "smooth" });
    });

    prevBtn.addEventListener("click", (e) => {
        e.preventDefault();
        slider.scrollBy({ left: -scrollAmount, behavior: "smooth" });
    });

    function updateNavButtons() {
        const maxScroll = slider.scrollWidth - slider.clientWidth;
        prevBtn.style.display = slider.scrollLeft <= 5 ? "none" : "flex";
        nextBtn.style.display = slider.scrollLeft >= maxScroll - 5 ? "none" : "flex";
    }

    slider.addEventListener('scroll', updateNavButtons);
    updateNavButtons();
}

function initPageCarousel(carouselId, prevId, nextId) {
    const container = document.getElementById(carouselId);
    const prevBtn = document.getElementById(prevId);
    const nextBtn = document.getElementById(nextId);

    if (!container || !prevBtn || !nextBtn) return;

    const pages = parseInt(container.dataset.pages) || 1;
    let currentPage = 0;

    function updateCarousel() {
        container.style.transform = `translateX(-${currentPage * 100}%)`;
        prevBtn.style.display = currentPage === 0 ? 'none' : 'flex';
        nextBtn.style.display = currentPage >= pages - 1 ? 'none' : 'flex';
    }

    prevBtn.addEventListener('click', () => {
        if (currentPage > 0) {
            currentPage--;
            updateCarousel();
        }
    });

    nextBtn.addEventListener('click', () => {
        if (currentPage < pages - 1) {
            currentPage++;
            updateCarousel();
        }
    });

    if (pages <= 1) {
        prevBtn.style.display = 'none';
        nextBtn.style.display = 'none';
    }

    updateCarousel();
}

// ===== MAIN INITIALIZATION =====
document.addEventListener("DOMContentLoaded", function() {
    console.log('🚀 DOM Content Loaded - Starting initialization...');
    
    // Initialize navbar FIRST and ONLY
    NavbarController.init();
    
    ensureFullViewport();
    
    // Initialize other components
    initSejarahSlider();
    initVideoHandling();
    initSmoothScrolling();
    initScrollAnimations();

    initHorizontalSlider('keunikan-slider', 'keunikan-prev', 'keunikan-next');
    initHorizontalSlider('fasilitas-slider', 'fasilitas-prev', 'fasilitas-next');
    initHorizontalSlider('wisata-slider', 'wisata-prev', 'wisata-next');

    initPageCarousel('keunikan-carousel', 'keunikan-prev', 'keunikan-next');
    initPageCarousel('fasilitas-carousel', 'fasilitas-prev', 'fasilitas-next');
    initPageCarousel('wisata-carousel', 'wisata-prev', 'wisata-next');
    
    if (typeof initialGalleryData !== 'undefined') {
        initGallery(initialGalleryData); 
    }

    console.log('✅ All components initialized');
});

window.heroVideoUtils = {
    adjustVideoSize: function() {
        const video = document.querySelector(".hero-video-element");
        const heroSection = document.querySelector(".hero-video");
        
        if (video && heroSection) {
            video.style.width = '100vw';
            video.style.height = '100vh';
            video.style.minWidth = '100vw';
            video.style.minHeight = '100vh';
            video.style.objectFit = 'cover';
            video.style.objectPosition = 'center';
        }
    },
    
    ensureFullViewport: ensureFullViewport
};

// ===== STYLES =====
function addNavbarStyles() {
    const style = document.createElement("style");
    style.textContent = `
        .header {
            transition: all 0.3s ease-in-out;
        }
        
        .nav-link {
            transition: all 0.3s ease-in-out;
        }
        
        .mobile-menu {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .hamburger span {
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        
        .search-input,
        .mobile-search-input {
            transition: all 0.3s ease-in-out;
        }
        
        .logo,
        .nav-link,
        .hamburger span {
            transition: color 0.3s ease-in-out, background-color 0.3s ease-in-out;
        }
    `;
    document.head.appendChild(style);
}

addNavbarStyles();

function addSearchStyles() {
    const style = document.createElement("style");
    style.textContent = `
        .search-suggestions {
            animation: fadeInDown 0.2s ease-out;
        }
        
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .suggestion-item:hover {
            background-color: #f9fafb;
        }
        
        .search-container {
            position: relative;
        }
        
        .mobile-search-container {
            position: relative;
        }
        
        .search-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
        }
        
        .mobile-search-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.5);
        }
        
        .search-suggestions::-webkit-scrollbar {
            width: 4px;
        }
        
        .search-suggestions::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        
        .search-suggestions::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }
        
        .search-suggestions::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }
    `;
    document.head.appendChild(style);
}

addSearchStyles();

// ===== OTHER COMPONENT FUNCTIONS =====
function initSejarahSlider() {
    const container = document.getElementById("sejarah-images");
    const prevBtn = document.getElementById("sejarah-prev");
    const nextBtn = document.getElementById("sejarah-next");
    const dots = document.querySelectorAll(".sejarah-dot");
    
    if (!container || !prevBtn || !nextBtn) return;
    
    const slideCount = parseInt(container.dataset.count) || 4;
    let currentIndex = parseInt(container.dataset.current) || 0;

    function updateSlider() {
        const slideWidth = container.querySelector('.flex-shrink-0')?.offsetWidth || container.offsetWidth;
        const translateX = -currentIndex * slideWidth;
        
        container.style.transform = `translateX(${translateX}px)`;
        
        dots.forEach((dot, idx) => {
            if (dot) {
                dot.classList.toggle("bg-primary", idx === currentIndex);
                dot.classList.toggle("bg-gray-300", idx !== currentIndex);
            }
        });
        
        container.dataset.current = currentIndex.toString();
    }

    prevBtn.addEventListener("click", (e) => {
        e.preventDefault();
        currentIndex = (currentIndex - 1 + slideCount) % slideCount;
        updateSlider();
    });

    nextBtn.addEventListener("click", (e) => {
        e.preventDefault();
        currentIndex = (currentIndex + 1) % slideCount;
        updateSlider();
    });

    dots.forEach((dot) => {
        if (dot) {
            dot.addEventListener("click", (e) => {
                e.preventDefault();
                const slideIndex = parseInt(dot.dataset.slide);
                if (!isNaN(slideIndex)) {
                    currentIndex = slideIndex;
                    updateSlider();
                }
            });
        }
    });

    setInterval(() => {
        currentIndex = (currentIndex + 1) % slideCount;
        updateSlider();
    }, 5000);

    updateSlider();
}

function initVideoHandling() {
    const video = document.querySelector(".hero-video-element");
    const fallback = document.querySelector(".video-fallback");
    const heroSection = document.querySelector(".hero-video");

    if (video && fallback && heroSection) {
        // Enhanced video sizing function
        function adjustVideoSize() {
            const containerWidth = window.innerWidth;
            const containerHeight = window.innerHeight;
            const videoAspectRatio = 16 / 9; // Sesuaikan dengan aspect ratio video Anda
            const containerAspectRatio = containerWidth / containerHeight;

            // Force video to cover entire viewport
            video.style.width = '100vw';
            video.style.height = '100vh';
            video.style.minWidth = '100vw';
            video.style.minHeight = '100vh';
            video.style.objectFit = 'cover';
            video.style.objectPosition = 'center';

            // Additional sizing based on aspect ratio
            if (containerAspectRatio > videoAspectRatio) {
                // Container is wider than video - ensure width coverage
                video.style.width = '100vw';
                video.style.height = 'auto';
                video.style.minHeight = '100vh';
            } else {
                // Container is taller than video - ensure height coverage
                video.style.height = '100vh';
                video.style.width = 'auto';
                video.style.minWidth = '100vw';
            }

            // Ensure hero section matches viewport
            heroSection.style.width = '100vw';
            heroSection.style.minHeight = '100vh';
        }

        // Enhanced video loading with better error handling
        function loadVideo() {
            video.src = "/videos/landingpage-compressed.mp4";
            
            // Set initial properties
            video.muted = true;
            video.playsInline = true;
            video.loop = true;
            video.autoplay = true;
            
            adjustVideoSize();
            
            // Load the video
            video.load();
        }

        // Event listeners with improved handling
        video.addEventListener("error", function(e) {
            console.log("Video failed to load, showing fallback:", e);
            fallback.style.display = "block";
            video.style.display = "none";
        });

        video.addEventListener("loadeddata", function() {
            console.log("Video data loaded successfully");
            fallback.style.display = "none";
            video.style.display = "block";
            adjustVideoSize();
            
            // Try to play the video
            const playPromise = video.play();
            if (playPromise !== undefined) {
                playPromise
                    .then(() => {
                        console.log("Video playing successfully");
                    })
                    .catch(error => {
                        console.log("Video autoplay failed:", error);
                        // Show fallback if autoplay fails
                        fallback.style.display = "block";
                    });
            }
        });

        video.addEventListener("loadedmetadata", function() {
            console.log("Video metadata loaded");
            adjustVideoSize();
        });

        video.addEventListener("canplay", function() {
            console.log("Video can start playing");
            adjustVideoSize();
        });

        video.addEventListener("ended", function() {
            // Ensure seamless loop
            video.currentTime = 0;
            video.play().catch(e => console.log("Video replay failed:", e));
        });

        // Handle resize events
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(() => {
                adjustVideoSize();
            }, 100);
        });

        // Handle orientation change on mobile
        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                adjustVideoSize();
            }, 500);
        });

        // Initialize video loading
        loadVideo();

        // Fallback timeout - if video doesn't load within 10 seconds
        setTimeout(() => {
            if (video.readyState < 2) { // HAVE_CURRENT_DATA
                console.log("Video loading timeout, showing fallback");
                fallback.style.display = "block";
                video.style.display = "none";
            }
        }, 10000);
    }
}

function ensureFullViewport() {
    const heroVideo = document.querySelector('.hero-video');
    if (heroVideo) {
        // Force full viewport coverage
        heroVideo.style.width = '100vw';
        heroVideo.style.minHeight = '100vh';
        heroVideo.style.position = 'relative';
        heroVideo.style.left = '50%';
        heroVideo.style.right = '50%';
        heroVideo.style.marginLeft = '-50vw';
        heroVideo.style.marginRight = '-50vw';
    }
    
    // Prevent horizontal scroll
    document.body.style.overflowX = 'hidden';
}

function initSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start",
                });
            }
        });
    });
}

function initScrollAnimations() {
    function handleScrollAnimation() {
        const elements = document.querySelectorAll(".animate-fade-in");
        elements.forEach((element) => {
            const elementTop = element.getBoundingClientRect().top;
            const elementVisible = 150;

            if (elementTop < window.innerHeight - elementVisible) {
                element.classList.add("animate-fade-in");
            }
        });
    }

    window.addEventListener("scroll", handleScrollAnimation);
    handleScrollAnimation();
}

function addScrollbarStyles() {
    const style = document.createElement("style");
    style.textContent = `
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }
        
        .flex.transition-transform {
            transition: transform 0.5s ease-in-out;
        }
        
        .slider-nav-btn {
            transition: opacity 0.3s ease;
        }
        
        .slider-nav-btn:disabled {
            opacity: 0.3;
            cursor: not-allowed;
        }
    `;
    document.head.appendChild(style);
}

addScrollbarStyles();

function initGallery(initialData) {
    let galleryData = {};
    let currentPage = initialData.currentPage;
    let lastPage = initialData.lastPage;
    let isLoading = false;

    if (initialData.data) {
        Object.values(initialData.data).forEach(item => {
            galleryData[item.id] = item;
        });
    }

    const galleryGrid = document.getElementById('gallery-grid');
    const loadMoreBtn = document.getElementById('load-more');
    const imageModal = document.getElementById('image-modal');
    const modalContent = document.getElementById('modal-content');
    const modalCloseBtn = document.getElementById('modal-close');
    const modalBackdrop = document.getElementById('modal-backdrop');

    if (!galleryGrid || !loadMoreBtn || !imageModal) {
        console.error('Gallery elements not found');
        return;
    }

    if (currentPage >= lastPage) {
        loadMoreBtn.style.display = 'none';
    }

    window.openImageModal = function(itemId) {
        const item = galleryData[itemId];
        if (!item) {
            console.error('Item not found:', itemId);
            return;
        }

        modalContent.innerHTML = `
            <div class="relative w-[800px] h-[600px] shadow-2xl rounded-lg overflow-hidden">
                <img src="${item.gambar_url}" alt="${item.judul}" class="w-full h-full object-cover">
                <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-4">
                    <h3 class="text-lg font-bold">${item.judul}</h3>
                    ${item.deskripsi ? `<p class="text-sm mt-1">${item.deskripsi}</p>` : ''}
                </div>
            </div>
        `;
        
        imageModal.classList.remove('hidden');
        imageModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        setTimeout(() => {
            imageModal.style.opacity = '1';
        }, 10);
    };

    function closeImageModal() {
        imageModal.style.opacity = '0';
        
        setTimeout(() => {
            imageModal.classList.add('hidden');
            imageModal.classList.remove('flex');
            document.body.style.overflow = 'auto';
            modalContent.innerHTML = '';
        }, 300);
    }

    async function loadMoreItems() {
        if (isLoading || currentPage >= lastPage) return;
        
        isLoading = true;
        loadMoreBtn.disabled = true;
        loadMoreBtn.textContent = 'Memuat...';
        
        const nextPage = currentPage + 1;

        try {
            const response = await fetch(`/api/galeri-items?page=${nextPage}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            
            const newData = await response.json();

            newData.data.forEach(item => {
                galleryData[item.id] = item;
                const itemHtml = createGalleryItemHtml(item);
                galleryGrid.insertAdjacentHTML('beforeend', itemHtml);
            });

            currentPage = newData.current_page;
            lastPage = newData.last_page;
            
            if (currentPage >= lastPage) {
                loadMoreBtn.style.display = 'none';
            }
            
        } catch (error) {
            console.error('Error saat memuat item:', error);
            loadMoreBtn.textContent = 'Gagal Memuat. Coba Lagi.';
        } finally {
            isLoading = false;
            loadMoreBtn.disabled = false;
            if (currentPage < lastPage) {
                loadMoreBtn.textContent = 'Muat Lebih Banyak Foto';
            }
        }
    }

    function createGalleryItemHtml(item) {
        return `
            <div class="gallery-item bg-white rounded-2xl shadow-lg overflow-hidden card-hover">
                <div class="relative h-48 overflow-hidden group cursor-pointer" onclick="openImageModal(${item.id})">
                    <img src="${item.gambar_url}" alt="${item.judul}" class="w-full h-full object-cover group-hover:opacity-80 transition duration-300">
                </div>
                <div class="p-4">
                    <h4 class="font-bold text-primary mb-1 text-center truncate">${item.judul}</h4>
                </div>
            </div>
        `;
    }

    loadMoreBtn.addEventListener('click', loadMoreItems);
    modalCloseBtn.addEventListener('click', closeImageModal);
    
    modalBackdrop.addEventListener('click', (e) => {
        if (e.target === modalBackdrop) {
            closeImageModal();
        }
    });
    
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !imageModal.classList.contains('hidden')) {
            closeImageModal();
        }
    });
    
    imageModal.style.transition = 'opacity 0.3s ease-in-out';
    imageModal.style.opacity = '0';
    
    console.log('Gallery initialized with data:', galleryData);
}