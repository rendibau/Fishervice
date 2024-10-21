  // Firebase configuration (isi dengan konfigurasi dari Firebase Console Anda)
  const firebaseConfig = {
    apiKey: "AIzaSyAbGSUAOW6UTOGlZa1RB5I_WGTVAH5yyHw",
    authDomain: "coba-35774.firebaseapp.com",
    projectId: "coba-35774",
    storageBucket: "coba-35774.appspot.com",
    messagingSenderId: "163958562627",
    appId: "1:163958562627:web:fcc939a487bc09de4a59f6"
};

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Initialize Firestore
const db = firebase.firestore();



// Mendapatkan data secara real-time dari Firestore
db.collection("khairiyahnisrina@gmail.com").doc("latestData")
  .onSnapshot((doc) => {
    if (doc.exists) {
        const data = doc.data();

        // Update nilai di HTML
        document.querySelector(".analyst-card:nth-child(1) p").textContent = `${data.tempC}°C`;
        document.querySelector(".analyst-card:nth-child(2) p").textContent = `${data.pHValue}`;
        document.querySelector(".analyst-card:nth-child(3) p").textContent = `${data.ntu} NTU`;
    } else {
        console.log("No such document!");
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
  