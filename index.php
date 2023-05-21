<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pinger</title>
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicons/favicon-16x16.png">
    <link rel="shortcut icon" href="favicons/favicon.ico" type="image/x-icon"> 
    <!-- Bootstrap CSS (CDN) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <!-- styling -->
    <style>
        .clear-btn {
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            cursor: pointer;
        }

        a {
            text-decoration: none;

        }
    </style>
</head>

<body>
    <!-- Header -->

    <header class="bg-light py-3">
        <div class="container">
            <div class="d-flex align-items-center">
                <img src="logo.png" alt="Logo" height="55px" class="me-2">
                <h1 class="h4 m-0">Pinger</h1>
                <nav class="ms-auto">
                    <a href="#" class="btn btn-outline-primary me-2">About Me</a>
                    <a href="#" class="btn btn-outline-dark">View Code on GitHUb</a>
                </nav>
            </div>

        </div>
    </header>


    <!-- Main Content -->
    <main class="py-5">
        <div class="container">
            <div class="text-center">
                <h1 class="display-4 mb-4">Welcome to Pinger</h1>
                <p class="lead">Ping any website or IP address to check its availability.</p>
            </div>
            <div class="row justify-content-center mt-5">
                <div class="col-md-6">
                    <form id="pingForm" class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="inputAddress" placeholder="Enter website or IP address" required>
                        </div>
                        <div id="errorMessage" class="text-danger"></div>
                    </form>
                    <div class="text-center">
                        <div id="pingButtons" class="mb-3">
                            <button type="button" class="btn btn-success btn-lg" id="startButton">Start</button>
                            <!-- <button type="button" class="btn btn-danger btn-lg" id="stopButton" onclick="stopPing()" disabled>Stop</button> -->
                        </div>
                    </div>
                    <div id="outputContainer" class="mt-3">

                    </div>

                </div>
            </div>




        </div>
    </main>



    <!-- Footer -->
    <footer class="text-center py-3">
        <div class="container">
            <p class="m-0">Pinger | A Easy way to Ping Websites</p>
            <p>Made by <a href="https://github.com/subhanraj" target="_blank">Subhan Raj</a></p>
        </div>
    </footer>

    <!-- Bootstrap JS (CDN) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous">
    </script>
    <!-- Jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Handle Start button click
            $("#startButton").click(function() {
                // Get the entered website or IP address
                var address = $("#inputAddress").val();

                // Validate input
                if (!validateInput(address)) {
                    $("#errorMessage").text("Invalid input. Please enter a valid website or IP address.");

                    // Add clear button and X icon
                    $("#inputAddress").addClass("invalid");
                    $("#inputAddress").next(".clear-btn").remove();
                    $("#inputAddress").after('<button class="clear-btn" onclick="clearInput()"><i class="bi bi-x"></i></button>');

                    // Remove loading icon
                    $("#loadingIcon").empty();

                    return;
                }

                // Clear error message and remove X icon
                $("#errorMessage").text("");
                $("#inputAddress").removeClass("invalid");
                $("#inputAddress").next(".clear-btn").remove();

                // Display loading icon
                $("#outputContainer").html('<div id="loadingIcon" class="d-flex justify-content-center align-items-center"><div class="loading-icon"><img src="loader.gif" height="180px" width="180px" alt="Loading..."></div></div>');

                // Send an AJAX request to the server for pinging
                $.ajax({
                    url: "ping-handler.php", // Replace with the correct path to ping-handler.php
                    method: "POST",
                    data: {
                        address: address
                    },
                    beforeSend: function() {
                        // Show the loading icon
                        $("#outputContainer").find(".loading-icon").show();
                    },
                    success: function(response) {
                        if (response.error) {
                            // Display error message
                            $("#outputContainer").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">' + response.error + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        } else {
                            // Display ping result
                            $("#outputContainer").html('<div class="alert alert-success alert-dismissible fade show" role="alert">' + response.result + '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                        }
                    },
                    error: function() {
                        // Display an error message
                        $("#outputContainer").html('<div class="alert alert-danger alert-dismissible fade show" role="alert">Error occurred while pinging.<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    },
                    complete: function() {
                        // Remove loading icon after request is complete
                        $("#outputContainer").find(".loading-icon").remove();
                    }
                });
            });

            // Handle Clear button click
            function clearInput() {
                $("#inputAddress").val("");
                $("#inputAddress").removeClass("invalid");
                $("#inputAddress").next(".clear-btn").remove();
                $("#errorMessage").text("");
                $("#outputContainer").empty();
            }

            // Input validation function
            function validateInput(input) {
                // Regular expression pattern for URL or IP address validation
                var pattern = /^(?:https?:\/\/)?(?:\w+\.)+\w+$|^(\d{1,3}\.){3}\d{1,3}$/;

                // Return true if input matches the pattern, false otherwise
                return pattern.test(input);
            }
        });
    </script>







</body>

</html>