<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Recovery</title>
</head>
<body style="font-family: sans-serif; margin:0; overflow-x: hidden;">
    <div style="display: block; margin: auto; width: 100vw; max-width: 600px;">

        <h1 style="font-size: 20px; font-weight: bold; margin-top: 20px; text-align: center;">UniLinks Password Recovery</h1>
        
        <p style="font-size: 18px; text-align: center" > Hello {{$mailData['account_tag']}} 👋</p>
            
        <div style="display: flex; flex-direction:column; align-items:center">

            <p>Here is your recovery code.</p>
            
            <p id="code" style="color:rgb(241 245 249 / 1); background-color: rgb(75 75 79); padding: 10px; margin-bottom: 10px; text-align: center; max-width: 90vw; overflow-wrap: break-word;"> {{$mailData['recovery_code']}} </p>

        </div>
        
    </div>
</body>
</html>
