document.addEventListener('turbo:load', () => {
    
    const galleryCardsImages = document.querySelectorAll('.gallery-card__image');
    galleryCardsImages.forEach(image => {
        image.addEventListener('click', () => {
           popup.style.display = 'block';
        });
    });
    const popup = document.getElementById('popup');
 
    // popup.addEventListener('click', (event) => {
    //        popup.style.display = 'none';
    //     });
    
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

   images.forEach(image => {
        image.addEventListener('click', (event) => {
           popup.style.display = 'block';
        });
    });

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

            title.textContent = card.dataset.title;
            description.textContent = card.dataset.description;
            meta.textContent = `${card.dataset.serie} - ${card.dataset.annee}`;

            images = JSON.parse(card.dataset.images || '[]');
            currentIndex = 0;
            showImage();

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