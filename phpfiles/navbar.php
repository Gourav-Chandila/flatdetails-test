<?php
// session_start();
// Enable error reporting and display errors
error_reporting(0);
ini_set('display_errors', 1);
// Log the session status
// error_log('Session Status: ' . session_status(), 0);

// Check if the user is logged in
if (isset($_SESSION['party_id'])) {
  $loggedin = true;
} elseif (isset($_SESSION['first_name']) || isset($_SESSION['last_name'])) {
  $loggedin = true;
} else {
  $loggedin = false;
}

// Logs the value of $loggedin
// error_log('Logged In: ' . var_export($loggedin, true), 0);
// error_log('Party id is : ' . $_SESSION['party_id'], 0);


// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']); //echecking current page name

// Show 'home' and 'register' button if the user is not logged in .
if (!$loggedin) {
  echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav ">
          <li class="nav-item">
          <a class="nav-link" href="/sysnomy/flatdetails-test/index.php">Login</a>  
          </li>';
  echo '<li class="nav-item">
          <a class="nav-link" href="/sysnomy/flatdetails-test/phpfiles/Register_page.php">Register</a> 
          </li>
        </ul>
      </div>
    </div>
  </nav>';
}


// Show logout button if the user is logged in and on allotteeDetails.php
if ($loggedin && $current_page == "allotteeDetails.php") {
  echo '<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <strong style="color: white; font-size: 25px;">Allottee details</strong>

      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item">
            <a class="nav-link" href="/sysnomy/flatdetails-test/phpfiles/allotteeDetails.php">
              Welcome: ' . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '
            </a>
          </li>';

  // Conditionally display the separator only on larger screens
  echo '<li class="nav-item d-none d-lg-block">
            <span class="nav-link">||</span>
          </li>';

  echo '<li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>';



}


?>