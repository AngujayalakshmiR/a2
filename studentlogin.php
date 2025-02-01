<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #2980b9;
        }
        .login-container {
            width: 100vw;
            height: 100vh;
            display: flex;
            flex-direction: row;
        }
        .left-section {
    flex: 1.5;
    position: relative;
    background: url('img/loginimg1.jpg') no-repeat center center;
    background-size: cover;
    opacity: .8;
    overflow: hidden; /* To make sure nothing goes outside the curved edges */
    border-top-right-radius: 50%;
    border-bottom-right-radius: 50%; /* Curve the bottom-left corner */
    background-color: #2980b9;
    border-right: 20px solid white;
}

.left-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: inherit; /* Inherit background of the left-section */
    border-top-right-radius: 20%;
    border-bottom-right-radius: 20%;
    z-index: -1; /* Make sure the pseudo-element stays behind the content */
    background-color: #2980b9;
}

        .right-section {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 50px;
            background-color: #2980b9;
        }
        .right-section h2 {
            font-size: 2rem;
            font-weight: bold;
        }
        .form-control {
            font-size: 1.2rem;
            padding: 12px;
        }
        .password-wrapper {
            position: relative;
            width: 100%;
        }
        .password-wrapper i {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 1.2rem;
            color: #333;
        }
        .btn-primary {
            background-color: #2980b9;
            color: white;
            border: 3px solid white;
            font-size: 1.2rem;
            padding: 8px;
            border-radius: 25px;
        }
        .btn-primary:hover {
            background-color: white;
            color: #2980b9;
            border: 3px solid white;
        }
        @media (max-width: 550px) {
            .login-container {
                flex-direction: column;
            }
            .left-section {
                flex: 0.3;
                height: 30%;
            }
            .right-section {
                height: 100%;
                padding: 30px;
            }
        }


        .circles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 1;
        }
        .circles li {
            position: absolute;
            display: block;
            list-style: none;
            width: 20px;
            height: 20px;
            background: rgba(255, 255, 255, 0.68);
            animation: animate 20s linear infinite;
            bottom: -150px;
        }
        .circles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .circles li:nth-child(2) { left: 10%; width: 20px; height: 20px; animation-delay: 2s; animation-duration: 12s; }
        .circles li:nth-child(3) { left: 70%; width: 20px; height: 20px; animation-delay: 4s; }
        .circles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .circles li:nth-child(5) { left: 65%; width: 20px; height: 20px; animation-delay: 0s; }
        .circles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .circles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .circles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .circles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .circles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }
        @keyframes animate {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; border-radius: 0; }
            100% { transform: translateY(-1000px) rotate(720deg); opacity: 0; border-radius: 50%; }
        }
        .transparent-img {
    position: absolute;
    width: 80px; 
    z-index: 2;
}

.top-img {
    top: 0px;
    width: 100%;
}

.bottom-img {
    bottom: 1px;
    width: 100%;
}

/* Mobile Responsiveness */
@media (max-width: 550px) {
    .transparent-img {
        width: 100%; /* Smaller on mobile */
    }
}

    </style>
</head>
<body>
    <div class="login-container">
    <div class="left-section d-none d-md-block">
            <ul class="circles">
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>
        </div>
        <div class="right-section position-relative">
    <!-- Top Transparent Image -->
    <img src="img/flower2transparent.png" class="transparent-img top-img" alt="Top Image">
    
    <h2 class="mb-4 text-center text-white" style="font-size: 45px;font-family:roboto;">Student Login</h2>
    
    <form style="width: 80%; max-width: 400px;">
        <div class="mb-3">
            <label for="username" class="form-label text-white">Student ID</label>
            <input type="text" class="form-control" id="username" placeholder="Enter Student ID" required>
        </div>
        <div class="mb-3 password-wrapper">
            <label for="password" class="form-label text-white">Password</label>
            <input type="password" class="form-control" id="password" placeholder="Enter password" required>
            <i class="fas fa-eye-slash" id="togglePassword" style="padding-top: 30px;"></i>
        </div><br>
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </form>

    <!-- Bottom Transparent Image -->
    <img src="img/flower2transparent.png" class="transparent-img bottom-img" alt="Bottom Image">
</div>

    </div>
    <script>
        document.getElementById("togglePassword").addEventListener("click", function() {
            let passwordField = document.getElementById("password");
            if (passwordField.type === "password") {
                passwordField.type = "text";
                this.classList.remove("fa-eye-slash");
                this.classList.add("fa-eye");
            } else {
                passwordField.type = "password";
                this.classList.remove("fa-eye");
                this.classList.add("fa-eye-slash");
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>