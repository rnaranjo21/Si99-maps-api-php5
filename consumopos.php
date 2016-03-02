<?php
session_start();

/*
$self = $_SERVER['PHP_SELF'] ;//Obtenemos la página en la que nos encontramos
header("refresh:60;url=$self");
*/
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="content-language" content="es">
     <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,300,700">
    <link type="text/css" rel="stylesheet" href="http://fonts.googleapis.com/css?family=Oswald:400,700,300">
    <link type="text/css" rel="stylesheet" href="styles/jquery-ui-1.10.4.custom.min.css">
    <link type="text/css" rel="stylesheet" href="styles/font-awesome.min.css">
    <link type="text/css" rel="stylesheet" href="styles/bootstrap.min.css">
    <link type="text/css" rel="stylesheet" href="styles/animate.css">
    <link type="text/css" rel="stylesheet" href="styles/all.css">
    <link type="text/css" rel="stylesheet" href="styles/main.css">
    <link type="text/css" rel="stylesheet" href="styles/style-responsive.css">
    <link type="text/css" rel="stylesheet" href="styles/zabuto_calendar.min.css">
    <link type="text/css" rel="stylesheet" href="styles/pace.css">
    <link type="text/css" rel="stylesheet" href="styles/jquery.news-ticker.css">
         <link href="css/Estilo.css" rel="stylesheet" type="text/css"/>  
          <link type="text/css" rel="stylesheet" href="js/sortable-0.8.0/css/sortable-theme-bootstrap.css">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


     <script type="text/javascript" src="js/ext/jquery-1.8.2.min.js"></script>


    <script type="text/javascript" src="js/script.js"></script>
       <script type="text/javascript" src="js/buscar-en-tabla.js"></script>
    
     <title>Si99</title>
</head>
<div id="header-topbar-option-demo" class="page-header-topbar">
            <nav id="topbar" role="navigation" style="margin-bottom: 0;" data-step="3" class="navbar navbar-default navbar-static-top">
    <div class="navbar-header">
           <a id="logo" href="#" class="navbar-brand"><span class="logo-text">Si99</span></a></div>
<div class="topbar-main"></a>
            <ul class="nav navbar navbar-top-links navbar-right mbn">
                             <li><a href="#" id="btnExport"> <img src="imagenes/reportes.png" >Exportar Archivo a Excel</a></li>
                            <li><a href="cerrar.php"> <img src="imagenes/logout.png" >Cerrar Sesión</a></li>
                        </ul>
                    </li>
  </div>    
      </div>  
         </div>           
          
 <br>
 <body>
     <div id="layer">
  <div id="layerc">
    <p>Descargando Información...</p>
    <div id="layerClock"></div>
  </div>
</div>
<script type="text/javascript">
$(document).ready(function(){
    $(".panel-heading").html(function(){
    // Una vez se ha cargado el archivo, escondemos el reloj
    $("#layer").hide();
  });
});
</script>
  <script type="text/javascript">
    $("#btnExport").click(function(e) {
        window.open('data:application/vnd.ms-excel,' + encodeURIComponent($('.panel-body').html()));
        e.preventDefault();
    });
    </script>
       <style type="text/css">
body { 
  background-image: url(imagenes/Fondo.jpg);
  height: 100%; 
  margin: 0; 
  padding: 0; 
  text-align: center;
}
#layerClock {
  margin:auto;
  width:31px;height:31px;
  
background-image: url('imagenes/loading.gif');
}
input[type="search"]{
      padding: 5px;
      background: url(search-white.png) no-repeat 10px 6px #fcfcfc;
    border: 1px solid #d1d1d1;
    font: bold 12px Arial,Helvetica,Sans-serif;
    color: #bebebe;
    padding: 6px 15px 6px 35px;
    -webkit-border-radius: 20px;
    -moz-border-radius: 20px;
    border-radius: 20px;
    text-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
    -webkit-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15) inset;
    -moz-box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15) inset;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15) inset;
    -webkit-transition: all 0.7s ease 0s;
    -moz-transition: all 0.7s ease 0s;
    -o-transition: all 0.7s ease 0s;
    transition: all 0.7s ease 0s;
    }    
.panel-body{
  height: 450px;
    overflow: scroll;
    position: relative;
    padding-bottom: 56.25%;
}
</style>
<div>
<?php

//llamado WSDL
$client =new SoapClient("https://api.fm-web.us/webservices/CoreWebSvc/CoreWS.asmx?WSDL",array( "trace" => 1 )); 
$client2 = new SoapClient("https://api.fm-web.us/webservices/PositioningWebSvc/PositioningWS.asmx?WSDL",array( "trace" => 1 ));
$client3=new SoapClient("https://api.fm-web.us/webservices/AssetDataWebSvc/VehicleProcessesWS.asmx?WSDL",array( "trace" => 1 ));
$client4=new SoapClient("https://api.fm-web.us/webservices/MappingWebSvc/LocationProcessesWS.asmx?WSDL",array("trace"=>1));


