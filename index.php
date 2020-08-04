<?php
    session_start();
    include 'functions.php';
    get_all_users();
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <link href="https://fonts.googleapis.com/css2?family=Raleway:wght@300;400;600&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="style.css">
        <title>Elementor</title>
    </head>
    <body>

        <main class="site-wrapper">

            <section class="section welcome-section">
                <?php if( user_logged_in() ) : ?>
                    <h1>Hello, <?php echo $_SESSION['user_email']; ?></h1>
                    <a href="/elementor/logout.php">Logout</a>
                <?php else: ?>
                    <h1>Hello, dear guest</h1>
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum</p>
                <?php endif; ?>

            </section>

            <?php if( ! user_logged_in() ) : ?>
                <section class="section form-section">

                    <form class="" action="login.php" method="post" onsubmit="validateForm()">

                        <div class="form-row">
                            <label>
                                <span>Email:</span>
                                <input type="email" name="user_email" id="user_email" value="" required>
                            </label>
                        </div>

                        <div class="form-row">
                            <label>
                                <span>Password:</span>
                                <input type="password" name="user_pass" id="user_pass" value="" required>
                            </label>
                        </div>

                        <p class="error" id="formErrors">Email and Password are required fields</p>

                        <div class="form-row">
                            <button type="submit" id="submitForm">Submit</button>
                        </div>

                    </form>

                </section>

            <?php else : ?>

                <section class="section users-section">
                    <?php if( $users = get_all_users() ) : ?>
                        <h3>Users list:</h3>
                        <ul>
                            <?php foreach( $users as $user ) : ?>
                                <li>
                                    <button <?php if( $user[0] == get_current_user_id() ) : ?>class="is-me"<?php endif; ?> onclick="loadUserData(<?php echo $user[0]; ?>)">
                                        <span class="email">
                                            <?php echo $user[1]; ?>
                                        </span>
                                        <span class="status <?php echo $user[4]; ?>">status: <?php echo $user[4]; ?></span>
                                    </button>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else : ?>
                    <?php endif; ?>
                </section>

            <?php endif; ?>

        </main>

        <script src="app.js" charset="utf-8"></script>
    </body>
</html>
