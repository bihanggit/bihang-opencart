<?php
class ControllerPaymentOklink extends Controller {
	public function index() {
		$this->load->language('payment/oklink');
		$data['button_confirm'] = $this->language->get('button_confirm');

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		if( $order_info['currency_code'] == 'USD' || $order_info['currency_code'] == 'CNY' || $order_info['currency_code'] == 'BTC' ){
		    $data['oklink_confirm_url'] = $this->url->link('payment/oklink/confirm');
		   	$data['error_warning'] = '';
		}else{
			$data['error_warning'] = $this->language->get('erro_currency');
		}
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/oklink.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/oklink.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/oklink.tpl', $data);
		}
	}

	public function confirm(){

		    $this->load->model('checkout/order');

		    $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);		
		    $this->load->model('payment/oklink');
		    $client = $this->model_payment_oklink->getClinet();
		    $params = array(
		    				'name'           => 'Order #'.$order_info['order_id'],
		    				'price'          => $order_info['total'],
		    				'price_currency' => $order_info['currency_code'],
		     				'custom'         => $order_info['order_id'],
		    				'callback_url'   => $this->url->link('payment/oklink/callback', '', 'SSL'),
		    				'success_url'    => $this->url->link('checkout/success'),

		    	);
		    $result = $client->buttonsButton($params);
	 	    if( $result->success == true ){
		    	$button_id = $result->button->id;
		    	$oklink_url = 'https://www.oklink.com/merchant/mPayOrderStemp1.do?buttonid='.$button_id;
		    	header('Location: '.$oklink_url);
		    }else{
		    	$data['error_warning'] = $result->error;
		    }
		    return $this->load->view('default/template/payment/oklink.tpl', $data);

	}

	public function callback() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			$this->load->model('payment/oklink');
			$client = $this->model_payment_oklink->getClinet();
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