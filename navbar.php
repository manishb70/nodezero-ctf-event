<?php
// navbar.php

// Start the session to access session variables
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get the current page filename to determine the active link
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<nav class="bg-gray-800/50 backdrop-blur-sm border-b border-red-500/20 sticky top-0 z-50">
    <div class="container mx-auto max-w-6xl px-4 sm:px-6 md:px-8">
        <div class="flex items-center justify-between h-16">
            <div class="flex-shrink-0">
                <a href="./index.php" class="text-2xl font-bold text-red-500 font-mono">NodeZer0</a>
            </div>
            <div class="hidden md:block">
                <div class="ml-10 flex items-baseline space-x-4">
                    <a href="./index.php" class="<?php echo ($currentPage == 'index.php') ? 'text-red-500 bg-gray-700' : 'text-gray-300'; ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="./events.php" class="<?php echo ($currentPage == 'events.php') ? 'text-red-500 bg-gray-700' : 'text-gray-300'; ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Events</a>
                    <!-- <a href="./labs.php" class="<?php echo ($currentPage == 'labs.php') ? 'text-red-500 bg-gray-700' : 'text-gray-300'; ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Labs</a> -->
                    <a href="./leaderboard.php" class="<?php echo ($currentPage == 'leaderboard.php') ? 'text-red-500 bg-gray-700' : 'text-gray-300'; ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Leaderboard</a>
                    <?php if (isset($_SESSION['username'])): ?>
                        <a href="./profile.php" class="<?php echo ($currentPage == 'profile.php') ? 'text-red-500 bg-gray-700' : 'text-gray-300'; ?> hover:bg-gray-700 hover:text-white px-3 py-2 rounded-md text-sm font-medium">Profile</a>
                    <?php endif; ?>
                </div>
            </div>
            <div class="flex items-center">
                <?php if (isset($_SESSION['username'])): ?>
                    <!-- Show if user is logged in -->
                    <span class="text-white font-medium mr-4">
                        Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>
                    </span>
                    <a href="./logout.php" class="bg-gray-600 text-white font-bold py-2 px-5 rounded-md hover:bg-gray-700 transition-colors duration-300">
                        Logout
                    </a>
                <?php else: ?>
                    <!-- Show if user is not logged in -->
                    <a href="./login.php" class="bg-red-600 text-white font-bold py-2 px-5 rounded-md hover:bg-red-700 transition-colors duration-300">
                        Login
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
