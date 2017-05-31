<?
function CheckUserCount(){

	$star_date = COption::GetOptionString("main", "START_DATE");

	if(!$star_date){
		COption::SetOptionString("main", "START_DATE", strtotime('now'));
		$star_date = COption::GetOptionString("main", "START_DATE");
	}

	$cur_date = strtotime('now');
	$days = (int)(($cur_date - $star_date)/(60*60*24));

	$by = "date_register";
	$order = "desc";
	$filter = array();

	$rsUsers = CUser::GetList($by, $order, $filter);

	while ($arResult = $rsUsers->GetNext())
	{
		$users[]=$arResult;
	}

	foreach($users as $userF){
		$reg_date = strtotime($userF["DATE_REGISTER"]);
		if(($reg_date>$star_date)&&($reg_date<=$cur_date)){
			$userNew[] = $userF;
		}
	}
	$count = count($userNew);

	//емэйлы админов
	$by = "";
	$order = "";
	$filter = array(
		"ACTIVE" => "Y",
		"GROUPS_ID" => array(1)
	);
	$rsAdmins = CUser::GetList($by, $order, $filter);
	$admins_emails="";
	while ($arResult = $rsAdmins->GetNext())
	{
		$admins_emails.= $arResult["EMAIL"].",";
	}

	//отправка
	$arEventFields = array(
		"COUNT" => $count,
		"DAYS" => $days,
		"SEND_TO" => $admins_emails,
	);
	CEvent::Send("USERS_COUNT", SITE_ID, $arEventFields);

	//установка новой даты
	COption::SetOptionString("main", "START_DATE", $cur_date);

	return "CheckUserCount();";
}