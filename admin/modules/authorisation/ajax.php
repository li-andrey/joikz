<? 
if(@$_POST['username'] AND @$_POST['password']){
	$login = $ob->pr($_POST['username']);
	$pass = $ob->pr($_POST['password']);
	$login = sha1($login);
	$pass = sha1($pass);
	$select = $mysql->query("select * from i_user where login='$login' and active=1");

	if($select->num_rows==1){
		$res = $select->fetch_array();
		if($res['password']==$pass){
			$group = $mysql->query("select * from i_user_group where id='".$res['id_group']."'");
			if($group->num_rows>0){
				$group_res = $group->fetch_array();
				if($group_res['active']==1){
					$_SESSION['user_id'] = $res['id'];
					$_SESSION['id_group'] = $res['id_group'];

					$version = $mysql->query("select * from i_lang where active=1 and `default`=1");
					$version_res = $version->fetch_array();
					$_SESSION['version'] = $version_res['name_reduction'];
					if(@$_POST['remember']==1) $ob->cookie($login."|".$pass);
					echo json_encode(array(
						'success' => 1,
						'msg' => '<script>location.href="/admin/modules/desktop.php";</script>',
					));
				}else {
					echo json_encode(array(
						'success'=>0,
						'msg' => 'Ваша группа не активна',
					));
				}
			}else {
				echo json_encode(array(
					'success'=>0,
					'msg' => 'Вы не находитесь ни в одной учётной группе',
				));
			}
		}else {
			echo json_encode(array(
				'success'=>0,
				'msg' => 'Неверный логин или пароль',
			));
		}
	}else {
		echo json_encode(array(
			'success'=>0,
			'msg' => 'Неверный логин или пароль',
		));
	}
}else {
	echo json_encode(array(
		'success'=>0,
		'msg' => 'Неверный логин или пароль',
	));
}

exit;