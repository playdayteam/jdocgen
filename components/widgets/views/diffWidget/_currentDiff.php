<?php

/**
 * @var \app\models\JsonHistory $model
 */

?>

<?php foreach ($models as $model): ?>

    <?php

    $data = $model->getDiffData();

    $methods = isset($data['methods']) ? $data['methods'] : null;

    $objects = isset($data['objects']) ? $data['objects'] : null;

    if ($methods || $objects) {

        echo '<h3>' . $model->getDateTitle() . '</h3>';

        if (isset($data['methods'])) {
            echo $this->render('methods', ['methods' => $methods]);
        }

        if (isset($data['objects'])) {
            echo $this->render('objects', ['objects' => $objects]);
        }
    }

    ?>

<?php endforeach ?>


<?php if (count($models) <= 1): ?>
    <div class="alert alert-warning" style="margin-top: 20px">
        No data
    </div>
<?php endif ?>



