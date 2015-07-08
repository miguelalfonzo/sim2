<?php

namespace Report;

use \Report\TbQuery;
use \Report\TbReporte;
use \Report\UserReport;
use \Validator;
use \DateTime;
use \Log;

class DataGroup
{
    // PROCESS MAIN DATA GROUP
    public static function process($parameters)
    {

        $result = array(
            'status' => 'OK'
        );
        try{
            Log::error("=================> DG 1");
            //$data, $rows, $columns, $values, $keyColumns
            self::showRam("DataGroup process ini");
Log::error("=================> DG 2");
            $rules  = array(
                'body'       => 'required|array', 
                'rows'       => 'required|array', 
                'columns' => 'array', 
                'values'     => 'required|array', 
                'keyColumns' => 'array'
            );
Log::error("=================> DG 3");
            $validator = Validator::make($parameters, $rules);
            if ($validator->fails()){
                $error            = $validator->messages();
                $result['status'] = 'ERROR';
                $result['data']   = $error;
                
                Log::error('['.__FUNCTION__.'] '. $error);
            }else{
                Log::error("=================> DG 4");
                $data = $parameters['body'];
                $filter = self::sortByFields($parameters['rows']);
               Log::error("=================> DG 5");
                $data   = self::array_orderby($data, $filter);
                Log::error("=================> DG 6");
                foreach ($parameters['rows'] as $key => $value) {
                    $data = self::recursiveGroup($data, $parameters['rows'], $value);
                }
                // Log::error(json_encode($data));
                Log::error("=================> DG 7");
                $data = self::operation($data, $parameters['values'], $parameters['rows'], $parameters['columns'], $parameters['keyColumns']);
                // Log::error(json_encode($data));
Log::error("=================> DG 8");
                $data = self::parseData($data, $parameters['rows'], $parameters['columns']);
Log::error("=================> DG 9");
                $temp_total  = array();
Log::error("=================> DG 10");
                foreach ($parameters['values'] as $key => $valueElementTemp) {
                    // Log::error($valueElementTemp);
                    $total        = 0;
                    $total_value  = 0;
                    // Log::error("valueElementTemp");
                    // Log::error($valueElementTemp);
                    $values_temp  = explode(":",$valueElementTemp);

                    $operator     = $values_temp[0];
                    $valueElement = $values_temp[1];
Log::error("=================> DG 11");
                    foreach ($data as $key => $value) {
                        foreach ($parameters['columns'] as $keyColumns => $valueColumns) {
                            Log::error("=================> DG 12");
                            if(is_array($value[$valueColumns])){
                                if (!isset($temp_total[$valueColumns][$valueElement])) {
                                    $temp_total[$valueColumns][$valueElement] = $value[$valueColumns][$valueElement];
                                } else {
                                    $temp_total[$valueColumns][$valueElement] += $value[$valueColumns][$valueElement];
                                }
                            }else{
                                if (!isset($temp_total[$valueColumns])) {
                                    $temp_total[$valueColumns] = $value[$valueColumns];
                                } else {
                                    $temp_total[$valueColumns] += $value[$valueColumns];
                                }
                            }
                            $total = $value[$valueColumns];
                        }
                        $total_value += $value[$valueElement];
                    }
Log::error("=================> DG 13");
                    $fields_size = count($parameters['rows']) - 1;
                    for ($i = 0; $i <= $fields_size; $i++) {
                        if ($i == $fields_size)
                            $temp_total[$parameters['rows'][$i]] = "Total";
                        else
                            $temp_total[$parameters['rows'][$i]] = "";
                    }
                    $temp_total[$valueElement] = $total_value;
                    Log::error("=================> DG 14");
                }
                $result['total'] = $temp_total;
                Log::error("=================> DG 15");
                self::showRam("DataGroup process ini");  
                Log::error("=================> DG 16");
                Log::error(json_encode($data));
                $result['data'] = $data;
                Log::error("=================> DG 17");
            }
        }
        catch (Exception $e) {
            $result["status"]  = 'ERROR';
            $result["message"] = REPORT_MESSAGE_EXCEPTION;
            Log::error('['.__FUNCTION__.'] '. $e);
        }
        finally
        {
            return $result;
        }    
    }

