<?php
session_start();
include('./db.php');
$alertMessage = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

   

    // Query to fetch user
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $hashedPassword);
        $stmt->fetch();

        // Verify password
        if (md5($password) === $hashedPassword) {
            $_SESSION['id'] = $id;
            header("Location: index.php"); // Redirect to a secure page
            exit();
        } else {
            $alertMessage = 'Incorrect password!';
        }
    } else {
        $alertMessage = 'Email not registered!';
    }

    $stmt->close();
    $conn->close();
}
?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.css"
        rel="stylesheet">
    <link
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        rel="stylesheet">
    <title>Case-Management</title>
</head>
<body>
    <section class="vh-100" style="background-color: #508bfc;">
        <div class="container py-5 h-100">
            <div
                class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">

                            <h3 class="mb-5">Sign in case-Management</h3>
                            <?php if (!empty($alertMessage)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= $alertMessage ?>
                                </div>
                            <?php endif; ?>
                            <form action="login.php" method="POST" enctype="multipart/form-data">
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="email" id="typeEmailX-2" name="email"
                                        class="form-control form-control-lg" required />
                                    <label class="form-label" for="typeEmailX-2">Email</label>
                                </div>

                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="password" id="typePasswordX-2" name="password"
                                        class="form-control form-control-lg" required />
                                    <label class="form-label" for="typePasswordX-2">Password</label>
                                </div>

                                <button data-mdb-button-init
                                    class="btn btn-primary btn-lg btn-block" type="submit">Login</button>

                                <hr class="my-4">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/6.4.0/mdb.min.js"></script>
</body>
</html>
