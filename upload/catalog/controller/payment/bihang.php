<?php
class ControllerPaymentBihang extends Controller {
	public function index() {
		$this->load->language('payment/bihang');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if( $order_info['currency_code'] == 'USD' || $order_info['currency_code'] == 'CNY' || $order_info['currency_code'] == 'BTC' ){
		    $data['bihang_confirm_url'] = $this->url->link('payment/bihang/confirm');
		   	$data['error_warning'] = '';
		}else{
			$data['error_warning'] = $this->language->get('erro_currency');
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/bihang.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/bihang.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/bihang.tpl', $data);
		}
	}

	public function confirm(){

		    $this->load->model('checkout/order');

		    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);		
		    $this->load->model('payment/bihang');
		    $client = $this->model_payment_bihang->getClinet();
		    $params = array(
		    				'name'           => 'Order #'.$order_info['order_id'],
		    				'price'          => $order_info['total'],
		    				'price_currency' => $order_info['currency_code'],
		     				'custom'         => $order_info['order_id'],
		    				'callback_url'   => $this->url->link('payment/bihang/callback', '', 'SSL'),
		    				'success_url'    => $this->url->link('checkout/success'),

		    	);
		    $result = $client->buttonsButton($params);
	 	    if( $result->success == true ){
		    	$button_id = $result->button->id;
		    	$bihang_url = 'https://www.bihang.com/merchant/mPayOrderStemp1.do?buttonid='.$button_id;
		    	header('Location: '.$bihang_url);
		    }else{
		    	$data['error_warning'] = $result->error;
		    }
		    return $this->load->view('default/template/payment/bihang.tpl', $data);

	}

	public function callback() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('payment/bihang');
			$client = $this->model_payment_bihang->getClinet();
            if( $client->checkCallback() ){
				$order = file_get_contents('php://input');
                $order = json_decode($order);
	            if( $order && ($order->status == 'completed' )){
				    $this->load->model('checkout/order');
				    $this->model_checkout_order->addOrderHistory($order->custom, $this->config->get('config_order_status_id')); 
				    header("HTTP/1.0 200 OK");
	            }
            }
		}
	}
}