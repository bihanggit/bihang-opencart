<?php
class ModelPaymentOklink extends Model {

    public function getClinet(){
		$key           = $this->config->get('oklink_api_key');
		$secret        = $this->config->get('oklink_api_secret');

		require_once(dirname(__FILE__) . '/oklink/Oklink.php');
        return Oklink::withApiKey($key, $secret);
    }

	public function getMethod($address, $total) {
		$this->load->language('payment/oklink');

		$method_data = array(
			'code'       => 'oklink',
			'title'      => $this->language->get('text_title'),
			'terms'      => 'BitCoin',
			'sort_order' => $this->config->get('oklink_sort_order')
		);

		return $method_data;
	}
}