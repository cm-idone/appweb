<?php

defined('_EXEC') or die;

class Locations_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function create_location($data)
	{
		$query = $this->database->insert('locations', [
			'account' => Session::get_value('vkye_account')['id'],
			'name' => $data['name'],
			'blocked' => false
		]);

		return $query;
	}

	public function read_locations()
	{
		$query = $this->database->select('locations', [
			'id',
			'name',
			'blocked'
		], [
            'account' => Session::get_value('vkye_account')['id'],
            'ORDER' => [
    			'name' => 'ASC'
    		]
        ]);

		return $query;
	}

	public function read_location($id)
	{
		$query = $this->database->select('locations', [
			'name'
		], [
			'id' => $id
		]);

		return !empty($query) ? $query[0] : null;
	}

	public function update_location($data)
	{
		$query = $this->database->update('locations', [
			'name' => $data['name']
		], [
			'id' => $data['id']
		]);

        return $query;
	}

	public function block_location($id)
	{
		$query = $this->database->update('locations', [
			'blocked' => true
		], [
			'id' => $id
		]);

        return $query;
	}

	public function unblock_location($id)
	{
		$query = $this->database->update('locations', [
			'blocked' => false
		], [
			'id' => $id
		]);

        return $query;
	}

	public function delete_location($id)
    {
		$query = $this->database->delete('locations', [
			'id' => $id
		]);

        return $query;
    }
}
