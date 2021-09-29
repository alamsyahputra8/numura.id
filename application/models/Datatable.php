<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Datatable extends CI_Model {
	private $input = array();
	private $output = array();
	private $orderby = array();
	
	/*public function __construct( $input ) {
		$this->output = $this->input = $input;
	}*/

	public function get_input()
	{
		return $this->input;
	}
	
	public function get_output()
	{
		return $this->output;
	}

	/**
	 * Filters the list, based on a set of key => value arguments.
	 *
	 *
	 * @param array  $args     Optional. An array of key => value arguments to match
	 *                         against each object. Default empty array.
	 * @param string $operator Optional. The logical operation to perform. 'AND' means
	 *                         all elements from the array must match. 'OR' means only
	 *                         one element needs to match. 'NOT' means no elements may
	 *                         match. Default 'AND'.
	 *
	 * @return array Array of found values.
	 */
	public function filter( $args = array(), $operator = 'AND' )
	{
		if ( empty( $args ) ) {
			return $this->output;
		}

		$operator = strtoupper( $operator );

		if ( ! in_array( $operator, array( 'AND', 'OR', 'NOT' ), true ) ) {
			return array();
		}

		$count    = count( $args );
		$filtered = array();

		foreach ( $this->output as $key => $obj ) {
			$to_match = (array)$obj;

			$matched = 0;
			foreach ( $args as $m_key => $m_value ) {
				if ( array_key_exists( $m_key, $to_match ) && $m_value == $to_match[ $m_key ] ) {
					$matched++;
				}
			}

			if (
				( 'AND' == $operator && $matched == $count ) ||
				( 'OR' == $operator && $matched > 0 ) ||
				( 'NOT' == $operator && 0 == $matched )
			) {
				$filtered[ $key ] = $obj;
			}
		}

		$this->output = $filtered;

		return $this->output;
	}

	/**
	 * Plucks a certain field out of each object in the list.
	 *
	 * This has the same functionality and prototype of
	 * array_column() (PHP 5.5) but also supports objects.
	 *
	 *
	 * @param int|string $field     Field from the object to place instead of the entire object
	 * @param int|string $index_key Optional. Field from the object to use as keys for the new array.
	 *                              Default null.
	 *
	 * @return array Array of found values. If `$index_key` is set, an array of found values with keys
	 *               corresponding to `$index_key`. If `$index_key` is null, array keys from the original
	 *               `$list` will be preserved in the results.
	 */
	public function pluck( $field, $index_key = null )
	{
		if ( ! $index_key ) {
			/*
			 * This is simple. Could at some point wrap array_column()
			 * if we knew we had an array of arrays.
			 */
			foreach ( $this->output as $key => $value ) {
				if ( is_object( $value ) ) {
					$this->output[ $key ] = $value->$field;
				} else {
					$this->output[ $key ] = $value[ $field ];
				}
			}

			return $this->output;
		}

		/*
		 * When index_key is not set for a particular item, push the value
		 * to the end of the stack. This is how array_column() behaves.
		 */
		$newlist = array();
		foreach ( $this->output as $value ) {
			if ( is_object( $value ) ) {
				if ( isset( $value->$index_key ) ) {
					$newlist[ $value->$index_key ] = $value->$field;
				} else {
					$newlist[] = $value->$field;
				}
			} else {
				if ( isset( $value[ $index_key ] ) ) {
					$newlist[ $value[ $index_key ] ] = $value[ $field ];
				} else {
					$newlist[] = $value[ $field ];
				}
			}
		}

		$this->output = $newlist;

		return $this->output;
	}

	/**
	 * Sorts the list, based on one or more orderby arguments.
	 *
	 *
	 * @param string|array $orderby       Optional. Either the field name to order by or an array
	 *                                    of multiple orderby fields as $orderby => $order.
	 * @param string       $order         Optional. Either 'ASC' or 'DESC'. Only used if $orderby
	 *                                    is a string.
	 * @param bool         $preserve_keys Optional. Whether to preserve keys. Default false.
	 *
	 * @return array The sorted array.
	 */
	public function sort( $orderby = array(), $order = 'ASC', $preserve_keys = false )
	{
		if ( empty( $orderby ) ) {
			return $this->output;
		}

		if ( is_string( $orderby ) ) {
			$orderby = array( $orderby => $order );
		}

		foreach ( $orderby as $field => $direction ) {
			$orderby[ $field ] = 'DESC' === strtoupper( $direction ) ? 'DESC' : 'ASC';
		}

		$this->orderby = $orderby;

		if ( $preserve_keys ) {
			uasort( $this->output, array( $this, 'sort_callback' ) );
		} else {
			usort( $this->output, array( $this, 'sort_callback' ) );
		}

		$this->orderby = array();

		return $this->output;
	}

	/**
	 * Callback to sort the list by specific fields.
	 *
	 * @access private
	 *
	 * @see    List_Util::sort()
	 *
	 * @param object|array $a One object to compare.
	 * @param object|array $b The other object to compare.
	 *
	 * @return int 0 if both objects equal. -1 if second object should come first, 1 otherwise.
	 */
	private function sort_callback( $a, $b )
	{
		if ( empty( $this->orderby ) ) {
			return 0;
		}

		$a = (array)$a;
		$b = (array)$b;

		foreach ( $this->orderby as $field => $direction ) {
			if ( ! isset( $a[ $field ] ) || ! isset( $b[ $field ] ) ) {
				continue;
			}

			if ( $a[ $field ] == $b[ $field ] ) {
				continue;
			}

			$results = 'DESC' === $direction ? array( 1, -1 ) : array( -1, 1 );

			if ( is_numeric( $a[ $field ] ) && is_numeric( $b[ $field ] ) ) {
				return ( $a[ $field ] < $b[ $field ] ) ? $results[ 0 ] : $results[ 1 ];
			}

			return 0 > strcmp( $a[ $field ], $b[ $field ] ) ? $results[ 0 ] : $results[ 1 ];
		}

		return 0;
	}

	function filterArray( $array, $allowed = [] ) {
		return array_filter(
			$array,
			function ( $val, $key ) use ( $allowed ) { // N.b. $val, $key not $key, $val
				return isset( $allowed[ $key ] ) && ( $allowed[ $key ] === true || $allowed[ $key ] === $val );
			},
			ARRAY_FILTER_USE_BOTH
		);
	}

	function filterKeyword( $data, $search, $field = '' ) {
		$filter = '';
		if ( isset( $search['value'] ) ) {
			$filter = $search['value'];
		}
		if ( ! empty( $filter ) ) {
			if ( ! empty( $field ) ) {
				if ( strpos( strtolower( $field ), 'date' ) !== false ) {
					// filter by date range
					$data = filterByDateRange( $data, $filter, $field );
				} else {
					// filter by column
					$data = array_filter( $data, function ( $a ) use ( $field, $filter ) {
						return (boolean) preg_match( "/$filter/i", $a[ $field ] );
					} );
				}

			} else {
				// general filter
				$data = array_filter( $data, function ( $a ) use ( $filter ) {
					return (boolean) preg_grep( "/$filter/i", (array) $a );
				} );
			}
		}

		return $data;
	}

	function filterByDateRange( $data, $filter, $field ) {
		// filter by range
		if ( ! empty( $range = array_filter( explode( '|', $filter ) ) ) ) {
			$filter = $range;
		}

		if ( is_array( $filter ) ) {
			foreach ( $filter as &$date ) {
				// hardcoded date format
				$date = date_create_from_format( 'm/d/Y', stripcslashes( $date ) );
			}
			// filter by date range
			$data = array_filter( $data, function ( $a ) use ( $field, $filter ) {
				// hardcoded date format
				$current = date_create_from_format( 'm/d/Y', $a[ $field ] );
				$from    = $filter[0];
				$to      = $filter[1];
				if ( $from <= $current && $to >= $current ) {
					return true;
				}

				return false;
			} );
		}

		return $data;
	}

	public function generateDatatable ($columnsDefault,$jsonfile) {
		error_reporting(0);

		if ( isset( $_REQUEST['columnsDef'] ) && is_array( $_REQUEST['columnsDef'] ) ) {
			$columnsDefault = [];
			foreach ( $_REQUEST['columnsDef'] as $field ) {
				$columnsDefault[ $field ] = true;
			}
		}
		 
		$alldata = json_decode($jsonfile,true);
 
		$data = [];
		// internal use; filter selected columns only from raw data
		foreach ( $alldata as $d ) {
			$data[] = $this->datatable->filterArray( $d, $columnsDefault );
		}

		// count data
		$totalRecords = $totalDisplay = count( $data );

		// filter by general search keyword
		if ( isset( $_REQUEST['search'] ) ) {
			$data         = $this->datatable->filterKeyword( $data, $_REQUEST['search'] );
			$totalDisplay = count( $data );
		}

		if ( isset( $_REQUEST['columns'] ) && is_array( $_REQUEST['columns'] ) ) {
			foreach ( $_REQUEST['columns'] as $column ) {
				if ( isset( $column['search'] ) ) {
					$data         = $this->datatable->filterKeyword( $data, $column['search'], $column['data'] );
					$totalDisplay = count( $data );
				}
			}
		}

		// sort
		if ( isset( $_REQUEST['order'][0]['column'] ) && $_REQUEST['order'][0]['dir'] ) {
			$column = $_REQUEST['order'][0]['column'];
			$dir    = $_REQUEST['order'][0]['dir'];
			usort( $data, function ( $a, $b ) use ( $column, $dir ) {
				$a = array_slice( $a, $column, 1 );
				$b = array_slice( $b, $column, 1 );
				$a = array_pop( $a );
				$b = array_pop( $b );

				if ( $dir === 'asc' ) {
					return $a > $b ? true : false;
				}

				return $a < $b ? true : false;
			} );
		}

		// pagination length
		if ( isset( $_REQUEST['length'] ) ) {
			$data = array_splice( $data, $_REQUEST['start'], $_REQUEST['length'] );
		}

		// return array values only without the keys
		if ( isset( $_REQUEST['array_values'] ) && $_REQUEST['array_values'] ) {
			$tmp  = $data;
			$data = [];
			foreach ( $tmp as $d ) {
				$data[] = array_values( $d );
			}
		}

		$secho = 0;
		if ( isset( $_REQUEST['sEcho'] ) ) {
			$secho = intval( $_REQUEST['sEcho'] );
		}

		$result = [
			'iTotalRecords'        => $totalRecords,
			'iTotalDisplayRecords' => $totalDisplay,
			'sEcho'                => $secho,
			'sColumns'             => '',
			'aaData'               => $data,
		];

		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');
		header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
		header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

		echo json_encode( $result, JSON_PRETTY_PRINT );
	}
}
function list_filter( $list, $args = array(), $operator = 'AND' )
{
	if ( ! is_array( $list ) ) {
		return array();
	}

	$util = new Datatable( $list );

	return $util->filter( $args, $operator );
}