<?php

class readline {
    public static $prompt = ">";
    public static $endChar = "q";

    private static function createOptionsArray(array $options){
        $result = array();
        foreach($options as $index => $desc){
            $result[] = array("value" => $desc, "on" => false, "original_index" => $index);
        }

        return $result;
    }

    private static function switchOption($optionIndex, array &$options){
        if($optionIndex === self::$endChar) return false;

        if(array_key_exists($optionIndex, $options)){
            $options[$optionIndex]["on"] = !$options[$optionIndex]["on"];
        }

        return true;
    }

    private static function bool2Char($bool){
        if($bool) return "\033[32m■\033[0m ";

        return "";
    }

    private static function enabledOptionsToArray($options){
        $result = array();
        foreach($options as $index => $data){
            if($data["on"]) $result[$data["original_index"]] = $data["value"];
        }

        return $result;
    }

    public static function readAnswers(array $optionsToShow){
        if(count($optionsToShow) < 1) throw new Exception("No options supplied");

        $options = self::createOptionsArray($optionsToShow);
        echo "Please choose enter one or more options - confirm with '".self::$endChar."' when done\n";
        do{
            echo "Available options\n----------------\n";
            foreach($options as $index => $values){
                echo self::bool2Char($values["on"])."${index}) ".$values["original_index"]."\n";
            }
            echo "----------------\n";
        }while(self::switchOption($result = readline(self::$prompt), $options));

        return self::enabledOptionsToArray($options);
    }

    public static function readString($description){
        echo $description."\n---------------\n";

        return readline(self::$prompt);
    }
}