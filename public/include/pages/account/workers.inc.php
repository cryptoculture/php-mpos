<?php
$defflip = (!cfip()) ? exit(header('HTTP/1.1 401 Unauthorized')) : 1;

if ($user->isAuthenticated()) {
  switch (@$_REQUEST['do']) {
  case 'delete':
    if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
      if ($worker->deleteWorker($_SESSION['USERDATA']['id'], $_GET['id'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Worker removed', 'TYPE' => 'alert alert-success');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $worker->getError(), 'TYPE' => 'alert alert-danger');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
    }
    break;

  case 'add':
    if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
      if ($worker->addWorker($_SESSION['USERDATA']['id'], $_POST['username'], $_POST['password'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Worker added', 'TYPE' => 'alert alert-success');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $worker->getError(), 'TYPE' => 'alert alert-danger');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
    }
    break;

  case 'update':
    if (!$config['csrf']['enabled'] || $config['csrf']['enabled'] && $csrftoken->valid) {
      if ($worker->updateWorkers($_SESSION['USERDATA']['id'], @$_POST['data'])) {
        $_SESSION['POPUP'][] = array('CONTENT' => 'Worker updated', 'TYPE' => 'alert alert-success');
      } else {
        $_SESSION['POPUP'][] = array('CONTENT' => $worker->getError(), 'TYPE' => 'alert alert-danger');
      }
    } else {
      $_SESSION['POPUP'][] = array('CONTENT' => $csrftoken->getErrorWithDescriptionHTML(), 'TYPE' => 'alert alert-warning');
    }
    break;
  }

  $smarty->assign('DISABLE_IDLEWORKERNOTIFICATIONS', $setting->getValue('notifications_disable_idle_worker'));
  $smarty->assign('DISABLE_CUSTOM_WORKER_DIFFICULTY', $setting->getValue('disable_custom_worker_difficulty'));
  $aWorkers = $worker->getWorkers($_SESSION['USERDATA']['id']);
  if (!$aWorkers) $_SESSION['POPUP'][] = array('CONTENT' => 'You have no workers configured', 'TYPE' => 'alert alert-danger');

  $smarty->assign('WORKERS', $aWorkers);
}
$smarty->assign('CONTENT', 'default.tpl');

?>
