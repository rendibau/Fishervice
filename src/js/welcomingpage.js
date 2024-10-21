// Ambil elemen tombol
document.getElementById("loginButton").addEventListener("click", function() {
    // Redirect ke signin.html
    window.location.href = "signin.html";
});

document.getElementById("signupButton").addEventListener("click", function() {
    // Redirect ke signup.html
    window.location.href = "signup.html";
});

    // JavaScript untuk menambahkan kelas 'active' ke item sidebar yang diklik
    const menuItems = document.querySelectorAll('.sidebar ul li a');

    menuItems.forEach(item => {
        item.addEventListener('click', function() {
            // Hapus kelas 'active' dari semua item
            menuItems.forEach(link => link.classList.remove('active'));
            
            // Tambahkan kelas 'active' ke item yang diklik
            this.classList.add('active');
        });
    });

    // JavaScript untuk membuat product card menjadi clickable
    document.getElementById("productCard1").addEventListener("click", function() {
        window.location.href = "https://aquaculturemag.com/2023/05/29/indonesia-towards-a-world-aquaculture-giant/";
    });
    document.getElementById("productCard2").addEventListener("click", function() {
        window.location.href = "https://takterlihat.com/budidaya-ikan-nila-dan-gurame/";
    });
    document.getElementById("productCard3").addEventListener("click", function() {
        window.location.href = "https://www.mongabay.co.id/2021/11/22/meningkatkan-kesejahteraan-ekonomi-dari-perikanan-budi-daya/";
    });
    document.getElementById("productCard4").addEventListener("click", function() {
        window.location.href = "https://roadtobiofloc.com/biofloc-fish-culture-in-indonesia/";
    });
    document.getElementById("productCard5").addEventListener("click", function() {
        window.location.href = "https://en.antaranews.com/news/236661/indonesian-fishery-products-accepted-by-171-countries-official";
    });
    document.getElementById("productCard6").addEventListener("click", function() {
        window.location.href = "https://news.mongabay.com/2022/01/indonesia-aims-for-sustainable-fish-farming-with-aquaculture-villages/";
    });
    // JavaScript untuk menambahkan kelas 'active' ke item sidebar yang sesuai saat scroll
    document.addEventListener('scroll', function() {
        const sections = document.querySelectorAll('section');
        const menuItems = document.querySelectorAll('.sidebar ul li a');

        let currentSection = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop - 50;
            if (pageYOffset >= sectionTop) {
                currentSection = section.getAttribute('id');
            }
        });

        menuItems.forEach(item => {
            item.classList.remove('active');
            if (item.getAttribute('href') === '#' + currentSection) {
                item.classList.add('active');
            }
        });
    });