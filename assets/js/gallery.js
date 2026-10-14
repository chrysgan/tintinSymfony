document.addEventListener('turbo:load', () => {
    const popup = document.getElementById('popup');

    if (!popup) {
        return;
    }

    const title = document.getElementById('modalTitle');
    const meta = document.getElementById('modalMeta');
    const description = document.getElementById('modalDescription');
    const image = document.getElementById('popup-image');

    const closeBtn = popup.querySelector('.popup-close');
    const prevBtn = popup.querySelector('.carousel-prev');
    const nextBtn = popup.querySelector('.carousel-next');

    let images = [];
    let currentIndex = 0;

    const closeModal = () => {
        popup.classList.remove('active');
        popup.setAttribute('aria-hidden', 'true');
    };

    const showImage = () => {
        image.src = images[currentIndex] || '/images/objet/000000.PNG';
        image.alt = title.textContent;
    };

    document.querySelectorAll('.gallery-card').forEach(card => {
        card.onclick = event => {
            if (event.target.closest('a')) {
                return;
            }

            // Les data-* sont portés par la zone image, pas par la carte
            const data = card.querySelector('.gallery-card__image').dataset;

            title.textContent = card.querySelector('h2').textContent;
            description.textContent = data.description;
            meta.textContent = [data.serie, data.annee].filter(Boolean).join(' - ');

            images = JSON.parse(data.images || '[]');
            currentIndex = 0;
            showImage();

            // Pas de flèches s'il n'y a rien à faire défiler
            prevBtn.classList.toggle('hidden', images.length <= 1);
            nextBtn.classList.toggle('hidden', images.length <= 1);

            popup.classList.add('active');
            popup.setAttribute('aria-hidden', 'false');
        };
    });

    nextBtn.onclick = () => {
        if (images.length === 0) return;
        currentIndex = (currentIndex + 1) % images.length;
        showImage();
    };

    prevBtn.onclick = () => {
        if (images.length === 0) return;
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        showImage();
    };

    closeBtn.onclick = closeModal;

    popup.onclick = event => {
        if (event.target === popup) {
            closeModal();
        }
    };
});