<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Software Suspension Notice</title>
    <style>
       body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .container {
            width: 100%;
            max-width: 100%;
            margin: 20px;
            padding: 30px;
            overflow-y: auto;
            max-height: 90vh; /* For handling overflow gracefully */
            /* Other styles remain unchanged */
        }

        .header {
            margin-top: 20px;
            font-size: 26px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #d9534f;
            text-transform: uppercase;
        }
        .message {
            font-size: 16px;
            margin-bottom: 20px;
            line-height: 1.6;
            color: #555;
        }
        .image {
            width: 80%;
            height: auto;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .footer {
            font-size: 14px;
            color: #777;
            margin-top: 20px;
            text-align: center;
        }
        .button {
            display: inline-block;
            margin-top: 15px;
            padding: 12px 25px;
            color: #fff;
            background-color: #d9534f;
            text-decoration: none;
            border-radius: 8px;
            transition: background-color 0.3s ease;
            font-weight: bold;
        }
        .button:hover {
            background-color: #c9302c;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="container">
        {{-- <img src="{{ asset('images/suspension.jpg') }}" alt="Suspension Notice" class="image"> --}}
        <img alt="zisprink" width="130" src="https://zisprink.com/img/demos/business-consulting-3/Untitled-22.png" class="image" style="top: 0px; width: 130px;">
        <div class="message">
            {!! $message !!}
        </div>
        <div class="footer">
            <p>Your account has been temporarily suspended due to certain activities that violate our policies. We are here to help you resolve this issue as soon as possible.</p>
            <p>Please review the details and contact our support team if you need further assistance.</p>
            <a href="https://zisprink.com/" class="button">Get Support</a>

            <p>We apologize for any inconvenience caused. Our team is committed to resolving your issue promptly.</p>
            <p>Thank you for your understanding.</p>
        </div>
    </div>
</body>
</html>
