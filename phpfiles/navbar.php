<?php
session_start();
// Enable error reporting and display errors
ini_set('display_errors', 1);

// Log the session status
// error_log('Session Status: ' . session_status(), 0);

// Check if the user is logged in
if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
    $loggedin = true;
} else {
    $loggedin = false;
}

// Log the value of $loggedin
// error_log('Logged In: ' . var_export($loggedin, true), 0);

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Navigation bar
echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
                <!--<a class="nav-link" href="#">Home <span class="sr-only">(current)</span></a>-->
            </li>';

if (!$loggedin) {
    echo '<li class="nav-item">
    <a class="nav-link" href="/sysnomy/flatdetails-test/index.php">Login</a>  
    </li>
    <li class="nav-item">
    <a class="nav-link" href="/sysnomy/flatdetails-test/phpfiles/Register_page.php">Register</a> 
    </li>';
}


// Show logout button if the user is logged in and on allotteeDetails.php
if ($loggedin && $current_page == "allotteeDetails.php") {
    echo '<ul class="navbar-nav justify-content-end">
            <li class="nav-item">
                <a class="nav-link" href="logout.php">Logout</a>
            </li>
          </ul>';
}



echo '</div> 
</nav>';
?>