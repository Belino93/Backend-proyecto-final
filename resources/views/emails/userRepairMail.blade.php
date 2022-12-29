<!DOCTYPE html>
<html>
<head>
    <title>Fixapp</title>
</head>
<body>
    <h1>{{ $mailData['title'] }}</h1>
  
    <p>You have created the repair successfully</p>
    <h3>{{$mailData['userRepair'][0]->type}}, {{$mailData['userRepair'][0]->brand}} {{$mailData['userRepair'][0]->model}}</h3>

    <p>Imei: <b>{{$mailData['userRepair'][0]->imei}}</b></p>
    <p>Description: {{$mailData['userRepair'][0]->description}}</p>

    <b>State: {{$mailData['userRepair'][0]->name}}, we'll pick up your device</b>
    
</body>
</html>