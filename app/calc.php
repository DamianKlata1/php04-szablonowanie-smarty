<?php
require_once dirname(__FILE__) . '/../config.php';

require_once _ROOT_PATH.'/libs/smarty/Smarty.class.php';
function getParameters(&$form)
{
    $form['amount'] = isset($_REQUEST['amount']) ? $_REQUEST['amount'] : null;
    $form['years'] = isset($_REQUEST['years']) ? $_REQUEST['years'] : null;
    $form['interest'] = isset($_REQUEST['interest']) ? $_REQUEST['interest'] : null;
}

function validate(&$form, &$messages, &$infos)
{
    if (!(isset($form['amount']) && isset($form['years']) && isset($form['interest']))) {
        return false;
    }

    $infos[] = ' Przekazano parametry ';
    if ($form['amount'] == "") {
        $messages [] = 'Nie podano kwoty';
    }
    if ($form['years'] == "") {
        $messages [] = 'Nie podano liczby lat';
    }
    if ($form['interest'] == "") {
        $messages [] = 'Nie podano oprocentowania';
    }
    if (count($messages) != 0) return false;

    if (!is_numeric($form['amount'])) {
        $messages [] = 'Kwota nie jest liczbą całkowitą';
    }
    if (!is_numeric($form['years'])) {
        $messages [] = 'Czas kredytu nie jest liczbą całkowitą';
    }
    if (!is_numeric($form['interest'])) {
        $messages [] = 'Oprocentowanie nie jest liczbą całkowitą';
    }

    if (count($messages) != 0) return false;
    else return true;
}

function process(&$form, &$result, &$infos)
{
    $infos[]='Parametry poprawne, przechodzę do obliczęń.';
        //konwersja na int
    $form['amount']=intval($form['amount']);
    $form['years']=intval($form['years']);
    $form['interest']=intval($form['interest']);

    $amountOfMonths=$form['years']*12;

    $unroundedresult=($form['amount']+$form['amount']*($form['interest']/100))/$amountOfMonths;
    $result=round($unroundedresult,2);

}

$form=null;
$infos=array();
$result=null;
$messages=array();

getParameters($form);
if(validate($form,$messages,$infos)){
    process($form,$result,$infos);
}
// 4. Przygotowanie danych dla szablonu

$smarty = new Smarty();

$smarty->assign('app_url',_APP_URL);
$smarty->assign('root_path',_ROOT_PATH);
$smarty->assign('page_title','Przykład 04');
$smarty->assign('page_description','Profesjonalne szablonowanie oparte na bibliotece Smarty');
$smarty->assign('page_header','Szablony Smarty');

//pozostałe zmienne niekoniecznie muszą istnieć, dlatego sprawdzamy aby nie otrzymać ostrzeżenia
$smarty->assign('form',$form);
$smarty->assign('result',$result);
$smarty->assign('messages',$messages);
$smarty->assign('infos',$infos);

// 5. Wywołanie szablonu
$smarty->display(_ROOT_PATH.'/app/calc.html');




