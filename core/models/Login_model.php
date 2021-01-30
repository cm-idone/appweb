<?php

defined('_EXEC') or die;

class Login_model extends Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function read_session($email)
	{
		$session['user'] = System::decode_json_to_array($this->database->select('users', [
			'id',
			'avatar',
			'firstname',
			'lastname',
			'email',
			'password',
			'language',
			'accounts'
		], [
			'email' => $email
		]));

		if (!empty($session['user']))
		{
			foreach ($session['user'][0]['accounts'] as $key => $value)
			{
				$value['account'] = System::decode_json_to_array($this->database->select('accounts', [
					'id',
					'avatar',
					'name',
					'type',
					'path',
					'email',
					'description',
					'website',
					'zip_code',
					'country',
					'city',
					'time_zone',
					'currency',
					'language',
					'fiscal',
					'work_team',
					'permissions',
					'settings',
					'blocked'
				], [
					'id' => $value['id']
				]));

				if (!empty($value['account']))
				{
					if ($value['account'][0]['type'] == 'business')
					{
						if ($value['permissions'] != 'all')
						{
							foreach ($value['permissions'] as $subkey => $subvalue)
							{
								$subvalue = $this->database->select('users_permissions', [
									'code'
								], [
									'id' => $subvalue
								]);

								if (!empty($subvalue))
									$value['permissions'][$subkey] = $subvalue[0]['code'];
								else
									unset($value['permissions'][$subkey]);
							}
						}

						$value['account'][0]['user']['permissions'] = $value['permissions'];
					}

					foreach ($value['account'][0]['permissions'] as $subkey => $subvalue)
					{
						$subvalue = $this->database->select('accounts_permissions', [
							'code'
						], [
							'id' => $subvalue
						]);

						if (!empty($subvalue))
							$value['account'][0]['permissions'][$subkey] = $subvalue[0]['code'];
						else
							unset($value['account'][0]['permissions'][$subkey]);
					}

					$session['user'][0]['accounts'][$key] = $value['account'][0];
				}
				else
					unset($session['user'][0]['accounts'][$key]);
			}

			$session['user'][0]['accounts'] = array_values($session['user'][0]['accounts']);

			if (!empty($session['user'][0]['accounts']))
			{
				if ($session['user'][0]['accounts'][0]['type'] == 'business')
					$session['user'][0]['permissions'] = $session['user'][0]['accounts'][0]['user']['permissions'];

				foreach ($session['user'][0]['accounts'] as $key => $value)
					unset($session['user'][0]['accounts'][$key]['user']);

				$session['account'] = $session['user'][0]['accounts'][0];
			}
			else
				$session['account'] = null;

			$session['user'] = $session['user'][0];

			return $session;
		}
		else
			return null;
	}
}
