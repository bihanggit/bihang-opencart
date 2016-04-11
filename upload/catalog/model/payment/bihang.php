<?php
class ModelPaymenBihang extends Model {

    public function getClinet(){
		$key           = $this->config->get('bihang_api_key');
		$secret        = $this->config->get('bihang_api_secret');

		require_once(dirname(__FILE__) . '/bihang/Bihang.php');
        return Bihang::withApiKey($key, $secret);
    }

	public function getMethod($address, $total) {
		$this->load->language('payment/bihang');

		$method_data = array(
			'code'       => 'bihang',
			'title'      => $this->language->get('text_title'),
			'terms'      => 'BitCoin',
			'sort_order' => $this->config->get('bihang_sort_order')
		);

		return $method_data;
	}
}