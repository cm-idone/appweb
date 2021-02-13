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
		if (Session::get_value('vkye_user')['god'] == 'activate_and_wake_up')
		{
			$accounts = [];

			foreach (Session::get_value('vkye_user')['accounts'] as $value)
				array_push($accounts, $value['id']);
		}
		else
			$accounts = Session::get_value('vkye_account')['id'];

		$query = $this->database->select('locations', [
			'[>]accounts' => [
				'account' => 'id'
			]
		], [
			'locations.id',
			'accounts.name(account_name)',
			'locations.name',
			'locations.blocked'
		], [
            'locations.account' => $accounts,
            'ORDER' => [
    			'locations.name' => 'ASC'
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
