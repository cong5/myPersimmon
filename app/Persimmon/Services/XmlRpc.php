<?php

/**
 * Created by PhpStorm.
 * User: MrCong <i@cong5.net>
 * Date: 2017/2/25
 * Time: 17:22
 */

namespace Persimmon\Services;

use Illuminate\Contracts\Logging\Log;

/**
 * XmlRpc Encode 类
 * PHP自带的有xmlrpc类 @link http://php.net/manual/zh/book.xmlrpc.php
 * 但是PHP自带的xmlrpc类是实验性质的，同时文档过少，还有解析出来的xml结构在部分xmlrpc发布的软件上不支持
 * 本类中部分函数来自于github 的gist ，具体地址找不到了，即此感谢原作者
 * @package Persimmon\Services
 */
class XmlRpc
{

    protected static $debug = false;

    public static function XMLSerialize(&$data, $level = 0, $prior_key = NULL)
    {
        #assumes a hash, keys are the variable names
        $XMLSerialized_string = "";
        while (list($key, $value) = each($data)) {
            $inline = false;
            $numeric_array = false;
            $attributes = "";
            #echo "My current key is '$key', called with prior key '$prior_key'<br>";
            if (!strstr($key, " attr")) { #if it's not an attribute
                if (array_key_exists("$key attr", $data)) {
                    while (list($attr_name, $attr_value) = each($data["$key attr"])) {
                        #echo "Found attribute $attribute_name with value $attribute_value<br>";
                        $attr_value = &htmlspecialchars($attr_value, ENT_QUOTES);
                        $attributes .= " $attr_name=\"$attr_value\"";
                    }
                }

                if (is_numeric($key)) {
                    #echo "My current key ($key) is numeric. My parent key is '$prior_key'<br>";
                    $key = $prior_key;
                } else {
                    #you can't have numeric keys at two levels in a row, so this is ok
                    #echo "Checking to see if a numeric key exists in data.";
                    if (is_array($value) and array_key_exists(0, $value)) {
                        #	echo " It does! Calling myself as a result of a numeric array.<br>";
                        $numeric_array = true;
                        $XMLSerialized_string .= self::XMLSerialize($value, $level, $key);
                    }
                    #echo "<br>";
                }

                if (!$numeric_array) {
                    $XMLSerialized_string .= str_repeat("\t", $level) . "<$key$attributes>";

                    if (is_array($value)) {
                        $XMLSerialized_string .= "\r\n" . self::XMLSerialize($value, $level + 1);
                    } else {
                        $inline = true;
                        $XMLSerialized_string .= htmlspecialchars($value);
                    }

                    $XMLSerialized_string .= (!$inline ? str_repeat("\t", $level) : "") . "</$key>\r\n";
                }
            } else {
                #echo "Skipping attribute record for key $key<bR>";
            }
        }
        if ($level == 0) {
            $XMLSerialized_string = "<?xml version=\"1.0\" ?>\r\n" . $XMLSerialized_string;
            return $XMLSerialized_string;
        } else {
            return $XMLSerialized_string;
        }
    }

    public static function XMLRPCPrepare($data, $type = NULL)
    {
        if (is_array($data)) {
            $num_elements = count($data);
            if ((array_key_exists(0, $data) or !$num_elements) and $type != 'struct') { #it's an array
                if (!$num_elements) { #if the array is empty
                    $returnvalue = array('array' => array('data' => NULL));
                } else {
                    $returnvalue['array']['data']['value'] = array();
                    $temp = &$returnvalue['array']['data']['value'];
                    $count = self::count_numeric_items($data);
                    for ($n = 0; $n < $count; $n++) {
                        $type = NULL;
                        if (array_key_exists("$n type", $data)) {
                            $type = $data["$n type"];
                        }
                        $temp[$n] = self::XMLRPCPrepare($data[$n], $type);
                    }
                }
            } else { #it's a struct
                if (!$num_elements) { #if the struct is empty
                    $returnvalue = array('struct' => NULL);
                } else {
                    $returnvalue['struct']['member'] = array();
                    $temp = &$returnvalue['struct']['member'];
                    while (list($key, $value) = each($data)) {
                        if (substr($key, -5) != ' type') { #if it's not a type specifier
                            $type = NULL;
                            if (array_key_exists("$key type", $data)) {
                                $type = $data["$key type"];
                            }
                            $temp[] = array('name' => $key, 'value' => self::XMLRPCPrepare($value, $type));
                        }
                    }
                }
            }
        } else { #经典实例
            if (!$type) {
                /*
                if(is_int($data)){
                    $returnvalue['int'] = $data;
                    return $returnvalue;
                }elseif(is_float($data)){
                    $returnvalue['double'] = $data;
                    return $returnvalue;
                }elseif(is_bool($data)){
                    $returnvalue['boolean'] = ($data ? 1 : 0);
                    return $returnvalue;
                }elseif(preg_match('/^\d{8}T\d{2}:\d{2}:\d{2}$/', $data, $matches)){ #it's a date
                    $returnvalue['dateTime.iso8601'] = $data;
                    return $returnvalue;
                }elseif(is_string($data)){
                    $returnvalue['string'] = htmlspecialchars($data);
                    return $returnvalue;
                }
                */
                return $data;
            } else {
                $returnvalue[$type] = htmlspecialchars($data);
            }
        }
        return $returnvalue;
    }

    public static function XMLRPCResponse($return_value, $server = NULL)
    {
        $data["methodResponse"]["params"]["param"]["value"] = &$return_value;
        $return = self::XMLSerialize($data);

        if (self::$debug) {
            self::debug("XMLRPCResponse: Received the following data to return:\n" . var_export($return_value, true));
        }

        header("Connection: close");
        header("Content-Length: " . strlen($return));
        header("Content-Type: text/xml");
        header("Date: " . date("r"));
        if ($server) {
            header("Server: $server");
        }

        if (self::$debug) {
            self::debug("XMLRPCResponse: Sent the following response:\n" . var_export($return, true));
        }
        echo $return;
    }

    public static function XMLRPCError($faultCode, $faultString, $server = NULL)
    {
        $array["methodResponse"]["fault"]["value"]["struct"]["member"] = array();
        $temp = &$array["methodResponse"]["fault"]["value"]["struct"]["member"];
        $temp[0]["name"] = "faultCode";
        $temp[0]["value"]["int"] = $faultCode;
        $temp[1]["name"] = "faultString";
        $temp[1]["value"]["string"] = $faultString;

        $return = self::XMLSerialize($array);

        header("Connection: close");
        header("Content-Length: " . strlen($return));
        header("Content-Type: text/xml");
        header("Date: " . date("r"));
        if ($server) {
            header("Server: $server");
        }
        if (self::$debug) {
            self::debug("XMLRPC_error: Sent the following error response:\n" . var_export($return, true));
        }
        echo $return;
    }

    public static function response($data, $type = 'success')
    {
        switch ($type) {
            case 'success':
                self::XMLRPCResponse(self::XMLRPCPrepare($data)); //返回
                break;
            case 'error':
                $data = is_array($data) ? $data : ['faultCode' => 500,'faultString' => 'error'];
                self::XMLRPCError($data['faultCode'], $data['faultString']);
                break;
            default:
                break;
        }
    }

    public static function count_numeric_items(&$array)
    {
        return is_array($array) ? count(array_filter(array_keys($array), 'is_numeric')) : 0;
    }

    public static function debug($message)
    {
        Log::debug($message);
    }

}