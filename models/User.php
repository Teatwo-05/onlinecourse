<?php
require_once __DIR__ . '/../config/Database.php';

class User {

	public static function findByEmail($email)
	{
		$db = (new Database())->connect();
		$stmt = $db->prepare('SELECT * FROM users WHERE email = :email LIMIT 1');
		$stmt->execute(['email' => $email]);
		return $stmt->fetch() ?: false;
	}

	public static function findById($id)
	{
		$db = (new Database())->connect();
		$stmt = $db->prepare('SELECT * FROM users WHERE id = :id LIMIT 1');
		$stmt->execute(['id' => $id]);
		return $stmt->fetch() ?: false;
	}

	public static function create($data)
	{
		$db = (new Database())->connect();
		$stmt = $db->prepare('INSERT INTO users (name, email, password, role, created_at) VALUES (:name, :email, :password, :role, :created_at)');
		$now = date('Y-m-d H:i:s');
		$stmt->execute([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => password_hash($data['password'], PASSWORD_DEFAULT),
			'role' => isset($data['role']) ? $data['role'] : 'student',
			'created_at' => $now,
		]);

		return $db->lastInsertId();
	}

	public static function verifyCredentials($email, $password)
	{
		$user = self::findByEmail($email);
		if (!$user) return false;

		if (isset($user['password']) && password_verify($password, $user['password'])) {
			return $user;
		}

		return false;
	}

	public static function update($id, $data)
	{
		$db = (new Database())->connect();
		$fields = [];
		$params = ['id' => $id];

		if (isset($data['name'])) { $fields[] = 'name = :name'; $params['name'] = $data['name']; }
		if (isset($data['email'])) { $fields[] = 'email = :email'; $params['email'] = $data['email']; }
		if (isset($data['password'])) { $fields[] = 'password = :password'; $params['password'] = password_hash($data['password'], PASSWORD_DEFAULT); }
		if (isset($data['role'])) { $fields[] = 'role = :role'; $params['role'] = $data['role']; }

		if (empty($fields)) return false;

		$sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
		$stmt = $db->prepare($sql);
		return $stmt->execute($params);
	}
}

