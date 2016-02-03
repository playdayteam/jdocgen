<?php


use yii\helpers\Html;
use app\components\ObjectParser;

?>

<div>
    <h3>Objects</h3>
    <ol class="list-unstyled">
        <?php foreach ($objects as $object): ?>
            <?php if (!in_array($object['id'], ['meta'])): ?>
                <?php
                $objectParser = new ObjectParser($object);
                ?>
                <?php if ($objectParser->isRoot()): ?>
                    <li> -
                        <?= Html::a($objectParser->getTitle(), $objectParser->getUrl(), ['class' => $objectParser->selected() ? 'active-item' : null]) ?>
                        <?php
                            $children = $objectParser->getChildren($objects);
                        ?>
                        <?php if (count($children)): ?>
                            <ul>
                                <?php foreach ($children as $child): ?>
                                    <?php
                                        $objectParser = new ObjectParser($child);
                                    ?>
                                    <li><?= Html::a($objectParser->getTitle(), $objectParser->getUrl(), ['class' => $objectParser->selected() ? 'active-item' : null]) ?></li>
                                <?php endforeach ?>
                            </ul>
                        <?php endif ?>
                    </li>
                <?php endif ?>
            <?php endif ?>
        <?php endforeach ?>
    </ol>
</div>