<?php
class ControllerPaymentBihang extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/bihang');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('bihang', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_api_key'] = $this->language->get('entry_api_key');
		$data['entry_api_secret'] = $this->language->get('entry_api_secret');		

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/bihang', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['action'] = $this->url->link('payment/bihang', 'token=' . $this->session->data['token'], 'SSL');

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['bihang_order_status_id'])) {
			$data['bihang_order_status_id'] = $this->request->post['bihang_order_status_id'];
		} else {
			$data['bihang_order_status_id'] = $this->config->get('bihang_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['bihang_status'])) {
			$data['bihang_status'] = $this->request->post['bihang_status'];
		} else {
			$data['bihang_status'] = $this->config->get('bihang_status');
		}

		if (isset($this->request->post['bihang_sort_order'])) {
			$data['bihang_sort_order'] = $this->request->post['bihang_sort_order'];
		} else {
			$data['bihang_sort_order'] = $this->config->get('bihang_sort_order');
		}

		if (isset($this->request->post['bihang_api_key'])) {
			$data['bihang_api_key'] = $this->request->post['bihang_api_key'];
		} else {
			$data['bihang_api_key'] = $this->config->get('bihang_api_key');
		}

		if (isset($this->request->post['bihang_api_secret'])) {
			$data['bihang_api_secret'] = $this->request->post['bihang_api_secret'];
		} else {
			$data['bihang_api_secret'] = $this->config->get('bihang_api_secret');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/bihang.tpl', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/bihang')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		return !$this->error;
	}
}