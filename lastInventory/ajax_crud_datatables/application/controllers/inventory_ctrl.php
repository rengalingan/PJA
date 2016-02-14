<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class inventory_ctrl extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('inventory_model','inventory');
	}

	public function index()
	{
		$this->load->helper('url');
		$this->load->view('inventory_view');
	}

	public function ajax_list()
	{
		$list = $this->inventory->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $inventory) {
			$no++;
			$row = array();
			$row[] = $inventory->Name;
			$row[] = $inventory->Description;
			$row[] = $inventory->Quantity;


			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void()" title="Edit" onclick="edit_inventory('."'".$inventory->id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void()" title="Hapus" onclick="delete_inventory('."'".$inventory->id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->inventory->count_all(),
						"recordsFiltered" => $this->inventory->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->inventory->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$data = array(
				'Name' => $this->input->post('Name'),
				'Description' => $this->input->post('Description'),
				'Quantity' => $this->input->post('Quantity'),
			);
		$insert = $this->inventory->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$data = array(
				'Name' => $this->input->post('Name'),
				'Description' => $this->input->post('Description'),
				'Quantity' => $this->input->post('Quantity'),
			);
		$this->inventory->update(array('id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->inventory->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}




}