$params = array(
  "UserName" =>$_SESSION['Usuario'],
  "Password" =>$_SESSION['Password'],
  "ApplicationID" => "",
);
$response = $client->__soapCall('Login', array($params));
$token = $response->LoginResult->Token;
//validacion login
//Error VAlidacion Token
if($token==null)
{
echo '<script languaje="javascript">';
echo   'alert("Contraseña Incorrecta");';
echo 'location.href = "cerrar.php";';
echo '</script>';
}
 //Paramtros LOGIN
//hander a mantener secion activa token
$core = "http://www.omnibridge.com/SDKWebServices/Core";
$posicion="http://www.omnibridge.com/SDKWebServices/Positioning";
$vehiculos="http://www.omnibridge.com/SDKWebServices/AssetData";
$location="http://www.omnibridge.com/SDKWebServices/Mapping";
$authHeader=array("Token" => $token);
//headers token para cada funcion a llamar 
$header[] = new SoapHeader($core,'TokenHeader',$authHeader, false);
$salida=$client->__setSoapHeaders($header);

$header1[] = new SoapHeader($posicion,'TokenHeader',$authHeader, false);
$salida=$client2->__setSoapHeaders($header1);

$header2[] = new SoapHeader($vehiculos,'TokenHeader',$authHeader, false);
$salida=$client3->__setSoapHeaders($header2);

$header3[]=new SoapHeader($location,'TokenHeader',$authHeader, false);
$salida=$client4->__setSoapHeaders($header3);
//parametros de consulta wsdl
$paramsStorg= array("NewOrganisationID" => 626);
$result= $client->__soapCall('SetCurrentOrgID', array($paramsStorg));
//parametros vehiculos
$paramvehi=array("TokenHeader");
//llamado al services vehiculos
$orgVehi =$client3->__soapCall('GetVehiclesList',array($paramvehi));
//mostrar result en tablas 
$vehi= $orgVehi->GetVehiclesListResult->Vehicle;
?>

<div class="panel panel-blue" style="background:#FFF;float:left">

<div class="panel-heading">Tabla de ubicaciones buses</div>
<br>
<input type="search" id="txtBuscar" autofocus placeholder="Digite el texto">
<br>
 <div class="panel-body">
<table  id="tblTabla" class="sortable-theme-bootstrap" data-sortable >
<thead>
<tr>
<th>Bus</th>
<th>Localización</th>
<th>Ultima posición</th>
</tr>
</thead>
<?php
//parametros posiscion


include_once("php-google-map-api-master/releases/3.0/src/GoogleMap.php");
include_once("php-google-map-api-master/releases/3.0/src/JSMin.php");
$MAP_OBJECT = new GoogleMapAPI(); $MAP_OBJECT->_minify_js = isset($_REQUEST["min"])?FALSE:TRUE;
for($i=1;$i<count($vehi);$i++){
$vehiID=$orgVehi->GetVehiclesListResult->Vehicle[$i]->ID;
$vehiDes=$orgVehi->GetVehiclesListResult->Vehicle[$i]->Description;
$paramPosi= array("SpecificVehicleIDs"=>array("short"=>$vehiID));
$orgposi =$client2->__soapCall('GetLatestPositionPerVehicle',array($paramPosi));
//llamado al services posiciongps
$posi1=$orgposi->GetLatestPositionPerVehicleResult->GPSPosition->Latitude;
$posi2=$orgposi->GetLatestPositionPerVehicleResult->GPSPosition->Longitude;
//llamado localizacion 
$paramlocal= array("Longitude"=>$posi2,"Latitude"=>$posi1);
$orglocal =$client4->__soapCall('GetNearestLocation',array($paramlocal));
$positime=$orgposi->GetLatestPositionPerVehicleResult->GPSPosition->Time;
$date = new DateTime($positime);
$location=$orglocal->GetNearestLocationResult->OriginLongitude;
$location1=$orglocal->GetNearestLocationResult->OriginLatitude;
$location2=$orglocal->GetNearestLocationResult->LocationName;
$marker_id=$MAP_OBJECT->addMarkerByCoords($posi2,$posi1,$location2,$vehiDes,$vehiDes);
$marker_opener= "opener_".$marker_id;
$MAP_OBJECT->addMarkerOpener($marker_id, $marker_opener);

echo '<tbody>';                        
echo '<tr>';
echo '<td id='.$marker_opener.'>'; 
echo $vehiDes;
echo '</td>';
echo '<td>';
echo $location2;
echo '</td>';
echo '<td>';
echo $date->format('d/m/Y H:i:s');
echo '</td>';
echo '</tr>';
echo '</tbody>';


}
echo '</table>';
 echo '</div>';
echo '</div>';
 ?> 

   <div class="maps" style="float:Rigth">
  
  <?=$MAP_OBJECT->getHeaderJS();?>
<?=$MAP_OBJECT->getMapJS();?>
<?=$MAP_OBJECT->printOnLoad();?>
 <?=$MAP_OBJECT->printMap();?>

</div>
</div>
<br>

 
 <br>
 <div id="footer">
                    <div class="copyright">
                        <a href="http://www.syscaf.com/">2016 © Desarrollado Por IDI de SYSCAF S.A.S</a>
                          <h4>+57 (1) 746 6892  |  Calle 74A N° 23 - 10  |  info@syscaf.com.co  |  Bogotá - COLOMBIA </h4>
                      </div>
 </div>

   </body>
   