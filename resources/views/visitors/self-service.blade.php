<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Self-Service Badge Printing</title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(-45deg, #FF5E3A, #FF2A6D, #FF7A00, #FF0055);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            overflow: hidden;
            color: #fff;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .main-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        /* Glassmorphism Card */
        .scan-box {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 15px 35px rgba(0,0,0,0.2), inset 0 0 0 1px rgba(255,255,255,0.1);
            padding: 60px 50px;
            border-radius: 30px;
            text-align: center;
            max-width: 600px;
            width: 90%;
            position: relative;
        }

        .scan-box h2 {
            font-size: 3.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 0 4px 10px rgba(0,0,0,0.1);
            letter-spacing: -1px;
        }

        .scan-box p {
            font-size: 1.25rem;
            font-weight: 300;
            opacity: 0.9;
            margin-bottom: 50px;
        }

        /* Animated Icon */
        .icon-wrapper {
            position: relative;
            width: 140px;
            height: 140px;
            margin: 0 auto 40px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .scan-icon {
            font-size: 60px;
            color: #ffffff;
            z-index: 2;
        }

        .ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            border: 2px solid rgba(255, 255, 255, 0.8);
            animation: ripple 2s infinite cubic-bezier(0.65, 0, 0.34, 1);
        }
        .ring:nth-child(2) { animation-delay: 0.6s; }
        .ring:nth-child(3) { animation-delay: 1.2s; border-color: rgba(255,255,255,0.4); }

        @keyframes ripple {
            0% { transform: scale(0.6); opacity: 1; border-width: 4px; }
            100% { transform: scale(1.6); opacity: 0; border-width: 1px; }
        }

        /* Input Field */
        .scan-input {
            width: 100%;
            padding: 22px;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            border: 2px solid rgba(255,255,255,0.2);
            background: rgba(255, 255, 255, 0.1);
            color: #fff;
            border-radius: 20px;
            margin-bottom: 20px;
            box-shadow: inset 0 4px 10px rgba(0,0,0,0.1);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            letter-spacing: 3px;
        }
        .scan-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
            font-weight: 400;
            letter-spacing: 1px;
        }
        .scan-input:focus {
            outline: none;
            background: rgba(255, 255, 255, 0.25);
            border-color: rgba(255,255,255,0.8);
            box-shadow: 0 0 25px rgba(255,255,255,0.3), inset 0 4px 10px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }

        /* Status Messages */
        .message-box {
            margin-top: 30px;
            font-size: 20px;
            font-weight: 600;
            display: none;
            padding: 15px 25px;
            border-radius: 50px;
            backdrop-filter: blur(10px);
            animation: slideUp 0.4s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .success-text { 
            background: rgba(46, 213, 115, 0.2);
            border: 1px solid rgba(46, 213, 115, 0.4);
            color: #e2ffe8;
            box-shadow: 0 5px 15px rgba(46, 213, 115, 0.2);
        }
        .error-text { 
            background: rgba(255, 71, 87, 0.2);
            border: 1px solid rgba(255, 71, 87, 0.4);
            color: #ffe2e2;
            box-shadow: 0 5px 15px rgba(255, 71, 87, 0.2);
        }

        .loader {
            display: none;
            margin: 30px auto;
            width: 3.5rem;
            height: 3.5rem;
            color: #fff;
            border-width: 4px;
        }

        /* Background Animated Particles */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            overflow: hidden;
            z-index: 0;
            padding: 0;
            margin: 0;
        }
        .particles li {
            position: absolute;
            display: block;
            list-style: none;
            background: rgba(255, 255, 255, 0.15);
            animation: floatUp 25s linear infinite;
            bottom: -200px;
            border-radius: 50%;
            backdrop-filter: blur(5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .particles li:nth-child(1) { left: 25%; width: 80px; height: 80px; animation-delay: 0s; }
        .particles li:nth-child(2) { left: 10%; width: 25px; height: 25px; animation-delay: 2s; animation-duration: 12s; }
        .particles li:nth-child(3) { left: 70%; width: 35px; height: 35px; animation-delay: 4s; }
        .particles li:nth-child(4) { left: 40%; width: 60px; height: 60px; animation-delay: 0s; animation-duration: 18s; }
        .particles li:nth-child(5) { left: 65%; width: 30px; height: 30px; animation-delay: 0s; }
        .particles li:nth-child(6) { left: 75%; width: 110px; height: 110px; animation-delay: 3s; }
        .particles li:nth-child(7) { left: 35%; width: 150px; height: 150px; animation-delay: 7s; }
        .particles li:nth-child(8) { left: 50%; width: 25px; height: 25px; animation-delay: 15s; animation-duration: 45s; }
        .particles li:nth-child(9) { left: 20%; width: 15px; height: 15px; animation-delay: 2s; animation-duration: 35s; }
        .particles li:nth-child(10) { left: 85%; width: 150px; height: 150px; animation-delay: 0s; animation-duration: 11s; }

        @keyframes floatUp {
            0% { transform: translateY(0) rotate(0deg) scale(1); opacity: 1; }
            100% { transform: translateY(-1200px) rotate(360deg) scale(1.5); opacity: 0; }
        }

        /* Print Styles */
        @media print {
            body { 
                background: none !important; 
                animation: none !important;
                color: #000;
            }
            .particles, .main-container {
                display: none !important;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                display: block !important;
            }
            @page { margin: 0; }
        }
    </style>
</head>
<body>

    <!-- Motion Background -->
    <ul class="particles">
        <li></li><li></li><li></li><li></li><li></li>
        <li></li><li></li><li></li><li></li><li></li>
    </ul>

    <div class="main-container">
        <div class="scan-box">
            
            <div class="icon-wrapper">
                <div class="ring"></div>
                <div class="ring"></div>
                <div class="ring"></div>
                <i class="fas fa-qrcode scan-icon"></i>
            </div>

            <h2>Check In</h2>
            <p>Scan your QR Code or enter your Registration Code below to instantly print your badge.</p>
            
            <form id="scan-form" autocomplete="off" autofocus>
                <input type="text" id="reg_code" class="scan-input" style="opacity:0" placeholder="Registration Code..." autofocus required>
            </form>

            <div class="spinner-border loader" role="status" id="loading-spinner">
                <span class="sr-only">Loading...</span>
            </div>

            <div id="message" class="message-box"></div>
        </div>
    </div>

    <!-- Hidden area for printing the badge -->
    <div id="print-area" style="display: none;"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            const inputField = $('#reg_code');
            const form = $('#scan-form');
            const spinner = $('#loading-spinner');
            const messageBox = $('#message');
            const printArea = $('#print-area');

            // Keep focus on input for scanners
            $('body').on('click', function() {
                inputField.focus();
            });

            form.on('submit', function(e) {
                e.preventDefault();
                
                const regCode = inputField.val().trim();
                if (!regCode) return;

                // UI Loading state
                inputField.prop('disabled', true);
                spinner.show();
                messageBox.hide().removeClass('success-text error-text');

                $.ajax({
                    url: '{{ route("self-service.scan") }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        reg_code: regCode
                    },
                    success: function(response) {
                        spinner.hide();
                        
                        if (response.success) {
                            // Show success message
                            messageBox.html('<i class="fas fa-check-circle me-2"></i> Hi '+response.name+', your badge is printing...').addClass('success-text').show();
                            
                            // Inject HTML to print area and print
                            printArea.html(response.html).show();
                            
                            // Trigger print
                            setTimeout(function() {
                                window.print();
                                
                                // Reset after printing
                                setTimeout(function() {
                                    resetScanner();
                                }, 3000); // Wait 3 seconds before resetting screen for next user
                                
                            }, 500);

                        } else {
                            // Show error message
                            messageBox.html('<i class="fas fa-times-circle me-2"></i> ' + response.message).addClass('error-text').show();
                            
                            // Reset quickly on error
                            setTimeout(function() {
                                resetScanner();
                            }, 3000);
                        }
                    },
                    error: function() {
                        spinner.hide();
                        messageBox.html('<i class="fas fa-exclamation-triangle me-2"></i> Network error. Please try again.').addClass('error-text').show();
                        
                        setTimeout(function() {
                            resetScanner();
                        }, 3000);
                    }
                });
            });

            function resetScanner() {
                inputField.val('').prop('disabled', false).focus();
                messageBox.hide();
                printArea.hide().empty();
            }
        });

        // Automatically refocus if we lose focus (e.g. windows prompt pops up)
        $(window).on('focus', function() {
            $('#reg_code').focus();
        });
    </script>
</body>
</html>
