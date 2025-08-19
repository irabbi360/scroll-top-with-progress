const scrollButton = document.getElementById('scrollTopButton');
    const topDistance = 600;

    if (scrollButton) {
        window.addEventListener('scroll', () => {
            if (document.body.scrollTop > topDistance || document.documentElement.scrollTop > topDistance) {
                scrollButton.classList.add('scrolltop-show');
                scrollButton.classList.remove('scrolltop-hide');
            } else {
                scrollButton.classList.add('scrolltop-hide');
                scrollButton.classList.remove('scrolltop-show');
            }
        });

        scrollButton.addEventListener('click', () => {
            window.scrollTo({
                top: 0,
                left: 0,
                behavior: 'smooth',
            });
        });

        function updateScrollProgress() {
            const scrollY = window.scrollY;
            const windowHeight = window.innerHeight;
            const documentHeight = document.documentElement.scrollHeight;
            const scrollPercent = (scrollY / (documentHeight - windowHeight)) * 100;
            scrollButton.style.setProperty('--stwp-scroll-progress', `${scrollPercent}%`);
        }

        window.addEventListener('scroll', updateScrollProgress);
    }