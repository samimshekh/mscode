<?php
class Visual {
    private static string $VisualName;

    public static function GetVisualName() {
        return static::$VisualName ?? null;
    }

    public static function SetVisualName(string $value) {
        static::$VisualName = $value;
    }
}

function view(string $VisualName, array $data = []) 
{
    Visual::SetVisualName($VisualName);
    extract($data);
    require(Settings\Paths::VISUALS_PATH . "$VisualName.php"); 
}

function frames(array $FrameNames, array $data)
{
    
    $VisualName = Visual::GetVisualName();
    extract($data);
    foreach ($FrameNames as $value) {
        require(Settings\Paths::VISUALS_PATH . "Frames/$VisualName/$value.php"); 
    }
}