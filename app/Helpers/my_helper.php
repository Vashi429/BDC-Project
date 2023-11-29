<?php



if (!function_exists('pr')) {
	function pr($res_array){
		echo '<pre>';print_r($res_array);die();
	}
}

if (!function_exists('dateformate')) {
	function dateformate($date){
		return date('d-m-Y',strtotime($date));
	}
}
if (!function_exists('dateformateWithTime')) {
	function dateformateWithTime($date){
		return date('d-m-Y H:i:s',strtotime($date));
	}
}
if (!function_exists('getValByCondition')) {

	function getValByCondition($table, $column, $condition = array()){

		$con = db_connect();

		$builder = $con->table($table);

		$builder->select($column);

		$builder->where($condition);

		$query = $builder->get();

		$row = $query->getRowArray();

		if (!empty($row)) {

			return $row[$column];

		} else {

			return 0;

		}

	}

}



if (!function_exists('getSettingValueByKey')) {

	function getSettingValueByKey($var_post_key){

		$con = db_connect();

		$builder = $con->table('mst_setting');

		$builder->select('var_post_value');

		$builder->where('var_post_key', $var_post_key);

		$query = $builder->get();

		$row = $query->getRowArray();

		if (!empty($row)) {

			return $row['var_post_value'];

		} else {

			return '';

		}

	}

}



