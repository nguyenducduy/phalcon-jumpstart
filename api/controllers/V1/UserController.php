<?php
namespace Controllers\V1;

class UserController extends BaseController
{
	public function getAction()
	{
		return array('Pong');
	}

	public function getoneAction()
	{
		return array('Pong');
	}

	public function postAction()
	{

		$parameter = $this->requestBody;

		$message = '';
		$success = 0;
		$myUser = new \Model\User();
		$myUserProfile = new \Model\UserProfile();

		if ($parameter['id'] > 0) {
			$myUser = \Model\User::findFirstById($parameter['id']);
			$myUserProfile = \Model\UserProfile::findFirstById($parameter['id']);
		}

		if ($parameter['name'] != '') {

			$myUser->name = $parameter['name'];
			$myUser->email = $parameter['email'] != '' ? $parameter['email'] :  'n/a';
			$myUser->password = (string) $this->security->hash('123456');
			$userGroup = \Model\UserGroup::findFirst(['name = :groupname:', 'bind' => ['groupname' => 'Gue']]);
			$myUser->role = $userGroup->id > 0 ? $userGroup->id : 3; // 3 là guest
			$myUser->avatar = $parameter['avatar'];
			$myUser->status = \Model\User::STATUS_ENABLE;

			if ($myUser->save()) {
				// Tạo user profile

	            $myUserProfile->id = $myUser->id;
	            $myUserProfile->birthday = $parameter['birthday'];
	            $myUserProfile->gender = in_array(
	            						$parameter['gender'], 
	            						[
	            							\Model\UserProfile::GENDER_MAN,
	            							\Model\UserProfile::GENDER_WOMAN
	            						]
	            						)
	            						? $parameter['gender']
	            						: \Model\UserProfile::GENDER_UNKNOWN;
	            $myUserProfile->phone = $parameter['phone'];
	            $myUserProfile->address = $parameter['address'];
	            $myUserProfile->city = $parameter['city'];
	            $myUserProfile->country = $parameter['country'];
	            $myUserProfile->ipaddress = $this->request->getClientAddress();
	            $myUserProfile->imagecount = $parameter['imagecount'];
	            $myUserProfile->newnotification = $parameter['newnotification'];

	            if ($myUserProfile->save()) {
	            	$success = 1;
	            	$message = [
	                    "errorCode" => 200,
	                    "userMessage" => "Cập nhật sản phẩm thành công.",
	                    "devMessage" => "Cập nhật sản phẩm thành công.",
	                    "more" => "json",
	                    "applicationCode" => "UPDATEPRICESUCCESS"
	                ];
	            }
			} else {
			}
		} 

		if ($success == 1) {
			return $message;
		} else {
			throw new \Exceptions\HTTPException(
				'Có lỗi trong quá trình tạo thành viên.',
				401,
				array(
					'dev' => 'Có lỗi trong quá trình tạo thành viên.',
					'internalCode' => 'ERRORCREATEUSER',
					'more' => 'json'
				)
			);
		}
	}
}