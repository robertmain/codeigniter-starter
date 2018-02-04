<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

An uncaught Exception was encountered

Type:        <?php echo get_class($exception), PHP_EOL; ?>
Message:     <?php echo $message, PHP_EOL; ?>
Filename:    <?php echo $exception->getFile(), PHP_EOL; ?>
Line Number: <?php echo $exception->getLine(); ?>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === true) : ?>
Backtrace:
<?php foreach ($exception->getTrace() as $error) : ?>
<?php if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0) : ?>
    File: <?php echo $error['file'], PHP_EOL; ?>
    Line: <?php echo $error['line'], PHP_EOL; ?>
    Function: <?php echo $error['function'], PHP_EOL .PHP_EOL; ?>
<?php endif ?>
<?php endforeach ?>

<?php endif ?>
