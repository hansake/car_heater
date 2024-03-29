-- dzVents script: "car_heater.lua" to switch on power to the car heater in advance depending on the outside temperature
--
-- dzVents Version: 2.4 or higher is needed
--
-- The following Domoticz user variables must be set:
--   carWarmTempStart: outside temperature below which the car heter will be started.
--   carWarmMinus5Minutes: number of minutes in advance the car heater will be started at minus 5 degrees.
-- The following Domoticz user variables are updated by the web user interface:
--   carWarmReadyTime: the expected time when the car will be warm.
--   carWarmEnabled: set to 1 when the timer function is enabled, 0 when disabled.
--

local DEBUGPRT = false -- true if debug print to log, else false

return {
    on = {
        timer = {"every 5 minutes"}
    },
    data = {
        carHeaterNotifySent = { initial = 0 }
    },
    execute = function(domoticz, item)
        -- First check that all parameters in user variables are set
        if domoticz.variables("carWarmTempStart") and domoticz.variables("carWarmMinus5Minutes") and domoticz.variables("carWarmReadyTime") and domoticz.variables("carWarmEnabled") then
            if (item.isTimer) then
                
                if (DEBUGPRT == true) then
                    print(string.format("Car heater timer enable: %d", domoticz.variables("carWarmEnabled").value))
                end
                if (domoticz.devices("Kupevärmare").state == "On") then
                    local carHeaterWatt = domoticz.devices("Kupevärme W").actualWatt
                    if (DEBUGPRT == true) then
                        print(string.format("Car heater is on, Watt: %d", carHeaterWatt))
                        print(string.format("carHeaterNotifySent: %d", domoticz.data.carHeaterNotifySent))
                    end
                    -- Do not send notify when current time is later than carWarmReadyTime, added 2021-11-14
                    if ((carHeaterWatt < 100) and (os.time() < domoticz.variables("carWarmReadyTime").value)) then
                        if (domoticz.data.carHeaterNotifySent == 0) then
                            domoticz.data.carHeaterNotifySent = 1
                            -- Send notification
                            domoticz.notify("Kupevarmare", "Kupevärme: kabel ej ansluten", domoticz.PRIORITY_NORMAL)
                            if (DEBUGPRT == true) then
                                print(string.format("Car heater notification sent"))
                            end
                        end
                    else
                        -- Do not send notify when cable is unplugged after car is warm
                        -- but switch is still on
                        domoticz.data.carHeaterNotifySent = 1
                    end
                else
                    domoticz.data.carHeaterNotifySent = 0
                end
                domoticz.variables("carWarmStartTime").set(0)
                if (domoticz.variables("carWarmEnabled").value == 1) then
                    local timeWarm = domoticz.variables("carWarmReadyTime").value
                    local timeNow = os.time()
                    local tempOutside = domoticz.devices("Utomhus norr").temperature
                    local tempStart = domoticz.variables("carWarmTempStart").value
                    local warmMinutes = domoticz.variables("carWarmMinus5Minutes").value
                    local heaterStartMinutes = math.floor(((tempOutside - tempStart) / (-5.0 - tempStart)) * warmMinutes)
                    local turnOnTime = 0
                    if (0 < heaterStartMinutes) then
                        turnOnTime = timeWarm - (heaterStartMinutes * 60)
                    end
                    local turnOffTime = timeWarm + (30 * 60)
                    domoticz.variables("carWarmStartTime").set(turnOnTime)
                    if (DEBUGPRT == true) then
                        print(string.format("Time when car warm: %d = %s", timeWarm, os.date("%Y-%m-%d %H:%M", timeWarm)))
                        print(string.format("Time now: %d = %s", timeNow, os.date("%Y-%m-%d %H:%M", timeNow)))
                        print(string.format("Temperature outside: %f", tempOutside))
                        print(string.format("Minutes to start heater before warm: %d", heaterStartMinutes))
                        print(string.format("Heater started at: %d = %s", turnOnTime, os.date("%Y-%m-%d %H:%M", turnOnTime)))
                        print(string.format("Heater stopped at: %d = %s", turnOffTime, os.date("%Y-%m-%d %H:%M", turnOffTime)))
                    end
                    if ((heaterStartMinutes <= 0) or (turnOffTime <= timeNow)) then -- if no heating needed or 30 minutes past warm time then stop heating
                        if (DEBUGPRT == true) then
                            print(string.format("Turn heater off (if on)"))
                        end
                        if (domoticz.devices("Kupevärmare").state == "On") then
                            domoticz.devices("Kupevärmare").switchOff() -- Car heater off
                            domoticz.variables("carWarmEnabled").set(0) -- Disable car heater timer
                        end
                    else
                        if (turnOnTime <= timeNow) then -- time to start heating
                            if (DEBUGPRT == true) then
                                print(string.format("Turn heater on (if off)"))
                            end
                            if (domoticz.devices("Kupevärmare").state == "Off") then
                                domoticz.devices("Kupevärmare").switchOn() -- Car heater on
                            end
                        end
                    end
                end
            end
        end
    end
}
