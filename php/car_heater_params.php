<?php
/* car_heater_params.php
 *
 * This file contains common parameters and function for the car heater php code
 * These parameters are dependent on the installation.
 *
 */

/* This function returns to the main php code
 */
function goback()
{
    header("Location: {$_SERVER['HTTP_REFERER']}");
    exit;
}

/* Flag to turn on/off debug printout */
$debugprint = "false"; /* Debug printout "true" or "false" */

/* Text size */
$textsizevw = "3";

/* Domotics address for the system handling the JSON API */
$domosysapi = "http://192.168.1.75:8080";

/* As the indexes depend on installation and configuration
 * order they have to be defined for each installation
 */

/* Device indexes */
$cWswitchIdx = "123";
$cWouttempIdx = "10";

/* User Variable indexes */
$cWReadyTimeIdx = "4";
$cWEnabledIdx = "5";
$cWTempStartIdx = "6";
$cWMinus5MinutesIdx = "7";
$cWStartTimeIdx = "10";
?>
