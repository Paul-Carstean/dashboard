<?php

$sname= "localhost";
$unmae= "root";
$password = "";
$db_name = "dashboard_db";

$conn = mysqli_connect($sname, $unmae, $password, $db_name);

if (!$conn) {
	echo "Connection failed!";
}

function getData($table){
    $con=mysqli_connect("localhost", "root", "", "dashboard_db");
    $sql="SELECT * FROM $table";
    $result=mysqli_query($con,$sql);

    if($result){
        if(mysqli_num_rows($result)>0){
            return $result;
        }
    }
}

function getDataID($table, $id){
    $con=mysqli_connect("localhost", "root", "", "dashboard_db");
    $sql="SELECT * FROM $table WHERE idUtilizator=$id";
    $result=mysqli_query($con,$sql);

    if($result){
        if(mysqli_num_rows($result)>0){
            return $result;
        }
    }
}

function getDataComenzi($val,$id){
    if($val === 1){
        $con=mysqli_connect("localhost", "root", "", "dashboard_db");
        $sql="SELECT * FROM comenzi WHERE comFinalizata=$val AND idUtilizator=$id ORDER BY dataLimita DESC ";
        $result=mysqli_query($con,$sql);

        if($result){
            if(mysqli_num_rows($result)>0){
                return $result;
            }
        }
    }
    else{
        $con=mysqli_connect("localhost", "root", "", "dashboard_db");
        $sql="SELECT * FROM comenzi WHERE comFinalizata=$val AND idUtilizator=$id ORDER BY dataLimita ASC ";
        $result=mysqli_query($con,$sql);

        if($result){
            if(mysqli_num_rows($result)>0){
                return $result;
            }
        }
    }
}