    public static function arrayCastRecursive($array)
    {
        set_time_limit(REPORT_TIME_LIMIT);
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = self::arrayCastRecursive($value);
                }
                if ($value instanceof stdClass) {
                    $array[$key] = self::arrayCastRecursive((array) $value);
                }
            }
        }
        if ($array instanceof stdClass) {
            
            return arrayCastRecursive((array) $array);
        }
        
        return $array;
    }
    
    public static function sortByFields($array)
    {
        $filter = array();
        foreach ($array as $key => $value) {
            array_push($filter, $value);
            array_push($filter, SORT_ASC);
        }
        return $filter;
    }

    public static function array_orderby()
    {
        // Log::error("array_orderby");
        // try{
        $args = func_get_args();
        // Log::error("array_orderby 1");
        $data = array_shift($args);
        $args = $args[0];
// Log::error("array_orderby 2");
        foreach ($args as $n => $field) {
            // Log::error("array_orderby 2 each ".$n." ".$field);
            if (is_string($field)) {
                // Log::error("array_orderby 2 each is string");
                $tmp = array();
                foreach ($data as $key => $row){
                    // Log::error("array_orderby 2 each each ".$key." ". json_encode($row));
                    // Log::error(json_encode($row).' '.$field);
                    $tmp[$key] = $row[$field];
                    // Log::error("array_orderby 2 each each ".$key);
                }
                // Log::error("array_orderby 2 each each fin");
                $args[$n] = $tmp;
                // Log::error("array_orderby 2 each if fin ");
            }
            // Log::error("array_orderby 2 each fin ");
        }
        // Log::error("array_orderby 3");
        $args[] =& $data;
        // Log::error($args);
        // Log::error("array_orderby 4");
        call_user_func_array('array_multisort', $args);
        // Log::error("array_orderby 5");
        return array_pop($args);
        // }catch(Exception $e){
            // Log::error($e);
        // }
    }
    
    public static function recursiveGroup($valueData, $field, $label)
    {
        $result;
        self::showRam("DataGroup recursiveGroup ini");  
        $elem = count($field) >= 1 ? array_shift($field) : $field;
        
        $igual = $elem == $label;
        
        if ($igual) {
            
            $result_temp = array();
            foreach ($valueData as $key => $value) {
                $result_temp[$value[$label]][] = $value;
            }
            $result = $result_temp;
        } else {
            
            foreach ($valueData as $key => $value) {
                $result_temp     = self::recursiveGroup($value, $field, $label);
                $valueData[$key] = $result_temp;
            }
            $result = $valueData;
        }
        self::showRam("DataGroup recursiveGroup ini");  
        return $result;
    }
    
    public static function operation($data, $values, $rows, $columns, $keyColumns)
    {
        Log::error("===================================================================================");
        Log::error($data);
        Log::error("===================================================================================");
        set_time_limit(REPORT_TIME_LIMIT);
        return self::operationForEach($data, $values, count($rows), $rows, $columns, $keyColumns);
    }
    
    private static function operationForEach($data, $values, $count, $rows, $columns, $keyColumns)
    {
        set_time_limit(REPORT_TIME_LIMIT);
        $result = array();
        try{
            $count--;
            if($count >= 1) {
                foreach ($data as $key => $value) {
                    // Log::error(json_encode($value));
                    if (is_array($value)) {
                        $recursiveResult = self::operationForEach($value, $values, $count, $rows, $columns, $keyColumns);
                        $result[$key]    = $recursiveResult;
                    }
                }
            } else {
                foreach ($data as $keyKeyValue => $valueValueValue) {
                    $temp_result = array();
                    $xlas = false;
                    foreach ($valueValueValue as $keyKeyKeyValue => $valueValueValueValue) {

                        $keyKeyValue = $keyKeyValue == '' ? NULL : $keyKeyValue;

                        if (!isset($temp_result[$keyKeyValue])) {
                            // Log::error("if");
                            $temp = array();
                            // Log::error("result ========> 1");
                            foreach ($rows as $keyField => $valueField) {
                                // Log::error($valueValueValueValue[$valueField]);
                                // if(!empty(trim($valueValueValueValue[$valueField], " ")))
                                    $temp[$valueField] = trim($valueValueValueValue[$valueField], " ");
                            }
                            // Log::error("result ========> 2");
                            // Log::error($temp);
                            // Log::error(count($temp));
                            if(count($temp)==0)
                                $xlas = true;
                            foreach ($keyColumns as $key => $value) {
                                $temp[$value] = $valueValueValueValue[$value];
                            }
                            // Log::error("result ========> 3");

                            foreach ($values as $key => $valueElement) {
                                $value_temp = explode(":",$valueElement);
                                $operator = $value_temp[0];
                                $values_element = $value_temp[1];

                                Log::error($values_element);
                                foreach ($columns as $key => $value) {
                                    // Log::error("result ========> each 1 ". $key);
                                    if(!isset($temp[$value]))
                                        $temp[$value] = array();
                                    if ($value == $valueValueValueValue[$keyColumns[0]]) {
                                        if ($operator == 'SUM') {
                                            $temp[$valueValueValueValue[$keyColumns[0]]][$values_element] = (int) ($valueValueValueValue[$values_element] === null ? 0 : $valueValueValueValue[$values_element]);
                                        }elseif ($operator == 'COUNT') {
                                            $temp[$valueValueValueValue[$keyColumns[0]]][$values_element] = array();
                                            $temp[$valueValueValueValue[$keyColumns[0]]][$values_element][] = $valueValueValueValue[$values_element];
                                        }
                                    } else {
                                        if ($operator == 'SUM') {
                                            $temp[$value][$values_element] = 0;
                                        }elseif ($operator == 'COUNT') {
                                            $temp[$value][$values_element] = array();
                                        }
                                    }
                                }
                                if ($operator == 'SUM') {
                                    $temp[$values_element] = $valueValueValueValue[$values_element];
                                } elseif ($operator == 'COUNT') {
                                    $temp[$values_element][] = $valueValueValueValue[$values_element];
                                }
                            }
                            // Log::error("result ========> 3");
                            // Log::error("temp");
                            // Log::error(json_encode($temp));
                            if($xlas == false){
                                // Log::error("result ========> 4");
                                // Log::error(json_encode($temp));
                                $temp_result[$keyKeyValue] = $temp;
                            }
                            // Log::error("result ========> 5");
                        } else {
                            Log::error("else");
                            foreach ($rows as $keyField => $valueField) {
                                $temp_result[$keyKeyValue][$valueField] = $valueValueValueValue[$valueField];
                                Log::error($valueValueValueValue[$valueField]);
                            }

                            foreach ($values as $key => $valueElement) {

                                $value_temp     = explode(":",$valueElement);
                                $operator       = $value_temp[0];
                                $values_element = $value_temp[1];
// Log::error($values_element);
                                foreach ($columns as $key => $value) {
                                    if ($valueValueValueValue[$keyColumns[0]] == $value) {
                                        if ($operator == 'SUM') {
                                            $temp_result[$keyKeyValue][$value][$values_element] += $valueValueValueValue[$values_element];
                                        } elseif ($operator == 'COUNT') {
                                            $temp_result[$keyKeyValue][$value][$values_element][] = $valueValueValueValue[$values_element];
                                        }
                                    }
                                }
                                if ($operator == 'SUM')
                                    $temp_result[$keyKeyValue][$values_element] += $valueValueValueValue[$values_element];
                                elseif ($operator == 'COUNT'){
                                    $temp_result[$keyKeyValue][$values_element][] = $valueValueValueValue[$values_element];
                                }
                            }
                            
                        }
                        
                    }
                    if ($xlas != true){
                        foreach ($values as $key => $valueElement) {
                            $value_temp     = explode(":",$valueElement);
                            $operator       = $value_temp[0];
                            $values_element = $value_temp[1];

                            if ($operator == 'COUNT'){
                                if(is_array($temp_result[$keyKeyValue][$values_element]))
                                {
                                    $temp_array_unique = array_unique($temp_result[$keyKeyValue][$values_element]);
                                    sort($temp_array_unique, SORT_NATURAL | SORT_FLAG_CASE);
                                    $temp_result[$keyKeyValue][$values_element] = count($temp_array_unique);
                                }
                                foreach ($columns as $colkey => $colvalue) {
                                    if(is_array($temp_result[$keyKeyValue][$colvalue][$values_element])){
                                        $temp_array_unique = array_unique($temp_result[$keyKeyValue][$colvalue][$values_element]);
                                        sort($temp_array_unique, SORT_NATURAL | SORT_FLAG_CASE);
                                        $temp_result[$keyKeyValue][$colvalue][$values_element] = count($temp_array_unique);
                                    }
                                }
                            }
                        }
                        $result[$keyKeyValue] = $temp_result[$keyKeyValue];
                    }
                }
                
            }
        }catch(Exception $e){
            Log::error($e);
        }finally{
            return $result;
        }
    }
    
    
    private static function parseDataForEach($data, $fields, $valuesList)
    {
        $result  = array();
        $element = array_shift($fields);
        if ($element) {
            if (count($fields) >= 1) {
                foreach ($data as $key => $value) {
                    $result = array_merge($result, self::parseDataForEach($value, $fields, $valuesList));
                }
            } else {
                foreach ($data as $key => $value) {
                    $result[] = $value;
                }
            }
        }
        return $result;
    }
    
    
    public static function parseData($data, $fields, $valuesList)
    {
        $result = array();
        $result = self::parseDataForEach($data, $fields, $valuesList);
        return $result;
    }
    
    
    
    public static function replaceSlaceDate($date)
    {
        return strpos($date, "/") == -1 ? $date : str_replace("/", "-", $date);
    }
    
    public static function cantidadMesesFechas($fromDate, $toDate)
    {
        $fechainicial = new DateTime(self::replaceSlaceDate($fromDate) . ' - 1 day');
        $fechafinal   = new DateTime(self::replaceSlaceDate($toDate));
        $diferencia   = $fechainicial->diff($fechafinal);
        
        $meses = ($diferencia->y * 12) + $diferencia->m + $diferencia->d / 30;
        return $meses;        
    }

    public static function showRam($identifier){
        $total_memoria  = ini_get('memory_limit');
        $memory_usage   = round((memory_get_usage()/1024)/1024);
        $peak_usage     = round((memory_get_peak_usage()/1024)/1024);
        // Log::error('#'. $identifier);
        // Log::error('Memoria en Uso: '. $memory_usage .'/'. $total_memoria .' M <br>');
        // Log::error('Uso Máximo    : '. $peak_usage .'/'. $total_memoria  .' M <br>');
    }
}