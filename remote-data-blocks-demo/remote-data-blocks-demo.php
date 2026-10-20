<?php
/**
 * Plugin Name: Remote Data Blocks Demo (WordPress VIP)
 * Description: Credential-free demo blocks for Remote Data Blocks: State Weather (api.weather.gov), Zip Code (zippopotam.us), and CISA Known Exploited Vulnerabilities.
 * Author: WordPress VIP Solutions Engineering
 */

namespace RdbDemo;

defined( 'ABSPATH' ) || exit();

const KEV_URL = 'https://raw.githubusercontent.com/cisagov/kev-data/main/known_exploited_vulnerabilities.json';

/** State => [ name, capital, weather.gov gridpoint forecast path ] */
function states(): array {
	return [
		'AL' => [ 'Alabama', 'Montgomery', 'BMX/81,35/forecast' ],
		'AK' => [ 'Alaska', 'Juneau', 'AJK/194,163/forecast' ],
		'AZ' => [ 'Arizona', 'Phoenix', 'PSR/159,58/forecast' ],
		'AR' => [ 'Arkansas', 'Little Rock', 'LZK/82,73/forecast' ],
		'CA' => [ 'California', 'Sacramento', 'STO/41,68/forecast' ],
		'CO' => [ 'Colorado', 'Denver', 'BOU/63,62/forecast' ],
		'CT' => [ 'Connecticut', 'Hartford', 'BOX/22,65/forecast' ],
		'DE' => [ 'Delaware', 'Dover', 'PHI/42,41/forecast' ],
		'FL' => [ 'Florida', 'Tallahassee', 'TAE/84,86/forecast' ],
		'GA' => [ 'Georgia', 'Atlanta', 'FFC/51,87/forecast' ],
		'HI' => [ 'Hawaii', 'Honolulu', 'HFO/154,145/forecast' ],
		'ID' => [ 'Idaho', 'Boise', 'BOI/133,86/forecast' ],
		'IL' => [ 'Illinois', 'Springfield', 'ILX/47,55/forecast' ],
		'IN' => [ 'Indiana', 'Indianapolis', 'IND/58,69/forecast' ],
		'IA' => [ 'Iowa', 'Des Moines', 'DMX/73,49/forecast' ],
		'KS' => [ 'Kansas', 'Topeka', 'TOP/81,48/forecast' ],
		'KY' => [ 'Kentucky', 'Frankfort', 'LMK/81,78/forecast' ],
		'LA' => [ 'Louisiana', 'Baton Rouge', 'LIX/25,109/forecast' ],
		'ME' => [ 'Maine', 'Augusta', 'GYX/82,91/forecast' ],
		'MD' => [ 'Maryland', 'Annapolis', 'LWX/115,78/forecast' ],
		'MA' => [ 'Massachusetts', 'Boston', 'BOX/71,101/forecast' ],
		'MI' => [ 'Michigan', 'Lansing', 'GRR/79,39/forecast' ],
		'MN' => [ 'Minnesota', 'Saint Paul', 'MPX/114,71/forecast' ],
		'MS' => [ 'Mississippi', 'Jackson', 'JAN/76,63/forecast' ],
		'MO' => [ 'Missouri', 'Jefferson City', 'LSX/26,70/forecast' ],
		'MT' => [ 'Montana', 'Helena', 'TFX/69,104/forecast' ],
		'NE' => [ 'Nebraska', 'Lincoln', 'OAX/56,40/forecast' ],
		'NV' => [ 'Nevada', 'Carson City', 'REV/44,90/forecast' ],
		'NH' => [ 'New Hampshire', 'Concord', 'GYX/34,30/forecast' ],
		'NJ' => [ 'New Jersey', 'Trenton', 'PHI/62,93/forecast' ],
		'NM' => [ 'New Mexico', 'Santa Fe', 'ABQ/126,146/forecast' ],
		'NY' => [ 'New York', 'Albany', 'ALY/72,63/forecast' ],
		'NC' => [ 'North Carolina', 'Raleigh', 'RAH/75,57/forecast' ],
		'ND' => [ 'North Dakota', 'Bismarck', 'BIS/110,47/forecast' ],
		'OH' => [ 'Ohio', 'Columbus', 'ILN/85,81/forecast' ],
		'OK' => [ 'Oklahoma', 'Oklahoma City', 'OUN/97,94/forecast' ],
		'OR' => [ 'Oregon', 'Salem', 'PQR/96,80/forecast' ],
		'PA' => [ 'Pennsylvania', 'Harrisburg', 'CTP/105,38/forecast' ],
		'RI' => [ 'Rhode Island', 'Providence', 'BOX/64,75/forecast' ],
		'SC' => [ 'South Carolina', 'Columbia', 'CAE/65,61/forecast' ],
		'SD' => [ 'South Dakota', 'Pierre', 'ABR/55,44/forecast' ],
		'TN' => [ 'Tennessee', 'Nashville', 'OHX/50,57/forecast' ],
		'TX' => [ 'Texas', 'Austin', 'EWX/156,91/forecast' ],
		'UT' => [ 'Utah', 'Salt Lake City', 'SLC/100,175/forecast' ],
		'VT' => [ 'Vermont', 'Montpelier', 'BTV/111,50/forecast' ],
		'VA' => [ 'Virginia', 'Richmond', 'AKQ/46,77/forecast' ],
		'WA' => [ 'Washington', 'Olympia', 'SEW/101,45/forecast' ],
		'WV' => [ 'West Virginia', 'Charleston', 'RLX/63,67/forecast' ],
		'WI' => [ 'Wisconsin', 'Madison', 'MKX/38,64/forecast' ],
		'WY' => [ 'Wyoming', 'Cheyenne', 'CYS/110,14/forecast' ]

	];
}

