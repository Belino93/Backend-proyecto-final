<!DOCTYPE html>
<html>
<head>
    <title>Fixapp</title>
</head>
<body>
    <h1>{{ $mailData['title'] }}</h1>
  
    <p>Hi {{$mailData['user']->name}} {{$mailData['user']->surname}}, thank you for registering on Fixapp. You can start processing repairs.</p>
     
</body>
</html>