// MonsterGallery - GLightbox runner v4.5
// Handles two gallery types per instance:
//   GRID  — normal thumbnail grid with optional lightbox slideshow
//   HERO  — inline cycling slideshow within the thumbnail box

var MG_HERO_START_DELAY = 3000; // ms before hero slideshow begins after page load

document.addEventListener('DOMContentLoaded', function () {

    var grids = document.querySelectorAll('.monsterGallery-grid');

    grids.forEach(function (grid) {

        var galleryId      = grid.dataset.mgGalleryId;
        var layoutMode     = grid.dataset.mgLayout        || 'grid';
        var slideshowMode  = grid.dataset.mgSlideshow     || 'manual';
        var slideshowTime  = parseInt(grid.dataset.mgSlideshowTime || '4000', 10);
        var heroTransition = grid.dataset.mgHeroTransition || 'slide';
        var heroClick      = grid.dataset.mgHeroClick      || 'none';

        // ── HERO MODE ────────────────────────────────────────────────
        if (layoutMode === 'hero') {

            var imgs       = grid.querySelectorAll('img');
            var total      = imgs.length;
            if (total < 2) return; // nothing to cycle

            var current    = 0;
            var heroTimer  = null;

            function showSlide(next) {
                if (heroTransition === 'fade') {
                    imgs[current].style.opacity = '0';
                    imgs[next].style.opacity    = '1';
                } else {
                    // Slide: move current out left, bring next in from right
                    imgs[current].style.transition = 'transform 0.5s ease, opacity 0.5s ease';
                    imgs[next].style.transition    = 'transform 0.5s ease, opacity 0.5s ease';
                    imgs[next].style.transform     = 'translateX(100%)';
                    imgs[next].style.opacity       = '1';
                    // Force reflow so the initial transform is applied before animating
                    void imgs[next].offsetWidth;
                    imgs[current].style.transform  = 'translateX(-100%)';
                    imgs[next].style.transform     = 'translateX(0)';
                }
                current = next;
            }

            function nextHeroSlide() {
                var next = (current + 1) % total;
                showSlide(next);
            }

            // Initialise slide positions for slide transition
            if (heroTransition === 'slide') {
                imgs.forEach(function (img, i) {
                    img.style.transition = 'none';
                    img.style.transform  = i === 0 ? 'translateX(0)' : 'translateX(100%)';
                });
            }

            // Start after delay
            setTimeout(function () {
                heroTimer = setInterval(nextHeroSlide, slideshowTime);
            }, MG_HERO_START_DELAY);

            // Click to open lightbox if configured
            if (heroClick === 'lightbox') {
                var lightbox = GLightbox({
                    selector: '[data-gallery="' + galleryId + '"]',
                    touchNavigation: true,
                    loop: true,
                    autoplayVideos: true,
                });

                grid.addEventListener('click', function () {
                    lightbox.openAt(current);
                });
            }

        // ── GRID MODE ─────────────────────────────────────────────────
        } else {

            var lightbox = GLightbox({
                selector: '[data-gallery="' + galleryId + '"]',
                touchNavigation: true,
                loop: true,
                autoplayVideos: true,
            });

            if (slideshowMode === 'slideshow') {

                var timer = null;

                function startSlideshow() {
                    stopSlideshow();
                    timer = setInterval(function () {
                        lightbox.nextSlide();
                    }, slideshowTime);
                }

                function stopSlideshow() {
                    if (timer) { clearInterval(timer); timer = null; }
                }

                lightbox.on('open',  startSlideshow);
                lightbox.on('close', stopSlideshow);

                lightbox.on('slide_before_change', function () {
                    stopSlideshow();
                    startSlideshow();
                });
            }
        }
    });
});
