<?php
require_once 'vendor/autoload.php';

if (isset($_SESSION['user'], $is_auth)) {

    $user['id'] = $_SESSION['user']['id'] ?? null;
    $user['name'] = $_SESSION['user']['name'] ?? null;
    $user['email'] = $_SESSION['user']['email'] ?? null;
    $lot_update = null;
    $bets = null;

    $sqlUsers = 'SELECT l.id, l.name, l.dt_end, l.vinner_id, b.dt_add, b.autor_id'
        . ' FROM lot l'
        . ' JOIN bets b'
        . ' ON l.id = b.lot_id'
        . ' WHERE l.dt_end <= current_date() AND l.vinner_id IS NULL'
        . ' ORDER BY b.dt_add DESC';
    $dbHelper->executeQuery($sqlUsers);
    $bets = $dbHelper->getResultArray()[0];

    if (isset($bets) && 0 < $dbHelper->getNumRows()) {
        $dbHelper->executeQuery('UPDATE lot SET vinner_id = ' . $bets['autor_id'] . ' WHERE id = ' . $bets['id']);
        $lot_update = $dbHelper->getNumRows();
    }

    if (isset($lot_update, $bets)) {
        $transport = new Swift_SmtpTransport('phpdemo.ru', 25);
        $transport->setUsername('keks@phpdemo.ru');
        $transport->setPassword('htmlacademy');

        $mailer = new Swift_Mailer($transport);

        $logger = new Swift_Plugins_Loggers_ArrayLogger();
        $mailer->registerPlugin(new Swift_Plugins_LoggerPlugin($logger));

        $message = new Swift_Message();
        $message->setSubject('Ваша ставка победила');
        $message->setFrom(['keks@phpdemo.ru' => 'Ваша ставка победила']);
        $message->setBcc($user['email']);

        $msg_content = include_template('email.php', [
            'user_name' => $user['name'],
            'lot_link'  => 'http://localhost/lot.php?lot_id=' . $bets['id'],
            'lot_name'  => $bets['name'],
        ]);
        $message->setBody($msg_content, 'text/html');

        $result = $mailer->send($message);

        if (isset($result)) {
            print('Письмо отправлено');
        }
        else {
            print('Не удалось отправить письмо: ' . $logger->dump());
        }
    }
}