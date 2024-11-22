document.addEventListener('DOMContentLoaded', () => {
    const carousel = document.querySelector('.video-carousel');
    const items = carousel.children;
    const totalItems = items.length / 3; // Apenas os itens "reais", sem os clones
    const itemWidth = items[0].offsetWidth + 20; // largura + espaçamento
    let currentIndex = 0;

    const moveCarousel = () => {
        currentIndex++;

        if (currentIndex === totalItems) {
            // Ao atingir o final do conteúdo original, reposicione sem animação
            carousel.style.transition = 'none';
            currentIndex = 0;
            carousel.style.transform = `translateX(0px)`;
            requestAnimationFrame(() => {
                setTimeout(() => {
                    carousel.style.transition = 'transform 1s ease-in-out';
                    moveCarousel(); // Continue o movimento
                }, 10);
            });
            return;
        }

        // Movimento normal
        const newPosition = -currentIndex * itemWidth;
        carousel.style.transform = `translateX(${newPosition}px)`;
    };

    // Iniciar o movimento automático
    const interval = setInterval(moveCarousel, 3000);

    // Pausar ao passar o mouse
    const container = document.querySelector('.video-carousel-container');
    container.addEventListener('mouseover', () => clearInterval(interval));
    container.addEventListener('mouseout', () => {
        setInterval(moveCarousel, 3000);
    });
});
