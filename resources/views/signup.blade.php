<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
        crossorigin="anonymous"}>
    <link href="style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f4f4;
        }

        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            margin-top: 10px;
            font-weight: 500;
        }

        .form-control {
            margin-bottom: 20px;
            border-radius: 5px;
            height: 42px;
            font-size: 14px;
        }

        .btn-primary {
            background-color: #1466C2;
            color: white;
            height: 42px;
            font-weight: bold;
        }

        .btn-primary:hover {
            background-color: #1466C2;
        }

        .custom-button-sign-up {
            width: 100%;
            height: 42px;
            margin-top: 20px;
            font-weight: bold;
        }

        .custom-link {
            color: #0079FF;
            cursor: pointer;
        }

        .custom-link:hover {
            text-decoration: underline;
        }
    </style>
    <title>iCLOP</title>
</head>

<body>
    <div class="container" style="margin-top: 50px; width: 500px;">
        <center>
        <img src="./images/logo.png" style="width: 10rem" height="100rem">
    </center>
        <p style="text-align: center; font-size: 30px; font-weight: bold; color: #1466C2; ">Create New Account</p>
        <form action="{{ route('post_signup') }}" method="post" onsubmit="return validateForm()">
            @csrf
            <label for="name" class="form-label">Name</label>
            <input class="form-control" list="datalistOptions" id="name" placeholder="Name" name="name">

            <label for="email" class="form-label">Email</label>
            <input class="form-control" list="datalistOptions" id="email" name="email" placeholder="Email"
                onblur="validateEmail()">

            <div id="emailAlert" style="color: red; margin-top: 5px;"></div>

            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" name="password" list="datalistOptions" id="password"
                placeholder="Password">

            <label for="confirmPassword" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="password_confirmation" list="datalistOptions"
                id="confirmPassword" placeholder="Confirm Password" onchange="validatePassword()">
            <div id="passwordMismatch" style="color: red; margin-top: 5px;"></div>

            <label for="teacherName" class="form-label">Role User</label>
            <select class="form-control" id="teacherName" name="role"  onchange="toggleInputField()">
                <option value="" disabled selected>Choose a Role</option>
                <option value="student">Student</option>
                <!--<option value="admin">Admin</option>-->
                <option value="teacher">Teacher</option>
            </select>


            <center>
            <button class="btn btn-primary" type="submit">Sign Up</button>
            <p style="text-align: center; margin-top: 12px; font-weight: 500">Already have an account? <span style="color: #0079FF;"><a href="/">Sign In</a></p>
        </center>
        </form>
        <script>
            function validatePassword() {
                var password = document.getElementById("password").value;
                var confirmPassword = document.getElementById("confirmPassword").value;

                if (password !== confirmPassword) {
                    document.getElementById("passwordMismatch").innerHTML = "Password tidak cocok";
                } else {
                    document.getElementById("passwordMismatch").innerHTML = "";
                }
            }

            function validateEmail() {
                var email = document.getElementById("email").value;
                var emailAlert = document.getElementById("emailAlert");

                if (!email.includes("@gmail.com")) {
                    emailAlert.innerHTML = "Invalid email";
                    document.getElementById("email").value = "";
                } else {
                    emailAlert.innerHTML = "";
                }
            }

            function validateForm() {
                var password = document.getElementById("password").value;
                var confirmPassword = document.getElementById("confirmPassword").value;

                if (password !== confirmPassword) {
                    document.getElementById("passwordMismatch").innerHTML = "Password tidak cocok";
                    return false;
                } else {
                    document.getElementById("passwordMismatch").innerHTML = "";
                    return true;
                }
            }
            function toggleInputField() {
                var selectedRole = document.getElementById('teacherName').value;
                var usernameField = document.getElementById('usernameField');
                if (selectedRole === 'student') {
                    usernameField.style.display = 'block'; // Show the input field
                } else {
                    usernameField.style.display = 'none'; // Hide the input field
                }
            }
        </script>
    </div>
</body>

</html>
