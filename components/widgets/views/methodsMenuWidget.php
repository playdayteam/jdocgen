<?php

use yii\helpers\Html;

$methodParser = new \app\components\MethodParser();

?>
<div>
    <h3>Methods</h3>
    <ul class="list-unstyled">
        <?php foreach ($methods as $key => $method): ?>
            <?php

            $id = 'collapse' . $key;

            $expanded = Yii::$app->request->getQueryParam('expand') == $id;
            $class = $expanded ? 'collapse in' : 'collapse';

            ?>
            <?php $methodParser->load($method) ?>
            <li>
                <span><?= $expanded ? '-' : '+'?></span>
                <a class="controller_title" data-toggle="collapse" href="#<?= $id ?>" aria-expanded="false" aria-controls="collapseExample"><?= $methodParser->getId() ?></a>
                <div class="<?= $class ?>" id="<?= $id ?>">
                    <ul>
                        <?php foreach ($methodParser->getActions() as $action): ?>
                            <li>
                                <?= Html::a($action['id'], $action['url'] . '&expand=' . $id, [
                                    'class' => $methodParser->selected($action) ? 'active-item' : null
                                ]) ?>
                            </li>
                        <?php endforeach ?>
                    </ul>
                </div>
            </li>
        <?php endforeach ?>
    </ul>
</div>