function paginationLinks($baseUrl, $totalRow, $perPage, $uriSegment){
		$CI =& get_instance();
		$CI->load->library('pagination');
		$config = array(
			'per_page' => '1',
			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',
			'num_tag_open' => '<li class="page-item"><span class="page-link">',
			'num_tag_close' => '</span></li>',
			'cur_tag_open' => '<li class="page-item active"><a class="page-link" href="#">',
			'cur_tag_close' => '</a></li>',
			'prev_tag_open' => '<li class="page-item"><span class="page-link">',
			'prev_tag_close' => '</span></li>',
			'next_tag_open' => '<li class="page-item"><span class="page-link">',
			'next_tag_close' => '</span></li>',
			'prev_link' => '<i class="fa fa-backward"></i>',
			'next_link' => '<i class="fa fa-forward"></i>',
			'last_tag_open' => '<li class="page-item"><span class="page-link">',
			'last_tag_close' => '</span></li>',
			'first_tag_open' => '<li class="page-item"><span class="page-link">',
			'first_tag_close' => '</span></li>'
		);
		$config["base_url"] = $baseUrl;
		$config["total_rows"] = $totalRow;
		$config["per_page"] = $perPage;
		$config["uri_segment"] = $uriSegment;
		$numberLinks = $config["total_rows"] / $config["per_page"];
		$config["num_links"] = round($numberLinks);
		$CI->pagination->initialize($config);
		return  $CI->pagination->create_links();
	}