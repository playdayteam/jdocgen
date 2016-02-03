<?php

use app\components\ObjectRender;

?>

<div role="tabpanel">
    <?php

    $tab = Yii::$app->request->get('tab');

    $items = [
        [
            'text' => 'Change history',
            'url' => '/',
            'active' => !$tab
        ],
        [
            'text' => 'Methods (' . $stat['actions_count'] . ')',
            'url' => '/?tab=methods',
            'active' => $tab == 'methods'
        ],
        [
            'text' => 'Оbjects (' . $stat['objects_count'] . ')',
            'url' => '/?tab=objects',
            'active' => $tab == 'objects'
        ],
        [
            'text' => 'README.md',
            'url' => '/?tab=doc',
            'active' => $tab == 'doc'
        ],
//        [
//            'text' => 'Ошибки',
//            'url' => '/?tab=errors',
//            'active' => $tab == 'errors'
//        ],
    ];

    switch ($tab) {
        case 'methods':
            $content = \app\components\widgets\MethodsListWidget::widget();
            break;
        case 'objects':
            $content = \app\components\widgets\ObjectsListWidget::widget();
            break;
        case 'errors':
            $content = \app\components\widgets\ErrorsList::widget();
            break;
        case 'doc':
            $content = \kartik\markdown\Markdown::convert(file_get_contents(Yii::getAlias('@app') . '/README.md'));
            break;
        default:
            $content = \app\components\widgets\DiffWidget::widget();
    }
    ?>

    <ul class="nav nav-tabs">
        <?php foreach ($items as $item): ?>
            <li role="presentation"
                <?php if ($item['active']): ?>
                    class="active"
                <?php endif ?>
            >
                <a href="<?= $item['url'] ?>"><?= $item['text'] ?></a>
            </li>
        <?php endforeach ?>
    </ul>

    <?= $content ?>

</div>


<script type="javascript">

    $(function () {
    //        var hash = window.location.hash;
    //        hash && $('ul.nav a[href="' + hash + '"]').tab('show');
    //
    //        window.location.hash = "bla";
    //
    //        $('#myTab a').click(function (e) {
    //            e.preventDefault()
    //            $(this).tab('show');
    //            var scrollmem = $('body').scrollTop();
    //            window.location.hash = this.hash;
    //            $('html,body').scrollTop(scrollmem);
    //        });
    //    });
</script>