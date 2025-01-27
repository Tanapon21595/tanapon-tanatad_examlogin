const firebaseConfig = {
    apiKey: "AIzaSyDw3N4y7BOWTAg02Z2TOWPBaa-55JGGuwY",
    authDomain: "it-login-32690.firebaseapp.com",
    projectId: "it-login-32690",
    storageBucket: "it-login-32690.firebasestorage.app",
    messagingSenderId: "957477814193",
    appId: "1:957477814193:web:a856538845c386716bc790",
    measurementId: "G-HL2NRB3T2Y"
  }; 

// Initialize Firebase
firebase.initializeApp(firebaseConfig);

// Function to handle Google login
function googleLogin() {
    const provider = new firebase.auth.GoogleAuthProvider();
    firebase.auth().signInWithPopup(provider)
        .then((result) => {
            result.user.getIdToken().then((idToken) => {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/php-login/assets/js/login.php';

                const idTokenField = document.createElement('input');
                idTokenField.type = 'hidden';
                idTokenField.name = 'idTokenString';
                idTokenField.value = idToken;
                form.appendChild(idTokenField);

                document.body.appendChild(form);
                form.submit();
            });
        }).catch((error) => {
            console.error(error);
        });
}