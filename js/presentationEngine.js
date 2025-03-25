class PresentationEngine {
    constructor() {
        this.slides = document.querySelectorAll('.slide');
        this.currentSlide = 0;
        this.touchStartX = 0;
        this.progressBar = document.getElementById('progressBar');
        this.counter = document.getElementById('slideCounter');
        
        this.init();
    }

    init() {
        this.setupControls();
        this.setupTouchControls();
        this.updateSlide(0);
    }

    setupControls() {
        document.getElementById('prevSlide').addEventListener('click', () => this.prevSlide());
        document.getElementById('nextSlide').addEventListener('click', () => this.nextSlide());
        
        document.addEventListener('keydown', (e) => {
            switch(e.key) {
                case 'ArrowRight':
                case 'Space':
                    this.nextSlide();
                    break;
                case 'ArrowLeft':
                    this.prevSlide();
                    break;
                case 'Home':
                    this.goToSlide(0);
                    break;
                case 'End':
                    this.goToSlide(this.slides.length - 1);
                    break;
            }
        });
    }

    setupTouchControls() {
        document.addEventListener('touchstart', (e) => {
            this.touchStartX = e.touches[0].clientX;
        });

        document.addEventListener('touchend', (e) => {
            const touchEndX = e.changedTouches[0].clientX;
            const diff = this.touchStartX - touchEndX;

            if (Math.abs(diff) > 50) {
                if (diff > 0) {
                    this.nextSlide();
                } else {
                    this.prevSlide();
                }
            }
        });
    }

    updateSlide(index) {
        this.slides.forEach(slide => {
            slide.classList.remove('active');
            slide.style.display = 'none';
        });

        this.slides[index].classList.add('active');
        this.slides[index].style.display = 'flex';
        
        this.updateProgress(index);
        this.updateCounter(index);
        this.announceSlideChange(index);
    }

    updateProgress(index) {
        this.progressBar.style.width = `${((index + 1) / this.slides.length) * 100}%`;
    }

    updateCounter(index) {
        this.counter.textContent = `${index + 1}/${this.slides.length}`;
    }

    announceSlideChange(index) {
        const title = this.slides[index].querySelector('h2')?.textContent || 'Slide';
        const announcement = `Slide ${index + 1} of ${this.slides.length}: ${title}`;
        
        // Add aria-live announcement for accessibility
        const announcer = document.getElementById('slide-announcer') || (() => {
            const div = document.createElement('div');
            div.id = 'slide-announcer';
            div.setAttribute('aria-live', 'polite');
            div.className = 'sr-only';
            document.body.appendChild(div);
            return div;
        })();
        
        announcer.textContent = announcement;
    }

    nextSlide() {
        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
        this.updateSlide(this.currentSlide);
    }

    prevSlide() {
        this.currentSlide = (this.currentSlide - 1 + this.slides.length) % this.slides.length;
        this.updateSlide(this.currentSlide);
    }

    goToSlide(index) {
        if (index >= 0 && index < this.slides.length) {
            this.currentSlide = index;
            this.updateSlide(this.currentSlide);
        }
    }
}
