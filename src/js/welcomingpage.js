// Ambil elemen tombol
document.getElementById("loginButton").addEventListener("click", function() {
    // Redirect ke signin.html
    window.location.href = "signin.html";
});

document.getElementById("signupButton").addEventListener("click", function() {
    // Redirect ke signup.html
    window.location.href = "signup.html";
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
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.getElementById('toggleSidebar');
    const mainContent = document.querySelector('.main-content');

    toggleBtn.addEventListener('click', () => {
        // Toggle kelas 'active' pada sidebar
        sidebar.classList.toggle('active');

        if (window.matchMedia("(max-width: 750px)").matches) {
            // Jika layar ≤ 750px, hanya tampil & sembunyi tanpa menggeser konten
            if (sidebar.classList.contains('active')) {
                toggleBtn.style.left = '260px'; // Sesuaikan dengan lebar sidebar
            } else {
                toggleBtn.style.left = '10px';
            }
        } else {
            // Jika layar > 750px, gunakan logika geser seperti sebelumnya
            if (sidebar.classList.contains('active')) {
                toggleBtn.style.left = '270px';
                mainContent.style.marginLeft = '250px';
            } else {
                toggleBtn.style.left = '20px';
                mainContent.style.marginLeft = '0';
            }
        }
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