/** Bound paragraph / heading markup helper. */
function bound( string $block, string $field, string $label, string $tag = 'p', string $extra_attrs = '' ): string {
	$name = 'p' === $tag ? 'paragraph' : 'heading';
	$level = '';
	if ( 'p' !== $tag ) {
		$level = ',"level":' . (int) substr( $tag, 1 );
	}
	$attrs = sprintf(
		'{"metadata":{"bindings":{"content":{"source":"remote-data/binding","args":{"block":"remote-data-blocks/%s","field":"%s"}}},"name":"%s"}%s%s}',
		$block, $field, $label, $level, $extra_attrs
	);
	$class = 'p' === $tag ? '' : ' class="wp-block-heading"';
	return "<!-- wp:{$name} {$attrs} -->\n<{$tag}{$class}></{$tag}>\n<!-- /wp:{$name} -->\n";
}

function static_text( string $text, string $tag = 'p' ): string {
	if ( 'p' === $tag ) {
		return "<!-- wp:paragraph {\"style\":{\"typography\":{\"fontWeight\":\"600\"}}} -->\n<p style=\"font-weight:600\">{$text}</p>\n<!-- /wp:paragraph -->\n";
	}
	$level = (int) substr( $tag, 1 );
	return "<!-- wp:heading {\"level\":{$level}} -->\n<{$tag} class=\"wp-block-heading\">{$text}</{$tag}>\n<!-- /wp:heading -->\n";
}

function bound_image( string $block, string $field, string $alt_field ): string {
	$attrs = sprintf(
		'{"width":"86px","sizeSlug":"full","metadata":{"bindings":{"url":{"source":"remote-data/binding","args":{"block":"remote-data-blocks/%1$s","field":"%2$s"}},"alt":{"source":"remote-data/binding","args":{"block":"remote-data-blocks/%1$s","field":"%3$s"}}},"name":"Icon"}}',
		$block, $field, $alt_field
	);
	return "<!-- wp:image {$attrs} -->\n<figure class=\"wp-block-image size-full is-resized\"><img alt=\"\" style=\"width:86px\"/></figure>\n<!-- /wp:image -->\n";
}

/* -------------------------------------------------------------------------
 * Pass input variables through to preprocess_response for the KEV queries.
 * ---------------------------------------------------------------------- */
