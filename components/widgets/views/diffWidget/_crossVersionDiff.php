<?php

$methods = isset($data['methods']) ? $data['methods'] : null;

$objects = isset($data['objects']) ? $data['objects'] : null;

if ($methods || $objects) {

    if (isset($data['methods'])) {
        echo $this->render('methods', ['methods' => $methods]);
    }

    if (isset($data['objects'])) {
        echo $this->render('objects', ['objects' => $objects]);
    }
} else {
    ?>
    <div class="alert alert-warning" style="margin-top: 20px">
        No data
    </div>
<?php
}
