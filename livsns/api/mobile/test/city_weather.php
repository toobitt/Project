<?php
$temp = rand(0, 12);
echo json_encode(json_decode('{"root":{"bg":"' . $temp . '","icon":"' . $temp . '","temperature_range":"20-30℃","weather_condition":"晴","wind":"东南风3-4级","humidity":"湿度50%","rays":"紫外线 中等","temperature_current":"25℃", "days":[{"day":"周一","icon":"' . rand(0, 12) . '","weather_condition":"多云"}, {"day":"周二","icon":"' . rand(0, 12) . '","weather_condition":"多云"}, {"day":"周三","icon":"' . rand(0, 12) . '","weather_condition":"多云"}] }}'));
?>