add_filter( 'remote_data_blocks_request_details', function ( array $details, $query, array $input_variables ): array {
	if ( isset( $details['uri'] ) && KEV_URL === $details['uri'] ) {
		$details['rdb_demo_inputs'] = $input_variables;
	}
	return $details;
}, 10, 3 );

/* -------------------------------------------------------------------------
 * Patterns
 * ---------------------------------------------------------------------- */
function state_weather_pattern(): string {
	$b = 'state-weather';
	$period = function ( string $p ) use ( $b ): string {
		return "<!-- wp:column {\"style\":{\"spacing\":{\"padding\":{\"top\":\"20px\",\"right\":\"20px\",\"bottom\":\"20px\",\"left\":\"20px\"}},\"border\":{\"radius\":\"12px\",\"width\":\"1px\"}},\"borderColor\":\"contrast\"} -->\n<div class=\"wp-block-column has-border-color has-contrast-border-color\" style=\"border-width:1px;border-radius:12px;padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:20px\">\n"
			. bound( $b, "{$p}_name", 'Period', 'h3' )
			. bound_image( $b, "{$p}_icon", "{$p}_short" )
			. bound( $b, "{$p}_temp", 'Temperature', 'p', ',"fontSize":"x-large"' )
			. bound( $b, "{$p}_short", 'Conditions' )
			. static_text( 'Wind' )
			. bound( $b, "{$p}_wind", 'Wind' )
			. static_text( 'Chance of precipitation' )
			. bound( $b, "{$p}_precip", 'Precipitation' )
			. "</div>\n<!-- /wp:column -->\n";
	};
	return static_text( 'Today and tonight', 'h2' )
		. bound( $b, 'location', 'Location', 'p', ',"fontSize":"medium"' )
		. "<!-- wp:columns -->\n<div class=\"wp-block-columns\">\n" . $period( 'p0' ) . $period( 'p1' ) . "</div>\n<!-- /wp:columns -->\n";
}

function zip_pattern(): string {
	$b = 'zip-code';
	return bound( $b, 'place', 'City, State', 'h3' )
		. "<!-- wp:columns -->\n<div class=\"wp-block-columns\">\n"
		. "<!-- wp:column -->\n<div class=\"wp-block-column\">\n" . static_text( 'Zip code' ) . bound( $b, 'zip_code', 'Zip Code' ) . "</div>\n<!-- /wp:column -->\n"
		. "<!-- wp:column -->\n<div class=\"wp-block-column\">\n" . static_text( 'Region' ) . bound( $b, 'state', 'State' ) . "</div>\n<!-- /wp:column -->\n"
		. "<!-- wp:column -->\n<div class=\"wp-block-column\">\n" . static_text( 'Latitude' ) . bound( $b, 'latitude', 'Latitude' ) . "</div>\n<!-- /wp:column -->\n"
		. "<!-- wp:column -->\n<div class=\"wp-block-column\">\n" . static_text( 'Longitude' ) . bound( $b, 'longitude', 'Longitude' ) . "</div>\n<!-- /wp:column -->\n"
		. "</div>\n<!-- /wp:columns -->\n";
}

function kev_pattern(): string {
	$b = 'known-exploited-vulnerabilities';
	$button = sprintf(
		'<!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button {"metadata":{"bindings":{"url":{"source":"remote-data/binding","args":{"block":"remote-data-blocks/%1$s","field":"nvd_url"}}},"name":"NVD link"}} --><div class="wp-block-button"><a class="wp-block-button__link wp-element-button">View details on nist.gov</a></div><!-- /wp:button --></div><!-- /wp:buttons -->' . "\n",
		$b
	);
	return "<!-- wp:group {\"style\":{\"spacing\":{\"padding\":{\"top\":\"16px\",\"bottom\":\"16px\"}},\"border\":{\"bottom\":{\"width\":\"1px\"}}},\"layout\":{\"type\":\"constrained\"}} -->\n<div class=\"wp-block-group\" style=\"border-bottom-width:1px;padding-top:16px;padding-bottom:16px\">\n"
		. bound( $b, 'title', 'Vulnerability', 'h3' )
		. bound( $b, 'cve_id', 'CVE ID' )
		. bound( $b, 'vendor_product', 'Vendor / Product' )
		. static_text( 'Required action' )
		. bound( $b, 'required_action', 'Required action' )
		. static_text( 'Added to catalog' )
		. bound( $b, 'date_added', 'Date added' )
		. static_text( 'Remediation due' )
		. bound( $b, 'due_date', 'Due date' )
		. $button
		. "</div>\n<!-- /wp:group -->\n";
}

