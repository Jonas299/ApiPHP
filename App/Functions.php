<?php

namespace App;

class Functions
{
    static function verifyRequest($data, $parameters)
    {
        $response['error'] = 0;
        //$parameters = str_replace($parameters, " ", "");
        $parameters = explode(',', $parameters);

        foreach ($parameters as $key => $parameter) {
            //@$type[0] == nameRequest
            $types = explode("|", $parameter);
            if (!empty($data[$types[0]])) {
                //explore types allow
                foreach ($types as $key => $type) {
                    if ($key > 0) {
                        if (is_array($data[$types[0]])) {
                            //if it's an array field
                            foreach($data[$types[0]] AS $dt){
                                $response = Functions::verifyField($type, $dt, $types[0],$response);
                            }
                        } else {
                            $response = Functions::verifyField($type, $data[$types[0]], $types[0],$response);
                        }
                    }
                }
            } else {
                $response["{$types[0]}"] = "empty";
                $response['error'] = true;
            }
        }
        return $response;
    }
    static function verifyField($type, $value, $field, $response){;
        if ($type == "number" && !is_numeric($value)) {
            $response["{$field}"] = "non-numeric value";
            $response['error'] = true;
        }
        if ($type == "string" && !is_string($value)) {
            $response["{$field}"] = "non-string value";
            $response['error'] = true;
        }
        if ($type == "boolean" && !is_bool($value)) {
            $response["{$field}"] = "non-boolean value";
            $response['error'] = true;
        }
        if ($type == "required" && empty($value)) {
            $response["{$field}"] = "this field is required";
            $response['error'] = true;
        }
        if ($type == "email" && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $response["{$field}"] = "invalid email";
            $response['error'] = true;
        }
        return $response;
    }
}
