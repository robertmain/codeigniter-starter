<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

A PHP Error was encountered

Severity:    <?php echo $severity, PHP_EOL; ?>
Message:     <?php echo $message, PHP_EOL; ?>
Filename:    <?php echo $filepath, PHP_EOL; ?>
Line Number: <?php echo $line; ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === true) : ?>
Backtrace:
<?php foreach (debug_backtrace() as $error) : ?>
<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0) : ?>
    File: <?php echo $error['file'], PHP_EOL; ?>
    Line: <?php echo $error['line'], PHP_EOL; ?>
    Function: <?php echo $error['function'], PHP_EOL . PHP_EOL; ?>
<?php endif ?>
<?php endforeach ?>

<?php endif ?>