/* -------------------------------------------------------------------------
 * Block registration
 * ---------------------------------------------------------------------- */
add_action( 'init', function (): void {
	if ( ! function_exists( 'register_remote_data_block' ) ) {
		return;
	}

	/* ---------- State Weather: api.weather.gov ---------- */
	$nws = [
		'display_name' => 'National Weather Service',
		'endpoint' => 'https://api.weather.gov',
		'request_headers' => [ 'Accept' => 'application/geo+json' ],
	];

	$period_fields = function ( int $i ): array {
		$p = "p{$i}";
		$get = function ( array $data ) use ( $i ): array {
			return $data['properties']['periods'][ $i ] ?? [];
		};
		return [
			"{$p}_name" => [ 'name' => "Period {$i} name", 'type' => 'string', 'generate' => fn( $d ) => (string) ( $get( $d )['name'] ?? '' ) ],
			"{$p}_temp" => [ 'name' => "Period {$i} temperature", 'type' => 'string', 'generate' => fn( $d ) => isset( $get( $d )['temperature'] ) ? $get( $d )['temperature'] . '°' . $get( $d )['temperatureUnit'] : '' ],
			"{$p}_short" => [ 'name' => "Period {$i} conditions", 'type' => 'string', 'generate' => fn( $d ) => (string) ( $get( $d )['shortForecast'] ?? '' ) ],
			"{$p}_wind" => [ 'name' => "Period {$i} wind", 'type' => 'string', 'generate' => fn( $d ) => trim( ( $get( $d )['windDirection'] ?? '' ) . ' ' . ( $get( $d )['windSpeed'] ?? '' ) ) ],
			"{$p}_precip" => [ 'name' => "Period {$i} precipitation", 'type' => 'string', 'generate' => fn( $d ) => ( (int) ( $get( $d )['probabilityOfPrecipitation']['value'] ?? 0 ) ) . '%' ],
			"{$p}_icon" => [ 'name' => "Period {$i} icon", 'type' => 'image_url', 'generate' => fn( $d ) => (string) str_replace( 'size=medium', 'size=large', $get( $d )['icon'] ?? '' ) ],
		];
	};

	$state_weather_query = [
		'display_name' => 'Forecast for a state capital',
		'data_source' => $nws,
		'endpoint' => function ( array $in ): string {
			$state = states()[ strtoupper( (string) ( $in['state_code'] ?? 'CA' ) ) ] ?? states()['CA'];
			return 'https://api.weather.gov/gridpoints/' . $state[2];
		},
		'input_schema' => [
			'state_code' => [ 'name' => 'State', 'type' => 'id', 'required' => true ],
		],
		'output_schema' => [
			'is_collection' => false,
			'type' => array_merge(
				[
					'location' => [
						'name' => 'Location',
						'type' => 'string',
						'generate' => function ( array $_d, array $resp ): string {
							$state = states()[ strtoupper( (string) ( $resp['input_variables']['state_code'] ?? '' ) ) ] ?? null;
							return $state ? "{$state[1]}, {$state[0]}" : '';
						},
					],
				],
				$period_fields( 0 ),
				$period_fields( 1 )
			),
		],
		'cache_ttl' => 900,
	];

	$list_states_query = [
		'display_name' => 'Choose a state',
		'data_source' => $nws,
		// Small, CORS-friendly endpoint; the state list itself is static.
		'endpoint' => 'https://api.weather.gov/alerts/active/count',
		'preprocess_response' => function (): array {
			$out = [];
			foreach ( states() as $code => $s ) {
				$out[] = [ 'state_code' => $code, 'state' => $s[0], 'capital' => $s[1] ];
			}
			return [ 'states' => $out ];
		},
		'output_schema' => [
			'is_collection' => true,
			'path' => '$.states[*]',
			'type' => [
				'state_code' => [ 'name' => 'State code', 'type' => 'id', 'path' => '$.state_code' ],
				'state' => [ 'name' => 'State', 'type' => 'title', 'path' => '$.state' ],
				'capital' => [ 'name' => 'Capital', 'type' => 'string', 'path' => '$.capital' ],
			],
		],
		'cache_ttl' => 86400,
	];

	register_remote_data_block( [
		'title' => 'State Weather',
		'icon' => 'cloud',
		'render_query' => [ 'query' => $state_weather_query ],
		'selection_queries' => [
			[ 'display_name' => 'Choose a state', 'query' => $list_states_query, 'type' => 'list' ],
		],
		'patterns' => [
			[ 'title' => 'Modern State Weather', 'html' => state_weather_pattern(), 'role' => 'inner_blocks' ],
		],
	] );

	/* ---------- Zip Code: zippopotam.us ---------- */
	$zip_source = [ 'display_name' => 'Zippopotam.us', 'endpoint' => 'https://api.zippopotam.us/us/' ];

	register_remote_data_block( [
		'title' => 'Zip Code',
		'icon' => 'location',
		'render_query' => [
			'query' => [
				'display_name' => 'Get location by zip code',
				'data_source' => $zip_source,
				'endpoint' => fn( array $in ): string => 'https://api.zippopotam.us/us/' . rawurlencode( trim( (string) ( $in['zip_code'] ?? '' ) ) ),
				'input_schema' => [ 'zip_code' => [ 'name' => 'Zip Code', 'type' => 'string', 'required' => true ] ],
				'output_schema' => [
					'is_collection' => false,
					'type' => [
						'zip_code' => [ 'name' => 'Zip Code', 'path' => '$["post code"]', 'type' => 'string' ],
						'city' => [ 'name' => 'City', 'path' => '$.places[0]["place name"]', 'type' => 'string' ],
						'state' => [ 'name' => 'State', 'path' => '$.places[0].state', 'type' => 'string' ],
						'place' => [
							'name' => 'City, State',
							'type' => 'string',
							'generate' => fn( $d ) => ( $d['places'][0]['place name'] ?? '' ) . ', ' . ( $d['places'][0]['state abbreviation'] ?? '' ),
						],
						'latitude' => [ 'name' => 'Latitude', 'path' => '$.places[0].latitude', 'type' => 'string' ],
						'longitude' => [ 'name' => 'Longitude', 'path' => '$.places[0].longitude', 'type' => 'string' ],
					],
				],
				'cache_ttl' => 86400,
			],
		],
		'patterns' => [
			[ 'title' => 'Zip code details', 'html' => zip_pattern(), 'role' => 'inner_blocks' ],
		],
	] );

	/* ---------- CISA Known Exploited Vulnerabilities ---------- */
	$kev_source = [ 'display_name' => 'CISA KEV Catalog', 'endpoint' => KEV_URL ];

	$kev_fields = [
		'cve_id' => [ 'name' => 'CVE ID', 'type' => 'id', 'path' => '$.cveID' ],
		'title' => [ 'name' => 'Vulnerability', 'type' => 'title', 'path' => '$.vulnerabilityName' ],
		'vendor_product' => [ 'name' => 'Vendor / Product', 'type' => 'string', 'generate' => fn( $d ) => ( $d['vendorProject'] ?? '' ) . ' — ' . ( $d['product'] ?? '' ) ],
		'required_action' => [ 'name' => 'Required action', 'type' => 'string', 'path' => '$.requiredAction' ],
		'date_added' => [ 'name' => 'Date added', 'type' => 'string', 'path' => '$.dateAdded' ],
		'due_date' => [ 'name' => 'Due date', 'type' => 'string', 'path' => '$.dueDate' ],
		'description' => [ 'name' => 'Description', 'type' => 'string', 'path' => '$.shortDescription' ],
		'nvd_url' => [ 'name' => 'NVD link', 'type' => 'button_url', 'generate' => fn( $d ) => 'https://nvd.nist.gov/vuln/detail/' . ( $d['cveID'] ?? '' ) ],
	];

	$sorted_kev = function ( $data ): array {
		$items = is_array( $data['vulnerabilities'] ?? null ) ? $data['vulnerabilities'] : [];
		usort( $items, fn( $a, $b ) => strcmp( $b['dateAdded'] ?? '', $a['dateAdded'] ?? '' ) );
		return $items;
	};

	$kev_render_query = [
		'display_name' => 'Get vulnerabilities by CVE ID',
		'data_source' => $kev_source,
		'endpoint' => KEV_URL,
		'input_schema' => [ 'cve_id' => [ 'name' => 'CVE ID', 'type' => 'id:list' ] ],
		'preprocess_response' => function ( $data, array $req ) use ( $sorted_kev ): array {
			$ids = (array) ( $req['rdb_demo_inputs']['cve_id'] ?? [] );
			$ids = array_map( 'strval', $ids );
			$items = array_values( array_filter( $sorted_kev( $data ), fn( $v ) => in_array( $v['cveID'] ?? '', $ids, true ) ) );
			return [ 'items' => $items ];
		},
		'output_schema' => [ 'is_collection' => true, 'path' => '$.items[*]', 'type' => $kev_fields ],
		'cache_ttl' => 3600,
	];

	$kev_search_query = [
		'display_name' => 'Search the KEV catalog',
		'data_source' => $kev_source,
		'endpoint' => KEV_URL,
		'input_schema' => [
			'search' => [ 'name' => 'Search terms', 'type' => 'ui:search_input' ],
			'limit' => [ 'name' => 'Items per page', 'type' => 'ui:pagination_per_page', 'default_value' => 10 ],
			'page' => [ 'name' => 'Page', 'type' => 'ui:pagination_page', 'default_value' => 1 ],
		],
		'preprocess_response' => function ( $data, array $req ) use ( $sorted_kev ): array {
			$in = $req['rdb_demo_inputs'] ?? [];
			$term = strtolower( trim( (string) ( $in['search'] ?? '' ) ) );
			$limit = max( 1, (int) ( $in['limit'] ?? 10 ) );
			$page = max( 1, (int) ( $in['page'] ?? 1 ) );
			$items = $sorted_kev( $data );
			if ( '' !== $term ) {
				$items = array_values( array_filter( $items, function ( $v ) use ( $term ) {
					$hay = strtolower( implode( ' ', [ $v['cveID'] ?? '', $v['vendorProject'] ?? '', $v['product'] ?? '', $v['vulnerabilityName'] ?? '' ] ) );
					return str_contains( $hay, $term );
				} ) );
			}
			return [ 'total' => count( $items ), 'items' => array_slice( $items, ( $page - 1 ) * $limit, $limit ) ];
		},
		'output_schema' => [ 'is_collection' => true, 'path' => '$.items[*]', 'type' => $kev_fields ],
		'pagination_schema' => [ 'total_items' => [ 'name' => 'Total items', 'path' => '$.total', 'type' => 'integer' ] ],
		'cache_ttl' => 3600,
	];

	register_remote_data_block( [
		'title' => 'Known Exploited Vulnerabilities',
		'icon' => 'shield',
		'render_query' => [ 'query' => $kev_render_query ],
		'selection_queries' => [
			[ 'display_name' => 'Search the CISA KEV catalog', 'query' => $kev_search_query, 'type' => 'search' ],
		],
		'patterns' => [
			[ 'title' => 'Vulnerability card', 'html' => kev_pattern(), 'role' => 'inner_blocks' ],
		],
	] );
} );
