<?php
/**
 * @author basic-app <dev@basic-app.com>
 * @license MIT
 * @link https://basic-app.com
 */
namespace BasicApp\Auth\Config;

use CodeIgniter\Events\Events;

Events::on('pre_system', function() {

    helper(['auth']);
});