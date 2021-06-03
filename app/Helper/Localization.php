<?php

namespace App\Helper;
use Illuminate\Support\Facades\App;

class Localization{
    private static $GLOBAL_COUNTRIES = [
        "Brasil" =>[
            "langs" => [
                "pt-BR" => "br",
                "es" => "es"
            ],
            "user_code_types" =>[
                "cpf"
            ],
            "business_code_types" =>[
                "cnpj"
            ],
            "currencys" =>[
                "real" => "R$"
            ],
        ],
        "EUA" => [
            "langs" =>[
                "en" => "us",
            ],
            "user_code_types" =>[
                "ssn"
            ],
            "business_code_types" =>[
                "ein"
            ],
            "currencys" =>[
                "dolar" => "$"
            ],
        ]
    ];

    public static function getCountries(){
        $COUNTRIES = [];
        foreach(self::$GLOBAL_COUNTRIES as $key=>$country){
            array_push($COUNTRIES,$key);
        }
        return $COUNTRIES;
    }

    public static function getLangs(){
        $LANGS = [];
        foreach(self::$GLOBAL_COUNTRIES as $country){
            foreach($country['langs'] as $lang=> $flag){
                array_push($LANGS,$lang);
            }
        }
        return array_unique($LANGS);
    }

    public static function getLangsWithFlags(){
        $LANGS = [];
        foreach(self::$GLOBAL_COUNTRIES as $country){
            foreach($country['langs'] as $lang => $flag){
                array_push($LANGS,[
                    'flag' => $flag,
                    'abbr' => $lang
                ]);
            }
        }
        return $LANGS;
    }

    public static function getUserCodeTypes(){
        $USER_CODE_TYPES = [];
        foreach(self::$GLOBAL_COUNTRIES as $key=>$country){
            foreach($country['user_code_types'] as $lang){
                array_push($USER_CODE_TYPES,$lang);
            }
        }
        return array_unique($USER_CODE_TYPES);
    }
    public static function getBusinessCodeTypes(){
        $BUSINESS_CODE_TYPES = [];
        foreach(self::$GLOBAL_COUNTRIES as $country){
            foreach($country['business_code_types'] as $lang){
                array_push($BUSINESS_CODE_TYPES,$lang);
            }
        }
        return array_unique($BUSINESS_CODE_TYPES);
    }

    public static function getCurrencys($isName = true){
        $CURRENCYS = [];
        foreach(self::$GLOBAL_COUNTRIES as $country){
            foreach($country['currencys'] as $key => $name){
                if($isName) array_push($CURRENCYS,$key);
                else array_push($CURRENCYS,$name);
            }
        }
        return array_unique($CURRENCYS);
    }

    public static function setLocalization($lang){
        App::setLocale(str_replace("-","_",$lang));
    }

    public static function getCurrency($nameCountry = "Brasil",$item = 1){
        foreach(self::$GLOBAL_COUNTRIES as $key=>$country){
            if($key==$nameCountry){
                $n = 1;
                foreach($country['currencys'] as $key => $name){
                    if($item==$n)
                        return $key;
                    $n++;
                }
            }
        }
        return "dolar";
    }


}


?>
