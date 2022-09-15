#############################################
# Maintain-Logs.ps1
# Log maintenance solution for Legacy
# EMR Data Viewer site
# Author(s): Sean Huggans
#############################################
$MaxLogSize = 512 # Keeping this low for now to avoid performance issues logging to a large file
foreach ($Log in $(Get-ChildItem -Path "$($PSScriptRoot)\Access Logs" | Where-Object {$_.Extension -eq ".log"})) {
    $LogSize = [math]::round($($Log.Length / 1KB), 2)
    if ($LogSize -gt $MaxLogSize) {
        Rename-Item -Path $Log.FullName -NewName "$($Log.Name.Replace('.log',"_$(get-date -format 'yyyyMMdd-HHmmss')._log"))"
    } else {
        # Do Nothing
    }
}