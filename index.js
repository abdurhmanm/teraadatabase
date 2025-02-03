const imagesContainer = document.querySelector('.carousel-container .images');
const prevBtn = document.querySelector('.prev-btn');
const nextBtn = document.querySelector('.next-btn');

let currentIndex = 0;

function updateCarousel() {
    const imageWidth = 300 + 20; // عرض الصورة + الهامش (10px لكل جانب)
    imagesContainer.style.transform = `translateX(${-currentIndex * imageWidth}px)`;
}

prevBtn.addEventListener('click', () => {
    if (currentIndex > 0) {
        currentIndex--;
        updateCarousel();
    }
});

nextBtn.addEventListener('click', () => {
    if (currentIndex < imagesContainer.children.length - 1) {
        currentIndex++;
        updateCarousel();
    }
});

