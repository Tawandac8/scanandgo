<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Self-Service Badge Printing</title>
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- CSS Files (Bootstrap for layout and spinner) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    
    <style>
        body, html {
            height: 100%;
            margin: 0;
            background-color: #f8f9fa;
        }
        .main-container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            padding: 20px;
        }
        .scan-box {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .scan-icon {
            font-size: 60px;
            color: #cb0c9f;
            margin-bottom: 20px;
        }
        .scan-input {
            width: 100%;
            padding: 15px;
            font-size: 24px;
            text-align: center;
            border: 2px solid #e9ecef;
            border-radius: 10px;
            margin-bottom: 20px;
            transition: border-color 0.3s ease;
        }
        .scan-input:focus {
            outline: none;
            border-color: #cb0c9f;
        }
        .loader {
            display: none;
            margin: 20px auto;
        }
        .message-box {
            margin-top: 20px;
            font-size: 18px;
            font-weight: bold;
            display: none;
        }
        .success-text { color: #82d616; }
        .error-text { color: #ea0606; }

        /* Print Styles */
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            @page { margin: 0; }
        }
    </style>
</head>
<body>

    <div class="main-container">
        <div class="scan-box">
            <i class="fas fa-qrcode scan-icon"></i>
            <h2>Welcome!</h2>
            <p class="text-muted mb-4">Please scan your QR code or type your Registration Code below to print your badge.</p>
            
            <form id="scan-form" autocomplete="off">
                <input type="text" id="reg_code" class="scan-input" placeholder="Registration Code..." autofocus required>
            </form>

            <div class="spinner-border text-primary loader" role="status" id="loading-spinner">
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
                            messageBox.html('<i class="fas fa-check-circle"></i> Badge found! Printing...').addClass('success-text').show();
                            
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
                            messageBox.html('<i class="fas fa-times-circle"></i> ' + response.message).addClass('error-text').show();
                            
                            // Reset quickly on error
                            setTimeout(function() {
                                resetScanner();
                            }, 3000);
                        }
                    },
                    error: function() {
                        spinner.hide();
                        messageBox.html('<i class="fas fa-exclamation-triangle"></i> Network error. Please try again.').addClass('error-text').show();
                        
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
