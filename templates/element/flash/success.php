<?php

/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>
<div class="alert alert-success text-center alert-dismissible" id="flashSuccess" role="alert"><b><?= $message ?></b>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<script>
    // function hiddenMsg() {
    //     document.getElementById('flashSuccess').style.display = "none";
    // }
</script>